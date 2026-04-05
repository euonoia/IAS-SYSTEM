<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel | OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #090e1d; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-flex w-16 h-16 rounded-2xl overflow-hidden shadow-2xl shadow-blue-500/40 mb-4 bg-transparent">
                <img src="{{ asset('images/cropped.PNG') }}" alt="Rxcel Logo" class="w-full h-full object-contain mix-blend-lighten">
            </div>
            <h1 class="text-3xl font-extrabold text-white italic">Rx<span class="text-blue-500">cel</span></h1>
            <p class="text-slate-400 text-sm mt-2 font-medium">Secure One-Time Passcode</p>
        </div>

        <div class="glass p-8 rounded-[2.5rem] shadow-2xl">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                    <p class="text-emerald-300 text-sm text-center">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                    <p class="text-red-300 text-sm text-center">{{ session('error') }}</p>
                </div>
            @endif

            <p class="text-slate-400 text-xs text-center mb-8 uppercase tracking-[0.2em] font-bold">Enter the 6-digit code sent to your email</p>

            <form action="{{ route('otp.verify') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">One-Time Passcode</label>
                    <input type="text" name="otp_code" maxlength="6" required inputmode="numeric" pattern="[0-9]*"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white text-center tracking-[0.4em] text-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                        placeholder="000000">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-blue-600/20 transition-all active:scale-[0.98] uppercase tracking-widest text-sm">
                    Verify Code
                </button>
            </form>

            <div x-data="{ seconds: 60, started: true, tick() { if (this.seconds > 0) { setTimeout(() => { this.seconds--; this.tick(); }, 1000); } } }" x-init="tick()" class="mt-6 text-center">
                <form action="{{ route('otp.resend') }}" method="POST" class="space-y-3">
                    @csrf
                    <button type="submit" class="w-full bg-slate-800/80 hover:bg-slate-700 text-white font-bold py-4 rounded-2xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="seconds > 0">
                        <span x-show="seconds === 0">Resend Code</span>
                        <span x-show="seconds > 0">Resend available in <span x-text="seconds"></span>s</span>
                    </button>
                </form>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center mt-4 text-sm font-semibold text-slate-200 hover:text-white hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i>Back to login
                </a>
            </div>
        </div>
        <p class="text-center text-slate-600 text-[10px] mt-10 font-bold uppercase tracking-[0.3em]">&copy; 2026 Rxcel Medical Systems</p>
    </div>
</body>
</html>