<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->student_employee_id = $request->student_employee_id;
        $user->save();

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Your password has been changed successfully.');
    }

    public function updateClinicSettings(Request $request)
    {
        $request->validate([
            'clinic_name' => 'nullable|string|max:255',
            'physician_name' => 'nullable|string|max:255',
            'theme_mode' => 'nullable|in:light,dark',
        ]);

        $this->ensureSystemSettingsTableExists();

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'clinic_name'],
            ['value' => $request->clinic_name]
        );

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'physician_name'],
            ['value' => $request->physician_name]
        );

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'theme_mode'],
            ['value' => $request->theme_mode]
        );

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
        if (Schema::hasTable('system_settings')) {
            return;
        }

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
}
