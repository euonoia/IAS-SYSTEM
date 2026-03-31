@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.records.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Medical Profile</h2>
            <p class="text-sm text-slate-500 font-medium">Updating record for Student ID: {{ $record->student_id }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.records.update', $record->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Student ID Number</label>
                    <input type="text" name="student_id" value="{{ $record->student_id }}" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none transition-all" readonly>
                    <p class="text-[10px] text-slate-400 mt-1 italic">Student ID cannot be changed once created.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Blood Type</label>
                    <select name="blood_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none transition-all bg-white">
                        <option value="">Select Blood Type</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $type)
                            <option value="{{ $type }}" {{ $record->blood_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Chronic Illness</label>
                    <input type="text" name="chronic_illness" value="{{ $record->chronic_illness }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none transition-all"
                        placeholder="e.g. Asthma, Diabetes">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Allergies</label>
                    <textarea name="allergies" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none transition-all">{{ $record->allergies }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Additional Medical Notes</label>
                    <textarea name="notes" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none transition-all">{{ $record->notes }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100">
                <a href="{{ route('clinic.records.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-amber-100 transition-all transform active:scale-95">
                    Update Medical Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection