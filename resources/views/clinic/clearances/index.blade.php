@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Medical Clearances</h2>
        <p class="text-sm text-slate-500 font-medium"> Health Certificate Issuance</p>
    </div>
    
    <div class="flex gap-2">
         <a href="{{ route('clinic.clearances.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 transition-all active:scale-95">
            <i class="fas fa-plus"></i> New Clearance
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-4 text-nowrap">Clearance #</th>
                    <th class="px-6 py-4">Student Name</th>
                    <th class="px-6 py-4">Purpose</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clearances as $c)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-indigo-600">{{ $c->clearance_number }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                        {{-- Inayos ang variable mula $clearance patungong $c --}}
                        {{ optional($c->student_medical_record)->name ?: optional($c->student_medical_record)->student_id ?: 'Unknown Student' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $c->purpose }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($c->status == 'Pending')
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-extrabold border border-amber-100 uppercase tracking-wider">
                                Pending
                            </span>
                        @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-extrabold border border-emerald-100 uppercase tracking-wider">
                                Approved
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
                        {{-- APPROVE BUTTON: Lalabas lang kung Pending pa --}}
                        @if($c->status == 'Pending')
                        <form action="{{ route('clinic.clearances.approve', $c->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 p-2 bg-emerald-50 rounded-lg transition-all flex items-center shadow-sm border border-emerald-100">
                                <i class="fas fa-check mr-1"></i> Approve
                            </button>
                        </form>
                        @endif

                        {{-- PRINT BUTTON: Lalabas lang kung Approved na --}}
                        @if($c->status == 'Approved')
                        <a href="{{ route('clinic.clearances.print', $c->id) }}" target="_blank" 
                           class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all border border-indigo-100 shadow-sm" 
                           title="Print Certificate">
                            <i class="fas fa-print"></i>
                        </a>
                        @endif
                        
                        {{-- DELETE BUTTON --}}
                        <form action="{{ route('clinic.clearances.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Sigurado ka bang buburahin ito?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-400 hover:text-red-600 transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                        No clearance records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection