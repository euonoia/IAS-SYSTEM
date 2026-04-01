@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Medicine Inventory</h2>
        <p class="text-sm text-slate-500 font-medium">Manage clinic supplies and monitor stock levels</p>
    </div>
    <a href="{{ route('clinic.medicines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add Medicine
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden text-nowrap overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Medicine Name</th>
                <th class="px-6 py-4">Batch No.</th>
                <th class="px-6 py-4">Current Stock</th>
                <th class="px-6 py-4">Expiration</th>
                <th class="px-6 py-4">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($medicines as $med)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 font-bold text-slate-700">{{ $med->name }}</td>
                <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $med->batch_number ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="font-bold {{ $med->stock_quantity <= $med->low_stock_threshold ? 'text-red-600' : 'text-slate-700' }}">
                        {{ $med->stock_quantity }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ \Carbon\Carbon::parse($med->expiration_date)->format('M d, Y') }}
                </td>
                <td class="px-6 py-4">
                    @if($med->stock_quantity <= $med->low_stock_threshold)
                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg border border-red-100 uppercase">Low Stock</span>
                    @else
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100 uppercase">Available</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                    No medicines in inventory yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection