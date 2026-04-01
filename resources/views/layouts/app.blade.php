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

        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #2563eb;
            font-weight: 600;
        }

        #sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-closed {
            margin-left: -16rem;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 z-20 shadow-sm">
            <div class="p-6">
                <div class="flex items-center gap-3 text-blue-600">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas fa-heart-pulse text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-800">Rxcel</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 mt-4 overflow-y-auto custom-scrollbar">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                    <i class="fas fa-th-large w-5 text-center"></i> 
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('clinic.records.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ request()->is('clinic/records*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                    <i class="fas fa-user-injured w-5 text-center"></i> 
                    <span class="text-sm">Patients</span>
                </a>

                <a href="{{ route('clinic.consultations.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ request()->is('clinic/consultations*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                    <i class="fas fa-notes-medical w-5 text-center"></i> 
                    <span class="text-sm">Consultations</span>
                </a>

                <a href="{{ route('clinic.medicines.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ request()->is('clinic/medicines*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                    <i class="fas fa-pills w-5 text-center"></i> 
                    <span class="text-sm">Medicines</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 p-2 rounded-xl">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-md">DA</div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-800 truncate">Dr. Aris</p>
                        <p class="text-[10px] text-slate-500 truncate">School Physician</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10 sticky top-0">
                <div class="flex items-center gap-4">
                    <button id="toggleBtn" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-all active:scale-90">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    
                    <h1 class="text-md font-bold text-slate-800">
                        @if(request()->is('dashboard*')) Dashboard Overview
                        @elseif(request()->is('clinic/records*')) Patient Management
                        @elseif(request()->is('clinic/consultations/create')) New Consultation
                        @elseif(request()->is('clinic/consultations*')) Consultation History
                        @elseif(request()->is('clinic/medicines*')) Medicine Inventory
                        @else Clinic System
                        @endif
                    </h1>
                </div>
                
                <div class="flex items-center gap-5">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Today's Date</span>
                        <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <button class="relative w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                @if(session('success'))
                    <div id="alert-success" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm shadow-emerald-100 animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-success').style.display='none'" class="text-emerald-400 hover:text-emerald-600">
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

        // Sidebar Toggle
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-closed');
        });

        // Mobile Responsiveness
        const handleResize = () => {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('sidebar-closed');
            } else {
                sidebar.classList.remove('sidebar-closed');
            }
        };

        window.addEventListener('resize', handleResize);
        window.addEventListener('load', handleResize);

        // Success Alert Auto-hide
        setTimeout(() => {
            const alert = document.getElementById('alert-success');
            if(alert) {
                alert.classList.add('animate-out', 'fade-out', 'slide-out-to-top-4');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>