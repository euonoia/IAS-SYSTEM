@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.medicines.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-all active:scale-95">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Medicine</h2>
            <p class="text-sm text-slate-500 font-medium">Update inventory details for the medicine</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.medicines.update', $medicine->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Medicine Name</label>
                    <input type="text" name="name" required value="{{ old('name', $medicine->name) }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-300"
                        placeholder="e.g. Paracetamol, Amoxicillin">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Stock Quantity</label>
                        <div class="relative">
                            <input type="number" name="stock_quantity" required value="{{ old('stock_quantity', $medicine->stock_quantity) }}"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                                placeholder="0">
                            <span class="absolute right-4 top-3 text-slate-400 text-sm italic font-medium">pcs/units</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1 text-amber-600">Low Stock Alert at</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $medicine->low_stock_threshold) }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-amber-100 bg-amber-50/30 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"
                            placeholder="10">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Expiration Date</label>
                        <div class="relative">
                            <input type="date" name="expiration_date" required value="{{ old('expiration_date', $medicine->expiration_date) }}"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-slate-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Batch / Lot Number</label>
                        <input type="text" name="batch_number" value="{{ old('batch_number', $medicine->batch_number) }}"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-mono text-sm uppercase"
                            placeholder="Optional (e.g. B-2024-X)">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-10 pt-8 border-t border-slate-100">
                <a href="{{ route('clinic.medicines.index') }}"
                   class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-check"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection