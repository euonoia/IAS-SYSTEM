@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
</div>

{{-- MODULE 2: LIVE QUEUE (CONSULTATIONS TODAY) --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-slate-800">Live Consultation Queue</h2>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Real-time check-up logs for {{ now()->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('clinic.consultations.index') }}" class="text-xs text-blue-600 hover:underline font-bold">View History</a>
    </div>
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Time</th>
                <th class="px-6 py-4">Student ID</th>
                <th class="px-6 py-4">Diagnosis</th>
                <th class="px-6 py-4">Medicines</th>
                <th class="px-6 py-4 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($todayConsultations as $cons)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $cons->created_at->format('h:i A') }}</td>
                <td class="px-6 py-4 text-sm text-slate-700 font-medium">
                    {{ optional($cons->student_medical_record)->student_id ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-500 italic">
                    {{ Str::limit($cons->diagnosis, 30) }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-lg border border-blue-100">
                        {{ $cons->medicines_used ?? 'No Medicine' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('clinic.consultations.show', $cons->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-clock text-3xl mb-3 opacity-20"></i>
                        <p class="text-sm italic">No consultations recorded today.</p>
                        <a href="{{ route('clinic.consultations.create') }}" class="mt-2 text-xs text-blue-600 font-bold hover:underline">+ New Consultation</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODULE 1: RECENT MEDICAL RECORDS --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-slate-800">New Patient Profiles</h2>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Latest student health records added</p>
        </div>
        <a href="{{ route('clinic.records.index') }}" class="text-xs text-blue-600 hover:underline font-bold">Manage Patients</a>
    </div>
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Student ID</th>
                <th class="px-6 py-4 text-center">Blood Type</th>
                <th class="px-6 py-4">Allergies</th>
                <th class="px-6 py-4">Chronic Illness</th>
                <th class="px-6 py-4 text-right">Added On</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($recentRecords as $record)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $record->student_id }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 text-center font-bold">
                    <span class="px-2 py-1 bg-red-50 text-red-600 rounded-md">{{ $record->blood_type ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-red-500">{{ Str::limit($record->allergies ?? 'None', 20) }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($record->chronic_illness ?? 'None', 20) }}</td>
                <td class="px-6 py-4 text-sm text-slate-400 text-right">{{ $record->created_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm italic">
                    No medical records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection