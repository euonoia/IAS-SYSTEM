<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Rate Limiting: 5 failed attempts per 15 minutes
        $this->ensureIsNotRateLimited($request);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_employee_id';

        $user = User::where($loginField, $request->login)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey($request));

            // Log successful login
            $this->logActivity($user, 'login', 'Admin logged in', $request);

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        }

        // Get current attempts before incrementing
        $currentAttempts = RateLimiter::attempts($this->throttleKey($request)) ?? 0;
        $remainingAttempts = max(0, 5 - $currentAttempts);

        // Increment failed attempts
        RateLimiter::hit($this->throttleKey($request), 15 * 60);

        // Log failed attempt
        if ($user) {
            $this->logActivity($user, 'login_failed', 'Failed login attempt', $request);
        }

        $errorMessage = 'Invalid credentials. Please try again.';
        if ($remainingAttempts > 0) {
            $errorMessage .= ' (' . $remainingAttempts . ' ' . ($remainingAttempts === 1 ? 'attempt' : 'attempts') . ' left)';
        }

        return back()->with('error', $errorMessage);
    }

    public function logout(Request $request) {
        $user = Auth::user();
        
        if ($user) {
            $this->logActivity($user, 'logout', 'Admin logged out', $request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Ensure the login request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts = 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return 'login_attempts:' . $request->ip();
    }

    /**
     * Log admin activity to database.
     */
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
