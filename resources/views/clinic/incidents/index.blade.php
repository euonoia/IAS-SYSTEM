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

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Type & Description</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Action/First Aid</th>
                        <th class="px-6 py-4">Reported By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($incidents as $i)
                    <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                        {{-- Date & Time --}}
                        <td class="px-6 py-4 text-sm font-medium text-slate-200">
                            {{ \Carbon\Carbon::parse($i->incident_date)->format('M d, Y') }}
                            <div class="text-[10px] text-slate-400 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($i->incident_date)->format('h:i A') }}
                            </div>
                        </td>

                        {{-- Student Name --}}
                        <td class="px-6 py-4 font-bold text-slate-100">
                            {{ $i->student_medical_record->name ?? 'N/A' }}
                        </td>

                        {{-- Type & Description --}}
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-red-100/10 text-red-300 rounded text-[10px] font-bold border border-red-700/10 uppercase block w-fit mb-1">
                                {{ $i->incident_type }}
                            </span>
                            <p class="text-xs text-slate-400 line-clamp-2 max-w-xs">
                                {{ $i->description }}
                            </p>
                        </td>

                        {{-- Location --}}
                        <td class="px-6 py-4 text-sm text-slate-300">
                            <i class="fas fa-map-marker-alt text-slate-500 mr-1"></i> {{ $i->location }}
                        </td>

                        {{-- Action Taken & First Aid --}}
                        <td class="px-6 py-4">
                            <div class="text-xs text-blue-300 font-semibold mb-1">
                                <i class="fas fa-kit-medical mr-1"></i> {{ $i->first_aid_given }}
                            </div>
                            <span class="text-[10px] text-slate-400 italic">
                                Result: {{ $i->action_taken ?? 'No action specified' }}
                            </span>
                        </td>

                        {{-- Reported By --}}
                        <td class="px-6 py-4 text-sm text-slate-300 font-medium">
                            {{ $i->reported_by }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">No incident reports recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection