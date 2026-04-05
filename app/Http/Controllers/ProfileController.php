<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password; 

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $settings = $this->loadSystemSettings();

        return view('profile.edit', [
            'user' => $user,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users_ums,email,' . $user->id,
            'student_employee_id' => 'nullable|string|max:50|unique:users_ums,student_employee_id,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = '/storage/' . $avatarPath;
        }

        $user->full_name = $this->sanitizeInput($request->full_name);
        $user->email = $this->sanitizeInput($request->email);
        $user->student_employee_id = $this->sanitizeInput($request->student_employee_id);
        $user->save();

        $this->logActivity($user, 'profile_update', 'Profile information updated', $request);

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // UPDATED: password validation with stronger rules
        $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if (! Hash::check($request->current_password, $user->password_hash)) {
            $this->logActivity($user, 'password_change_failed', 'Failed password change attempt - wrong current password', $request);
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();

        $this->logActivity($user, 'password_changed', 'Password changed successfully', $request);

        return back()->with('success', 'Your password has been changed successfully.');
    }

    public function updateClinicSettings(Request $request)
    {
        $request->validate([
            'clinic_name' => 'nullable|string|max:255',
            'physician_name' => 'nullable|string|max:255',
        ]);

        $this->ensureSystemSettingsTableExists();

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'clinic_name'],
            ['value' => $this->sanitizeInput($request->clinic_name)]
        );

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'physician_name'],
            ['value' => $this->sanitizeInput($request->physician_name)]
        );

        $this->logActivity(Auth::user(), 'settings_updated', 'Clinic settings updated', $request);

        return back()->with('success', 'Clinic settings have been updated successfully.');
    }

    protected function loadSystemSettings(): array
    {
        if (! Schema::hasTable('system_settings')) {
            $this->ensureSystemSettingsTableExists();
            return [];
        }
        return DB::table('system_settings')->pluck('value', 'key')->all();
    }

    protected function ensureSystemSettingsTableExists(): void
    {
        if (Schema::hasTable('system_settings')) return;

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    protected function sanitizeInput(?string $input): ?string
    {
        if ($input === null) return null;
        $sanitized = strip_tags($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        $sanitized = preg_replace('/(<|>|script|iframe|javascript:|onerror|onload)/i', '', $sanitized);
        return trim($sanitized);
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