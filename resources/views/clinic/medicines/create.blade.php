@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.medicines.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-200 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Add New Medicine</h2>
            <p class="text-sm text-slate-200 font-medium">Inventory Management & Stock-in</p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <form action="{{ route('clinic.medicines.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Medicine Name</label>
                    <input type="text" name="name" required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                        placeholder="Enter medicine name (e.g. Paracetamol)">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Initial Stock Quantity</label>
                    <input type="number" name="stock_quantity" required min="0"
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                        placeholder="0">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Expiration Date</label>
                    <input type="date" name="expiration_date" required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                </div>

                
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100/20">
                <a href="{{ route('clinic.medicines.index') }}" 
                   class="text-sm font-bold text-slate-400 hover:text-slate-300 transition-colors">Discard</a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Register Medicine
                </button>
            </div>
        </form>
    </div>
</div>
@endsection