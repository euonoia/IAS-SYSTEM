@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Health Incident Reports</h2>
            <p class="text-sm text-slate-200 font-medium">Logs accidents, injuries, and emergencies</p>
        </div>
    
    <a href="{{ route('clinic.incidents.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
        <i class="fas fa-plus"></i> Record New Incident
    </a>
</div>

<div class="bg-white/10 rounded-2xl border border-white/20 shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-4">Date & Time</th>
                    <th class="px-6 py-4">Student Involved</th>
                    <th class="px-6 py-4">Incident Type</th>
                    <th class="px-6 py-4">Location</th>
                    <th class="px-6 py-4">Action Taken</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($incidents as $i)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-slate-600">
                        {{ \Carbon\Carbon::parse($i->incident_date)->format('M d, Y | h:i A') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-700">
                        {{ $i->student_medical_record->name }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-bold border border-red-100 uppercase">
                            {{ $i->incident_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $i->location }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold border border-blue-100 uppercase">
                            {{ $i->action_taken }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                         <form action="{{ route('clinic.incidents.destroy', $i->id) }}" method="POST" onsubmit="return confirm('Delete this incident report?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-400 hover:text-red-600 transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No incident reports recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table
    </div>
</div>
@endsection