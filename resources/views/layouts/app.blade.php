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
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #2563eb;
            font-weight: 600;
        }
        #sidebar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { margin-left: -16rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 fixed h-full z-50">
            <div class="p-6">
                <div class="flex items-center gap-3 px-2 mb-10">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-plus-square text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-800">Rxcel<span class="text-indigo-600">.</span></span>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fas fa-th-large w-5"></i> Dashboard
                    </a>

                    <div class="pt-4 pb-2 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Clinic Services</div>
                    
                    <a href="{{ route('clinic.records.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.records.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fas fa-user-injured w-5"></i> Patient Records
                    </a>

                    <a href="{{ route('clinic.consultations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.consultations.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fas fa-stethoscope w-5"></i> Consultations
                    </a>

                    <a href="{{ route('clinic.medicines.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.medicines.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fas fa-pills w-5"></i> Inventory
                    </a>

                    <a href="{{ route('clinic.clearances.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.clearances.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fas fa-file-medical w-5"></i> Medical Clearance
                    </a>

                    <a href="{{ route('clinic.incidents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('clinic.incidents.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50 text-red-500' }}">
                        <i class="fas fa-ambulance w-5"></i> Health Incidents
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 transition-all duration-300">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 px-8 flex items-center justify-between">
                <button id="toggleBtn" class="p-2 hover:bg-slate-100 rounded-lg text-slate-500"><i class="fas fa-bars"></i></button>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div id="alert-success" class="mb-6 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500"></i>
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
        const toggleBtn = document.getElementById('toggleBtn');
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('sidebar-closed'));
    </script>
</body>
</html>