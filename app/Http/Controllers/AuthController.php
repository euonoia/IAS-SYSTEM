<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Mail\OTPMail;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display the login form
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if ($request->session()->has('otp_user_id')) {
            return redirect()->route('otp.form');
        }

        return view('auth.login');
    }

    /**
     * Handle login request with OTP verification
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        $login = $this->sanitizeInput($request->login);
        $password = $this->sanitizeInput($request->password);
        
        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_employee_id';
        $user = User::where($loginField, $login)->first();

        // Step 1: Validate Password
        if ($user && Hash::check($password, $user->password_hash)) {
            RateLimiter::clear($this->throttleKey($request));

            // Step 2: Generate 6-digit OTP
            $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            try {
                // Step 3: Send clean email (using the OTPMail mailable)
                Mail::to($user->email)->send(new OTPMail($otpCode, $user->full_name ?? 'User'));

                // Step 4: Database record
                UserOtp::create([
                    'user_id' => $user->id,
                    'otp_code' => $otpCode,
                    'expires_at' => Carbon::now()->addMinutes(10),
                    'is_used' => 0,
                ]);

                $request->session()->put('otp_user_id', $user->id);
                $request->session()->put('otp_sent_at', now()->toDateTimeString());

                $this->logActivity($user, 'otp_sent', 'MFA code sent successfully to ' . $user->email, $request);

                return redirect()->route('otp.form')->with('success', 'Verification code sent.');

            } catch (\Exception $e) {
                // Detailed error handling for SMTP debugging
                $errorMsg = $e->getMessage();
                
                \Log::error('SMTP Error - Failed to send OTP email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error_message' => $errorMsg,
                    'error_code' => $e->getCode(),
                    'error_class' => get_class($e),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Return detailed error message to debug SMTP connection
                $debugMsg = 'Unable to send verification code. ';
                
                // Check for specific error patterns (case-insensitive)
                if (stripos($errorMsg, 'authentication') !== false || stripos($errorMsg, 'username') !== false || stripos($errorMsg, 'password') !== false) {
                    $debugMsg .= 'SMTP Authentication failed - Check Gmail App Password and username in .env';
                } elseif (stripos($errorMsg, 'connection') !== false || stripos($errorMsg, 'connect') !== false || stripos($errorMsg, 'timeout') !== false) {
                    $debugMsg .= 'SMTP Connection error - Verify host, port, and encryption settings';
                } elseif (stripos($errorMsg, 'invalid') !== false && stripos($errorMsg, 'address') !== false) {
                    $debugMsg .= 'Invalid email address on file';
                } else {
                    $debugMsg .= 'Mail server error - Check Laravel logs for details';
                }

                return back()
                    ->withInput(['login' => $request->login])
                    ->with('error', $debugMsg);
            }
        }

        RateLimiter::hit($this->throttleKey($request), 5);
        if ($user) {
            $this->logActivity($user, 'login_failed', 'Incorrect password attempt', $request);
        }

        $currentAttempts = RateLimiter::attempts($this->throttleKey($request));
        $remainingAttempts = max(0, 5 - $currentAttempts);
        $errorMessage = 'Invalid credentials.';
        if ($remainingAttempts > 0) {
            $errorMessage .= " ($remainingAttempts attempts left)";
        }

        return back()->withInput(['login' => $request->login])->with('error', $errorMessage);
    }

    /**
     * Display OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    /**
     * Verify OTP and complete login
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        $otpCode = $this->sanitizeInput($request->otp_code);

        $otp = UserOtp::where('user_id', $user->id)
            ->where('otp_code', $otpCode)
            ->where('is_used', 0)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp) {
            $this->logActivity($user, 'login_failed', 'Invalid OTP entered', $request);
            return back()->with('error', 'Invalid or expired code.');
        }

        // Finalize Login
        $otp->update(['is_used' => 1]);
        Auth::login($user);

        $request->session()->forget(['otp_user_id', 'otp_sent_at']);
        $request->session()->regenerate();

        $this->logActivity($user, 'login_success', 'Authenticated with OTP', $request);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $this->logActivity($user, 'logout', 'User logged out', $request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Resend OTP code to user's email
     * Throttled to prevent abuse (60 second cooldown)
     */
    public function resendOtp(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }

        // Throttle resend to prevent abuse (10 seconds between requests)
        $lastSent = $request->session()->get('otp_sent_at');
        if ($lastSent && now()->diffInSeconds($lastSent) < 10) {
            $waitSeconds = 10 - now()->diffInSeconds($lastSent);
            return back()->with('error', "Please wait {$waitSeconds} seconds before requesting a new code.");
        }

        // Generate new 6-digit OTP
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Send OTP to user's email
            Mail::to($user->email)->send(new OTPMail($otpCode, $user->full_name ?? 'User'));

            // Only save to database if email succeeds
            UserOtp::create([
                'user_id' => $user->id,
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => 0,
            ]);

            // Update session timestamp
            $request->session()->put('otp_sent_at', now()->toDateTimeString());

            // Log activity
            $this->logActivity($user, 'otp_resent', 'New OTP code resent to email', $request);

            return back()->with('success', 'A new verification code has been sent to your email.');

        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to resend code. Please try again.');
        }
    }

    /**
     * Clear OTP session and redirect to login
     */
    public function backToLogin(Request $request)
    {
        $request->session()->forget(['otp_user_id', 'otp_sent_at']);
        return redirect()->route('login');
    }

    // --- Helper Methods (Consistent with ProfileController) ---

    protected function sanitizeInput(?string $input): ?string
    {
        if (!$input) return null;
        $sanitized = strip_tags($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        return trim(preg_replace('/(<|>|script|iframe|javascript:|onerror|onload)/i', '', $sanitized));
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) return;
        
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $displayTime = $seconds < 60 ? "{$seconds} second" . ($seconds > 1 ? 's' : '') : ceil($seconds / 60) . " minute" . (ceil($seconds / 60) > 1 ? 's' : '');
        
        throw ValidationException::withMessages([
            'login' => "Too many login attempts. Please try again in {$displayTime}.",
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return 'login_attempts:' . $request->ip();
    }

    protected function logActivity(User $user, string $action, string $description, Request $request): void
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);
    }
}