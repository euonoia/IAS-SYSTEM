@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="glass p-6 rounded-2xl shadow-sm border border-blue-50">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold mt-1">{{ $stats['total_patients'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <p class="text-xs text-emerald-500 mt-4 font-medium"><i class="fas fa-arrow-up"></i> Live Sync</p>
    </div>

    <div class="glass p-6 rounded-2xl shadow-sm border border-blue-50">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">Today's List</p>
                <h3 class="text-2xl font-bold mt-1">{{ $stats['today_appointments'] }}</h3>
            </div>
            <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4 font-medium">Scheduled Today</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">Live Queue</h2>
    </div>
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Time</th>
                <th class="px-6 py-4">Patient</th>
                <th class="px-6 py-4">Service</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($recentAppointments as $apt)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $apt->time }}</td>
                <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $apt->patient_name }}</td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $apt->service }}</td>
                <td class="px-6 py-4">
                    @if($apt->status == 'waiting')
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase">Waiting</span>
                    @elseif($apt->status == 'in-progress')
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase">In Room</span>
                    @else
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Done</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <button class="text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm italic">
                    No appointments available yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h2 class="font-bold text-slate-800">Recent Medical Records</h2>
        <a href="#" class="text-sm text-blue-600 hover:underline font-medium">View All Records</a>
    </div>
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Student ID</th>
                <th class="px-6 py-4">Blood Type</th>
                <th class="px-6 py-4">Allergies</th>
                <th class="px-6 py-4">Chronic Illness</th>
                <th class="px-6 py-4 text-right">Created At</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($recentRecords as $record)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $record->student_id }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 text-center">{{ $record->blood_type ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-red-500">{{ $record->allergies ?? 'None' }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $record->chronic_illness ?? 'None' }}</td>
                <td class="px-6 py-4 text-sm text-slate-400 text-right">{{ $record->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm italic">
                    No medical records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection