@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 border-b border-slate-100 pb-6">
            <div class="flex justify-between items-center text-nowrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Consultation History</h1>
                    <p class="text-slate-500 font-medium text-sm flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        Module 2: View and manage student check-up records
                    </p>
                </div>
                <a href="{{ route('clinic.consultations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold transition-all duration-200 shadow-lg shadow-blue-200 flex items-center gap-2 active:scale-95">
                    <i class="fas fa-plus"></i>
                    <span>New Consultation</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Date & Time</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Student ID</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Diagnosis</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Medicines</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($consultations as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $item->created_at->format('M d, Y') }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $item->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-blue-600">
                                {{ $item->student_medical_record->student_id }}
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-medium">
                                {{ Str::limit($item->diagnosis, 50) }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($item->medicines_used)
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full border border-blue-100">
                                        <i class="fas fa-pills mr-1"></i> {{ $item->medicines_used }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs italic">None</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('clinic.consultations.show', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('clinic.consultations.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
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