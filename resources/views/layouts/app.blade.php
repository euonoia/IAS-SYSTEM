<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel | Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
            border-left: 4px solid #3b82f6;
            color: #2563eb; /* blue-600 */
            font-weight: 600;
        }
        .sidebar-item:hover:not(.sidebar-item-active) {
            background: rgba(241, 245, 249, 0.5); /* slate-100 */
            color: #334155; /* slate-700 */
        }
        #sidebar {
            transition: margin 0.3s ease-in-out;
        }
        .sidebar-closed {
            margin-left: -16rem;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 z-20">
            <div class="p-6">
                <div class="flex items-center gap-3 text-blue-600">
                    <i class="fas fa-heart-pulse text-2xl"></i>
                    <span class="text-xl font-bold tracking-tight text-slate-800">Rxcel</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-item flex items-center gap-3 p-3 rounded-lg transition-all {{ request()->is('dashboard') ? 'sidebar-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>

                <a href="{{ route('clinic.records.index') }}" 
                   class="sidebar-item flex items-center gap-3 p-3 rounded-lg transition-all {{ request()->is('clinic/records*') ? 'sidebar-item-active' : 'text-slate-500' }}">
                    <i class="fas fa-user-injured w-5"></i> Patients
                </a>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">DR</div>
                    <div>
                        <p class="text-xs font-bold">Dr. Aris</p>
                        <p class="text-[10px] text-slate-400">Chief Surgeon</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button id="toggleBtn" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-700">
                        @if(request()->is('dashboard')) Clinic Overview 
                        @elseif(request()->is('clinic/records/create')) Add Patient Record
                        @else Patient Management @endif
                    </h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-400 hover:text-blue-500 transition-colors">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <span class="text-sm font-medium text-slate-600">{{ now()->format('D, M d, Y') }}</span>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-closed');
        });

        if (window.innerWidth < 768) {
            sidebar.classList.add('sidebar-closed');
        }
    </script>
</body>
</html>