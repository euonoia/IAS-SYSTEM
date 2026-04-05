@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Medicine Inventory</h2>
            <p class="text-sm text-slate-200 font-medium">Manage clinic supplies and monitor stock levels</p>
        </div>
        <a href="{{ route('clinic.medicines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Medicine
        </a>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden text-nowrap overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Medicine Name</th>
                    <th class="px-6 py-4">Medicine ID</th> {{-- PINALITAN: Mula Batch No. --}}
                    <th class="px-6 py-4">Current Stock</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($medicines as $med)
                <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-100">{{ $med->name }}</td>
                    <td class="px-6 py-4 font-mono text-blue-300 text-sm">{{ $med->batch_number }}</td> {{-- Eto yung auto-generated --}}
                    <td class="px-6 py-4 text-slate-200">{{ $med->stock_quantity }} units</td>
                    <td class="px-6 py-4">
                        @if($med->stock_quantity <= $med->low_stock_threshold)
                            <span class="px-3 py-1 bg-red-100/10 text-red-300 text-[10px] font-bold rounded-lg border border-red-700/10 uppercase">Low Stock</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100/10 text-emerald-300 text-[10px] font-bold rounded-lg border border-emerald-700/10 uppercase">Available</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                        <a href="{{ route('clinic.medicines.edit', $med->id) }}" class="text-slate-400 hover:text-amber-300 transition-colors p-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('clinic.medicines.destroy', $med->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this medicine?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors p-2">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">No medicines in inventory yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection