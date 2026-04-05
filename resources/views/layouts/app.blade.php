<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rxcel | Clinic Management</title>
    <link rel="icon" type="image/png" href="{{ asset('images/cropped.PNG') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0a0f1d; }
        
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #ffffff !important;
            font-weight: 600;
        }

        #sidebar {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            background: linear-gradient(180deg, #0f172a 0%, #0c1a35 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Custom Scrollbar for Dropdowns */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.5); border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 overflow-x-hidden">

    <div class="flex min-h-screen">
        
        <aside id="sidebar" class="w-64 fixed inset-y-0 left-0 z-50 flex flex-col shadow-2xl transition-transform duration-300">
            <div class="p-8 flex flex-col h-full">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg shadow-blue-500/40 bg-transparent">
                        <img src="{{ asset('images/cropped.PNG') }}" alt="Rxcel Logo" class="w-10 h-10 object-contain mix-blend-lighten">
                    </div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white italic">Rxcel</h1>
                </div>

                <nav class="space-y-1 flex-1 overflow-y-auto custom-scroll">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-th-large w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('clinic.records.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('clinic.records.*') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-user-injured w-5"></i> Patient Records
                    </a>
                    <a href="{{ route('clinic.consultations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('clinic.consultations.*') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-stethoscope w-5"></i> Consultations
                    </a>
                    <a href="{{ route('clinic.medicines.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('clinic.medicines.*') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-pills w-5"></i> Inventory
                    </a>
                    <a href="{{ route('clinic.clearances.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('clinic.clearances.*') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-file-medical w-5"></i> Clearances
                    </a>
                    <a href="{{ route('clinic.incidents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('clinic.incidents.*') ? 'sidebar-item-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-ambulance w-5"></i> Incidents
                    </a>
                </nav>

                <div class="mt-auto pt-6 border-t border-white/10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-bold group">
                            <i class="fas fa-sign-out-alt w-5 group-hover:-translate-x-1 transition-transform"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main id="mainContent" class="flex-1 lg:ml-64 transition-all duration-300 min-h-screen">
            
            <header class="h-20 glass-header sticky top-0 z-40 px-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="toggleBtn" class="p-2.5 hover:bg-white/10 rounded-xl text-slate-400 transition-all">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">System Health</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-xs font-bold text-emerald-400/80">Online</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-6">
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-slate-400 transition-all active:scale-95">
                            <i class="far fa-bell text-xl"></i>
                            <span class="absolute top-2 right-2.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0f172a] animate-pulse"></span>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="absolute right-0 mt-3 w-80 bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden" style="display: none;">
                            <div class="p-4 border-b border-white/10 flex justify-between items-center bg-white/5">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-white">Notifications</h3>
                                <span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-bold">New Alerts</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto custom-scroll">
                                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center text-red-500 flex-shrink-0">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white leading-tight">Low Stock Alert</p>
                                            <p class="text-[11px] text-slate-400 mt-1">Paracetamol 500mg is below 10 units.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-500 flex-shrink-0">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white leading-tight">Consultation Today</p>
                                            <p class="text-[11px] text-slate-400 mt-1">You have 5 pending consultations to check.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="block p-3 text-center text-[10px] font-bold text-slate-500 hover:text-white uppercase tracking-widest bg-white/5 transition-all">View All Activity</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 pl-4 border-l border-white/10 group active:scale-95 transition-all">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-white leading-none">{{ Auth::user()->full_name ?? 'Admin' }}</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter mt-1 group-hover:text-blue-400 transition-colors">Manage  Account <i class="fas fa-chevron-down ml-1"></i></p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-[2px] shadow-lg shadow-blue-500/20">
                                <div class="w-full h-full rounded-[10px] bg-[#0f172a] flex items-center justify-center overflow-hidden">
                                    <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name ?? 'Admin') . '&background=0D8ABC&color=fff' }}" alt="Profile">
                                </div>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-56 bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl z-50 p-2" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                                <i class="far fa-user-circle w-5"></i> My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                                <i class="fas fa-cog w-5"></i> Settings
                            </a>
                            <div class="h-px bg-white/10 my-2 mx-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-400 hover:bg-red-500/10 transition-all font-bold">
                                    <i class="fas fa-sign-out-alt w-5"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div id="alert-success" class="mb-8 flex items-center justify-between p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl backdrop-blur-md">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle"></i>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleBtn');

        // Sidebar logic
        function toggleSidebar() {
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('-translate-x-full');
            } else {
                sidebar.classList.toggle('-translate-x-full');
                main.classList.toggle('lg:ml-64');
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);

        // Auto-hide alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);

        // Resize check
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                main.classList.add('lg:ml-64');
            } else {
                sidebar.classList.add('-translate-x-full');
                main.classList.remove('lg:ml-64');
            }
        });

        // Initialize for mobile
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
            main.classList.remove('lg:ml-64');
        }
    </script>
</body>
</html>