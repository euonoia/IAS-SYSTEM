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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #ffffff;
            font-weight: 600;
        }
        #sidebar {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            background: linear-gradient(180deg, #0f172a 0%, #0c1a35 100%);
            border-right: 1px solid rgba(148, 163, 184, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.35);
        }
        .sidebar-closed {
            transform: translateX(-100%);
            opacity: 0;
            pointer-events: none;
        }
        .sidebar-collapsed {
            transform: translateX(-100%);
            width: 0 !important;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }
        .sidebar-fullscreen {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            transform: translateX(0);
            opacity: 1;
            pointer-events: auto;
        }
        #sidebar a {
            color: #cbd5e1;
        }
        #sidebar a:hover {
            background: rgba(148, 163, 184, 0.15);
            color: #ffffff;
        }
        #sidebar .sidebar-item-active {
            border-left-color: #60a5fa;
            color: #ffffff;
            background: rgba(59, 130, 246, 0.2);
        }
        .bg-white\/10 table th,
        .bg-white\/10 table td,
        .bg-white\/10 tbody tr {
            color: #e2e8f0;
        }
        .bg-white\/10 table th {
            color: #cbd5e1;
        }
        .bg-white\/10 tbody tr:not(:last-child) {
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .bg-white\/10 .text-slate-700,
        .bg-white\/10 .text-slate-800,
        .bg-white\/10 .text-slate-900 {
            color: #e2e8f0 !important;
        }
        .bg-white\/10 table thead tr,
        .bg-white\/10 table thead {
            background: rgba(255,255,255,0.12) !important;
        }
        .bg-white\/10 table tbody tr:hover {
            background: rgba(255,255,255,0.08) !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100">
    <div class="min-h-screen flex">
        <aside id="sidebar" class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-white/10 fixed h-full z-50">
            <div class="p-6">
                <div class="flex items-center gap-3 px-2 mb-10">
                    <div class="w-10 h-10 bg-blue-500/80 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/40">
                        <i class="fas fa-plus-square text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-white">Rxcel<span class="text-blue-400">.</span></span>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-th-large w-5"></i> Dashboard
                    </a>

                    <div class="pt-4 pb-2 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Clinic Services</div>
                    
                    <a href="{{ route('clinic.records.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.records.*') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-user-injured w-5"></i> Patient Records
                    </a>

                    <a href="{{ route('clinic.consultations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.consultations.*') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-stethoscope w-5"></i> Consultations
                    </a>

                    <a href="{{ route('clinic.medicines.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.medicines.*') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-pills w-5"></i> Inventory
                    </a>

                    <a href="{{ route('clinic.clearances.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.clearances.*') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-file-medical w-5"></i> Medical Clearance
                    </a>

                    <a href="{{ route('clinic.incidents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.incidents.*') ? 'sidebar-item-active' : 'text-slate-200 hover:bg-white/10' }}">
                        <i class="fas fa-ambulance w-5"></i> Health Incidents
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 transition-all duration-300 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
            <header class="h-20 bg-white/5 backdrop-blur-md border-b border-slate-700 sticky top-0 z-40 px-8 flex items-center justify-between">
                <button id="toggleBtn" class="p-2 hover:bg-slate-800/50 rounded-lg text-slate-400"><i class="fas fa-bars"></i></button>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div id="alert-success" class="mb-6 flex items-center justify-between p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-400"></i>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('main');
        const toggleBtn = document.getElementById('toggleBtn');

        function showSidebar() {
            sidebar.classList.remove('sidebar-closed');
            sidebar.classList.remove('sidebar-collapsed');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('sidebar-fullscreen');
                main.classList.remove('ml-0');
                main.classList.add('lg:ml-0');
            } else {
                sidebar.classList.remove('sidebar-fullscreen');
                main.classList.remove('ml-0');
                main.classList.add('lg:ml-64');
            }
        }

        function hideSidebar() {
            sidebar.classList.add('sidebar-closed');
            sidebar.classList.remove('sidebar-fullscreen');
            if (window.innerWidth < 1024) {
                main.classList.remove('lg:ml-64');
                main.classList.add('ml-0');
            } else {
                sidebar.classList.add('sidebar-collapsed');
                main.classList.remove('lg:ml-64');
                main.classList.add('ml-0');
            }
        }

        function toggleSidebar() {
            if (sidebar.classList.contains('sidebar-closed') || sidebar.classList.contains('sidebar-collapsed')) {
                showSidebar();
            } else {
                hideSidebar();
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('sidebar-fullscreen');
                sidebar.classList.remove('sidebar-closed');
                sidebar.classList.remove('sidebar-collapsed');
                main.classList.add('lg:ml-64');
                main.classList.remove('ml-0');
            } else {
                if (!sidebar.classList.contains('sidebar-fullscreen') && !sidebar.classList.contains('sidebar-closed')) {
                    sidebar.classList.add('sidebar-fullscreen');
                }
                main.classList.remove('lg:ml-64');
            }
        });

        // initialize:
        if (window.innerWidth < 1024) {
            hideSidebar();
        } else {
            showSidebar();
        }
    </script>
</body>
</html>