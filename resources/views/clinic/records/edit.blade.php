@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.records.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Edit Medical Profile</h2>
            <p class="text-sm text-slate-200 font-medium">Updating record for: {{ $record->student_id }}</p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <form action="{{ route('clinic.records.update', $record->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Student ID Number</label>
                    <input type="text" name="student_id" value="{{ $record->student_id }}" readonly 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-900 text-slate-400 font-mono cursor-not-allowed">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ $record->name }}" required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Blood Type</label>
                    <select name="blood_type" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                        <option value="">Select Blood Type</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                            <option value="{{ $bt }}" {{ $record->blood_type == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Chronic Illness</label>
                    <input type="text" name="chronic_illness" value="{{ $record->chronic_illness }}"
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Allergies</label>
                    <textarea name="allergies" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">{{ $record->allergies }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-white/10">
                <a href="{{ route('clinic.records.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-300 transition-colors">Cancel</a>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all transform active:scale-95">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection