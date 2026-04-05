<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel | Clinical Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0a0f1d; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-flex w-16 h-16 rounded-2xl overflow-hidden shadow-2xl shadow-blue-500/40 mb-4">
                <img src="{{ asset('images/cropped.PNG') }}" alt="Rxcel Logo" class="w-full h-full object-cover">
            </div>
            <h1 class="text-3xl font-extrabold text-white italic">Rx<span class="text-blue-500">cel</span></h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Clinic Management System</p>
        </div>

        <div class="glass p-8 rounded-[2.5rem] shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-2 text-center">Authorized Access</h2>
            <p class="text-slate-400 text-xs text-center mb-8 uppercase tracking-[0.2em] font-bold">Please enter your credentials</p>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold rounded-xl text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email or ID</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="text" name="login" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                            placeholder="admin@example.com or 123456">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="password" name="password" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-blue-600/20 transition-all active:scale-[0.98] mt-4 uppercase tracking-widest text-sm">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-slate-600 text-[10px] mt-10 font-bold uppercase tracking-[0.3em]">
            &copy; 2026 Rxcel Medical Systems
        </p>
    </div>
</body>
</html>