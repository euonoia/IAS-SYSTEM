@extends('layouts.app')

@section('content')
{{-- TOP STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass p-6 rounded-2xl shadow-sm border border-blue-50">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold mt-1 text-slate-800">{{ $stats['total_patients'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <p class="text-xs text-emerald-500 mt-4 font-medium"><i class="fas fa-sync-alt animate-spin-slow"></i> Live Sync Active</p>
    </div>

    <div class="glass p-6 rounded-2xl shadow-sm border border-blue-50">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">Today's List</p>
                <h3 class="text-2xl font-bold mt-1 text-slate-800">{{ $stats['today_appointments'] }}</h3>
            </div>
            <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4 font-medium">Check-ups Today</p>
    </div>

    <div class="glass p-6 rounded-2xl shadow-sm border border-blue-50">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">Medicine Stocks</p>
                <h3 class="text-2xl font-bold mt-1 text-slate-800">{{ $stats['medicine_stocks'] }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-pills"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4 font-medium italic">Total units available</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- LIVE QUEUE (Left Side) --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-slate-800">Live Consultation Queue</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Logs for {{ now()->format('M d, Y') }}</p>
            </div>
            <a href="{{ route('clinic.consultations.index') }}" class="text-xs text-blue-600 hover:underline font-bold text-nowrap">History</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/50 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Student ID</th>
                        <th class="px-6 py-4">Diagnosis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($todayConsultations as $cons)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-bold text-blue-600">{{ $cons->created_at->format('h:i A') }}</td>
                        <td class="px-6 py-4 font-medium">{{ optional($cons->student_medical_record)->student_id ?? 'N/A' }}</td>
                        <td class="px-6 py-4 italic">{{ Str::limit($cons->diagnosis, 40) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400 italic font-medium text-nowrap">No consultations today</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STOCK MONITOR (Right Side) --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <div>
                <h2 class="font-bold text-slate-800 text-sm">Stock Monitor</h2>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Inventory Alert</p>
            </div>
            <a href="{{ route('clinic.medicines.index') }}" class="text-xs text-indigo-600 hover:underline font-bold text-nowrap">Inventory</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[9px]">Medicine</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-wider text-[9px] text-right text-nowrap">Current Stocks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lowStockMedicines as $med)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $med->name }}</td>
                        <td class="px-4 py-4 text-right">
                            <span class="px-2 py-1 {{ $med->stock_quantity <= $med->low_stock_threshold ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-50 text-slate-600 border border-slate-100' }} rounded-lg font-bold">
                                {{ $med->stock_quantity }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-8 text-center text-slate-400 italic">Stocks are healthy</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODULE 1: NEW PATIENT PROFILES (FULL WIDTH - OUTSIDE GRID) --}}
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">New Patient Profiles</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Review the latest student health records added to the system.</p>
        </div>
        <a href="{{ route('clinic.records.index') }}" class="bg-white border border-slate-200 hover:border-blue-400 hover:text-blue-600 text-slate-600 px-5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
            View All Records
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/80 text-slate-500 text-[10px] uppercase tracking-[0.15em] font-black border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5">Student ID</th>
                    <th class="px-8 py-5 text-center">Blood Type</th>
                    <th class="px-8 py-5">Allergies</th>
                    <th class="px-8 py-5">Chronic Illness</th>
                    <th class="px-8 py-5 text-right">Added On</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentRecords as $record)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xs font-bold border border-blue-100">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $record->student_id }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-3 py-1.5 bg-red-50 text-red-600 rounded-full text-[10px] font-extrabold border border-red-100 shadow-sm">
                            {{ $record->blood_type ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm">
                        <span class="{{ $record->allergies ? 'text-red-500 font-medium' : 'text-slate-400 italic text-xs' }}">
                            {{ $record->allergies ? Str::limit($record->allergies, 50) : 'No reported allergies' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-600 font-medium">
                        {{ $record->chronic_illness ? Str::limit($record->chronic_illness, 50) : 'None reported' }}
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-400 text-right font-medium">
                        {{ $record->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 text-sm italic">
                        No recent patient profiles found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection