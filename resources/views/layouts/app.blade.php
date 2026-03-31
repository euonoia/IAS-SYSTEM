<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel | Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #2563eb;
            font-weight: 600;
        }

        .sidebar-item:hover:not(.sidebar-item-active) {
            background: rgba(241, 245, 249, 0.5);
            color: #334155;
        }

        #sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-closed {
            margin-left: -16rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 z-20 shadow-sm">
            <div class="p-6">
                <div class="flex items-center gap-3 text-blue-600">
                    <div class="bg-blue-600 text-white p-2 rounded-xl shadow-lg shadow-blue-200">
                        <i class="fas fa-heart-pulse text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-800 uppercase">Rxcel</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 mt-4">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
                
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-item flex items-center gap-3 p-3 rounded-xl transition-all {{ request()->is('dashboard*') ? 'sidebar-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-th-large w-5 text-center"></i> 
                    <span class="text-sm">Dashboard</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-2">Clinic Modules</p>

                <a href="{{ route('clinic.records.index') }}" 
                   class="sidebar-item flex items-center gap-3 p-3 rounded-xl transition-all {{ request()->is('clinic/records*') ? 'sidebar-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-user-injured w-5 text-center"></i> 
                    <span class="text-sm">Patients Profile</span>
                </a>

                <a href="{{ route('clinic.consultations.index') }}" 
                   class="sidebar-item flex items-center gap-3 p-3 rounded-xl transition-all {{ request()->is('clinic/consultations*') ? 'sidebar-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-notes-medical w-5 text-center"></i> 
                    <span class="text-sm">Consultations</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold shadow-md shadow-blue-100">
                        AR
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-800 truncate">Dr. Aris</p>
                        <p class="text-[10px] text-slate-500 font-medium truncate">Clinic Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50/50">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <div class="flex items-center gap-6">
                    <button id="toggleBtn" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-all active:scale-95 border border-slate-100">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">
                            @if(request()->is('dashboard*')) 
                                Clinic Overview 
                            @elseif(request()->is('clinic/records/create')) 
                                Create Student Profile
                            @elseif(request()->is('clinic/records/*/edit')) 
                                Edit Medical Record
                            @elseif(request()->is('clinic/records*')) 
                                Patient Management
                            @elseif(request()->is('clinic/consultations/create')) 
                                New Patient Check-up
                            @elseif(request()->is('clinic/consultations*')) 
                                Consultation Records
                            @else 
                                Rxcel System
                            @endif
                        </h1>
                        <p class="text-[11px] text-slate-400 font-medium">Welcome back, Dr. Aris! System is running smoothly.</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-5">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-tight">Current Date</span>
                        <span class="text-sm font-semibold text-slate-700">{{ now()->format('l, M d, Y') }}</span>
                    </div>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition-all relative border border-slate-100 bg-white">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <div class="p-8 max-w-[1600px] mx-auto">
                {{-- Global Success Alert --}}
                @if(session('success'))
                    <div id="alert-success" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm shadow-emerald-50 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="text-sm font-bold tracking-tight">{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-success').remove()" class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-300 hover:text-emerald-600 hover:bg-emerald-100 transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');

        // Toggle Sidebar function
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-closed');
        });

        // Responsive handling
        const checkWidth = () => {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('sidebar-closed');
            } else {
                sidebar.classList.remove('sidebar-closed');
            }
        };

        window.addEventListener('resize', checkWidth);
        window.addEventListener('load', checkWidth);

        // Auto-hide success alert
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);
    </script>
</body>
</html>