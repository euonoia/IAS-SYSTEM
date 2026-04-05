@extends('layouts.app')

@section('content')
{{-- TOP STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-2xl border border-white/20 hover:border-blue-400/50 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-300 font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold mt-1 text-white">{{ $stats['total_patients'] }}</h3>
            </div>
            <div class="p-3 bg-blue-500/20 text-blue-400 rounded-xl border border-blue-400/30">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <p class="text-xs text-emerald-400 mt-4 font-medium"><i class="fas fa-sync-alt animate-spin-slow"></i> Live Sync Active</p>
    </div>

    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-2xl border border-white/20 hover:border-teal-400/50 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-300 font-medium">Today's List</p>
                <h3 class="text-2xl font-bold mt-1 text-white">{{ $stats['today_appointments'] }}</h3>
            </div>
            <div class="p-3 bg-teal-500/20 text-teal-400 rounded-xl border border-teal-400/30">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4 font-medium">Check-ups Today</p>
    </div>

    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-2xl border border-white/20 hover:border-purple-400/50 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-300 font-medium">Medicine Stocks</p>
                <h3 class="text-2xl font-bold mt-1 text-white">{{ $stats['medicine_stocks'] }}</h3>
            </div>
            <div class="p-3 bg-purple-500/20 text-purple-400 rounded-xl border border-purple-400/30">
                <i class="fas fa-pills"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4 font-medium italic">Total units available</p>
    </div>
</div>

{{-- MODULE 1: NEW PATIENT PROFILES (FULL WIDTH - OUTSIDE GRID) --}}
<div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
    <div class="p-8 border-b border-white/10 flex justify-between items-center bg-gradient-to-r from-white/5 to-transparent">
        <div>
            <h2 class="text-xl font-extrabold text-white tracking-tight">New Patient Profiles</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Review the latest student health records added to the system.</p>
        </div>
        <a href="{{ route('clinic.records.index') }}" class="bg-white/10 border border-white/20 hover:border-blue-400/50 hover:text-blue-400 text-slate-300 px-5 py-2 rounded-xl text-xs font-bold transition-all backdrop-blur-sm">
            View All Records
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 text-slate-300 text-[10px] uppercase tracking-[0.15em] font-black border-b border-white/10">
                <tr>
                    <th class="px-8 py-5">Student ID / Name</th>
                    <th class="px-8 py-5 text-center">Blood Type</th>
                    <th class="px-8 py-5">Allergies</th>
                    <th class="px-8 py-5">Chronic Illness</th>
                    <th class="px-8 py-5 text-right">Added On</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($recentRecords as $record)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center text-xs font-bold border border-blue-400/30">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <span class="text-sm text-slate-400">{{ $record->student_id }}</span>
                                <div class="text-white font-bold text-sm group-hover:text-blue-400 transition-colors">{{ $record->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-3 py-1.5 bg-red-500/20 text-red-400 rounded-full text-[10px] font-extrabold border border-red-400/30 shadow-sm">
                            {{ $record->blood_type ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm">
                        <span class="{{ $record->allergies ? 'text-red-400 font-medium' : 'text-slate-500 italic text-xs' }}">
                            {{ $record->allergies ? Str::limit($record->allergies, 50) : 'No reported allergies' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-400 font-medium">
                        {{ $record->chronic_illness ? Str::limit($record->chronic_illness, 50) : 'None reported' }}
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-500 text-right font-medium">
                        {{ $record->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-slate-500 text-sm italic">
                        No recent patient profiles found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- LIVE QUEUE (Left Side) --}}
    <div class="lg:col-span-2 bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-white">Recent Consultations</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Latest records from all consultations</p>
            </div>
            <a href="{{ route('clinic.consultations.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold text-nowrap">History</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-white/5 text-slate-300 uppercase font-bold border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Student ID / Name</th>
                        <th class="px-6 py-4">Diagnosis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse($todayConsultations as $cons)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-blue-400">{{ $cons->created_at->format('h:i A') }}</td>
                        <td class="px-6 py-4">
                            @if($cons->student_medical_record)
                                <span class="text-slate-400 text-[11px]">{{ $cons->student_medical_record->student_id }}</span>
                                <div class="text-orange-400 font-bold">{{ $cons->student_medical_record->name ?? 'N/A' }}</div>
                            @else
                                <span class="text-slate-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 italic text-slate-400">{{ Str::limit($cons->diagnosis ?? 'No diagnosis', 40) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500 italic font-medium text-nowrap">No consultations today</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STOCK MONITOR (Right Side) --}}
    <div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-gradient-to-r from-white/5 to-transparent">
            <div>
                <h2 class="font-bold text-white text-sm">Stock Monitor</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Inventory Alert</p>
            </div>
            <a href="{{ route('clinic.medicines.index') }}" class="text-xs text-purple-400 hover:text-purple-300 font-bold text-nowrap">Inventory</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-4 py-3 font-bold text-slate-300 uppercase tracking-wider text-[9px]">Medicine</th>
                        <th class="px-4 py-3 font-bold text-slate-300 uppercase tracking-wider text-[9px] text-right text-nowrap">Current Stocks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($lowStockMedicines as $med)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-4 font-semibold text-slate-300">{{ $med->name }}</td>
                        <td class="px-4 py-4 text-right">
                            <span class="px-2 py-1 {{ $med->stock_quantity <= $med->low_stock_threshold ? 'bg-red-500/20 text-red-400 border border-red-400/30' : 'bg-white/10 text-slate-300 border border-white/20' }} rounded-lg font-bold">
                                {{ $med->stock_quantity }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-8 text-center text-slate-500 italic">Stocks are healthy</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODULE 4 & 5: MEDICAL CLEARANCE AND HEALTH INCIDENTS GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- MEDICAL CLEARANCE --}}
    <div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-gradient-to-r from-orange-500/10 to-transparent">
            <div>
                <h2 class="font-bold text-white text-lg">Medical Clearances</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Pending: <span class="text-orange-400 font-bold">{{ $stats['pending_clearances'] }}</span></p>
            </div>
            <div class="p-3 bg-orange-500/20 text-orange-400 rounded-xl border border-orange-400/30">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-white/5 text-slate-300 uppercase font-bold border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4">Student ID / Name</th>
                        <th class="px-6 py-4">Purpose</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse($medicalClearances as $clearance)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            @if($clearance->student_medical_record)
                                <div>
                                    <span class="text-slate-400 text-[11px]">{{ $clearance->student_medical_record->student_id }}</span>
                                    <div class="font-semibold text-orange-400">{{ $clearance->student_medical_record->name ?? 'N/A' }}</div>
                                </div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ Str::limit($clearance->purpose ?? 'N/A', 30) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold
                                @if($clearance->status === 'approved') bg-emerald-500/20 text-emerald-400 border border-emerald-400/30
                                @elseif($clearance->status === 'pending') bg-amber-500/20 text-amber-400 border border-amber-400/30
                                @else bg-red-500/20 text-red-400 border border-red-400/30
                                @endif
                            ">
                                {{ ucfirst($clearance->status ?? 'unknown') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">No clearances found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-white/5 border-t border-white/10">
            <a href="{{ route('clinic.clearances.index') }}" class="text-xs text-orange-400 hover:text-orange-300 font-bold">View All Clearances →</a>
        </div>
    </div>

    {{-- HEALTH INCIDENTS --}}
    <div class="bg-white/10 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-gradient-to-r from-red-500/10 to-transparent">
            <div>
                <h2 class="font-bold text-white text-lg">Health Incidents</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">This Week: <span class="text-red-400 font-bold">{{ $stats['recent_incidents'] }}</span></p>
            </div>
            <div class="p-3 bg-red-500/20 text-red-400 rounded-xl border border-red-400/30">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-white/5 text-slate-300 uppercase font-bold border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4">Student ID / Name</th>
                        <th class="px-6 py-4">Incident Type</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse($healthIncidents as $incident)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            @if($incident->student_medical_record)
                                <div>
                                    <span class="text-slate-400 text-[11px]">{{ $incident->student_medical_record->student_id }}</span>
                                    <div class="font-semibold text-red-400">{{ $incident->student_medical_record->name ?? 'N/A' }}</div>
                                </div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-400">{{ Str::limit($incident->incident_type ?? 'N/A', 25) }}</td>
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500 italic">No incidents recorded</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-white/5 border-t border-white/10">
            <a href="{{ route('clinic.incidents.index') }}" class="text-xs text-red-400 hover:text-red-300 font-bold">View All Incidents →</a>
        </div>
    </div>
</div>

@endsection