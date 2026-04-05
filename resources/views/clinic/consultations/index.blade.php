@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Consultation History</h1>
            <p class="text-slate-200 font-medium text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                View and manage student check-up records
            </p>
        </div>
                <a href="{{ route('clinic.consultations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all duration-200 shadow-sm flex items-center gap-2 active:scale-95">
                    <i class="fas fa-plus"></i>
                    <span>New Consultation</span>
                </a>
            </div>
        </div>

        <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-900/90 text-slate-100">
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Date & Time</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Student ID</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest">Diagnosis</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-center">Medicines</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($consultations as $item)
                        <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-100">{{ $item->created_at->format('M d, Y') }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $item->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-blue-300">
                                {{ $item->student_medical_record->student_id }}
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-300 font-medium">
                                {{ Str::limit($item->diagnosis, 50) }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($item->medicines_used)
                                    <span class="px-3 py-1 bg-blue-100/10 text-blue-300 text-[10px] font-bold rounded-full border border-blue-700/10">
                                        <i class="fas fa-pills mr-1"></i> {{ $item->medicines_used }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs italic">None</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('clinic.consultations.show', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-300 hover:bg-blue-900/40 rounded-xl transition-all" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('clinic.consultations.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-900/40 rounded-xl transition-all">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-notes-medical text-5xl mb-4 opacity-20 text-slate-900"></i>
                                    <p class="italic text-sm">No consultation records found yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection