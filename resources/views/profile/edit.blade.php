@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div x-data="{ 
        tab: 'profile', 
        preview: '{{ $user->avatar_url ?? '' }}',
        password: '',
        get strength() {
            let s = 0;
            if (this.password.length >= 8) s++;
            if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) s++;
            if (/[0-9]/.test(this.password)) s++;
            if (/[^A-Za-z0-9]/.test(this.password)) s++;
            return s;
        },
        generatePassword() {
            const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
            let retVal = '';
            for (let i = 0; i < 12; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            this.password = retVal;
            // Copy to confirmation manually if needed or just let user type it
            alert('Generated: ' + retVal + '\n\nPlease copy this to the confirmation field.');
        }
    }" class="space-y-8">
        
        <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white uppercase tracking-tight">Manage Account</h1>
                <p class="text-slate-400 mt-2">Update your profile, security, and clinic settings in one place.</p>
            </div>
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-3xl px-4 py-3 text-slate-300 backdrop-blur-md">
                <button :class="tab === 'profile' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-300'" @click="tab = 'profile'" class="rounded-full px-6 py-2 transition font-bold text-xs uppercase tracking-widest">Profile</button>
                <button :class="tab === 'settings' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-300'" @click="tab = 'settings'" class="rounded-full px-6 py-2 transition font-bold text-xs uppercase tracking-widest">System Settings</button>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl backdrop-blur-sm">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-user-circle text-blue-500"></i> Personal Information
                </h2>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" />
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" />
                        </div>
                        
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Profile Picture</label>
                        <div class="flex flex-wrap items-center gap-6 p-6 bg-slate-900/50 border border-white/5 rounded-[2rem]">
                            <div class="w-24 h-24 rounded-3xl overflow-hidden bg-slate-800 flex items-center justify-center border-2 border-white/10 shadow-inner">
                                <img :src="preview || '{{ asset('images/cropped.PNG') }}'" alt="Avatar Preview" :class="preview ? 'object-cover' : 'object-contain mix-blend-lighten'" class="w-full h-full" />
                            </div>
                            <div class="space-y-3">
                                <label class="inline-block cursor-pointer bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl transition font-bold text-sm shadow-lg shadow-blue-600/20">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload New Avatar
                                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])" />
                                </label>
                                <p class="text-slate-500 text-[10px] uppercase tracking-wider">Max 2MB. JPG, PNG, or WEBP only.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-3xl font-extrabold transition shadow-xl shadow-blue-600/20 uppercase tracking-widest text-xs">Save Profile Changes</button>
                    </div>
                </form>
            </div>

            <div class="p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl backdrop-blur-sm h-fit">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-shield-alt text-emerald-500"></i> Security
                </h2>
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <div class="relative">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 font-bold">New Password</label>
                            <button type="button" @click="generatePassword()" class="text-[9px] text-blue-400 hover:text-white font-bold uppercase tracking-tighter transition">Suggest Strong</button>
                        </div>
                        <input type="password" name="password" x-model="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex gap-1.5 h-1.5">
                                <div :class="strength >= 1 ? 'bg-red-500' : 'bg-white/10'" class="flex-1 rounded-full transition-all"></div>
                                <div :class="strength >= 2 ? 'bg-orange-500' : 'bg-white/10'" class="flex-1 rounded-full transition-all"></div>
                                <div :class="strength >= 3 ? 'bg-yellow-500' : 'bg-white/10'" class="flex-1 rounded-full transition-all"></div>
                                <div :class="strength >= 4 ? 'bg-emerald-500' : 'bg-white/10'" class="flex-1 rounded-full transition-all"></div>
                            </div>
                            
                            <ul class="grid grid-cols-2 gap-2 text-[9px] font-bold uppercase tracking-wider text-slate-500">
                                <li :class="password.length >= 8 ? 'text-emerald-400' : ''" class="flex items-center gap-1.5">
                                    <i class="fas" :class="password.length >= 8 ? 'fa-check-circle' : 'fa-circle'"></i> 8+ Char
                                </li>
                                <li :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'text-emerald-400' : ''" class="flex items-center gap-1.5">
                                    <i class="fas" :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'fa-check-circle' : 'fa-circle'"></i> Mixed Case
                                </li>
                                <li :class="/[0-9]/.test(password) ? 'text-emerald-400' : ''" class="flex items-center gap-1.5">
                                    <i class="fas" :class="/[0-9]/.test(password) ? 'fa-check-circle' : 'fa-circle'"></i> Numbers
                                </li>
                                <li :class="/[^A-Za-z0-9]/.test(password) ? 'text-emerald-400' : ''" class="flex items-center gap-1.5">
                                    <i class="fas" :class="/[^A-Za-z0-9]/.test(password) ? 'fa-check-circle' : 'fa-circle'"></i> Symbols
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <button type="submit" class="w-full bg-slate-100 hover:bg-white text-slate-900 px-8 py-4 rounded-2xl font-extrabold transition shadow-lg uppercase tracking-widest text-xs">Update Password</button>
                </form>
            </div>
        </div>

        <div x-show="tab === 'settings'" x-transition class="p-8 bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl backdrop-blur-sm">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <i class="fas fa-hospital-alt text-blue-400"></i> Clinic Settings
            </h2>
            <form action="{{ route('profile.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Clinic / School Name</label>
                        <input type="text" name="clinic_name" value="{{ old('clinic_name', $settings['clinic_name'] ?? 'Rxcel Memorial High School') }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-[0.35em] text-slate-400 mb-2 font-bold">Physician Name</label>
                        <input type="text" name="physician_name" value="{{ old('physician_name', $settings['physician_name'] ?? 'Dr. Aris Rodriguez, MD') }}" class="w-full bg-white/5 border border-white/10 rounded-3xl py-4 px-5 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-3xl font-extrabold transition shadow-xl shadow-blue-600/20 uppercase tracking-widest text-xs">Save Clinic Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom focus effects for a premium feel */
    input:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        transform: translateY(-1px);
    }
</style>
@endsection