@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div x-data="{ tab: 'profile', preview: '{{ $user->avatar_url ?? '' }}' }" class="space-y-8">
        <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Manage Account</h1>
                <p class="text-slate-400 mt-2">Update your profile, security, and clinic settings in one place.</p>
            </div>
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-3xl px-4 py-3 text-slate-300">
                <button :class="tab === 'profile' ? 'bg-blue-600 text-white' : 'text-slate-300'" @click="tab = 'profile'" class="rounded-full px-4 py-2 transition">Profile</button>
                <button :class="tab === 'settings' ? 'bg-blue-600 text-white' : 'text-slate-300'" @click="tab = 'settings'" class="rounded-full px-4 py-2 transition">System Settings</button>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl">
                <h2 class="text-xl font-bold text-white mb-6">Personal Information</h2>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Student / Employee ID</label>
                            <input type="text" name="student_employee_id" value="{{ old('student_employee_id', $user->student_employee_id) }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2 items-end">
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Profile Picture</label>
                            <div class="flex items-center gap-4 p-4 bg-slate-900/80 border border-white/10 rounded-3xl">
                                <div class="w-24 h-24 rounded-3xl overflow-hidden bg-slate-800 flex items-center justify-center">
                                    <img :src="preview || '{{ asset('images/cropped.PNG') }}'" alt="Avatar Preview" :class="preview ? 'object-cover' : 'object-contain mix-blend-lighten'" class="w-full h-full" />
                                </div>
                                <label class="cursor-pointer bg-blue-600 hover:bg-blue-500 text-white px-4 py-3 rounded-3xl transition">
                                    Upload Avatar
                                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])" />
                                </label>
                            </div>
                        </div>
                        <div class="text-slate-400 text-xs">
                            Max file size: 2MB. JPG, PNG, or WEBP only.
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-3xl font-bold transition">Save Profile</button>
                    </div>
                </form>
            </div>

            <div class="p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl">
                <h2 class="text-xl font-bold text-white mb-6">Security</h2>
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Current Password</label>
                        <input type="password" name="current_password" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">New Password</label>
                        <input type="password" name="password" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <button type="submit" class="w-full bg-slate-800/80 hover:bg-slate-700 text-white px-8 py-4 rounded-3xl font-bold transition">Update Password</button>
                </form>
            </div>
        </div>

        <div x-show="tab === 'settings'" class="p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Clinic Settings</h2>
            <form action="{{ route('profile.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Clinic / School Name</label>
                        <input type="text" name="clinic_name" value="{{ old('clinic_name', $settings['clinic_name'] ?? 'Rxcel Memorial High School') }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2">Physician Name</label>
                        <input type="text" name="physician_name" value="{{ old('physician_name', $settings['physician_name'] ?? 'Dr. Aris Rodriguez, MD') }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                

                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-3xl font-bold transition">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
