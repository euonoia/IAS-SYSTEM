@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.records.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-200 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">New Medical Profile</h2>
            <p class="text-sm text-slate-200 font-medium">Module 1: Student Medical Records</p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <form action="{{ route('clinic.records.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Student ID</label>
                    <input type="text" name="student_id" value="{{ $generatedId }}" readonly required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-900 text-blue-400 font-mono font-bold focus:outline-none cursor-not-allowed shadow-inner">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" required placeholder="Enter student's full name"
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Blood Type</label>
                    <select name="blood_type" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Chronic Illness</label>
                    <input type="text" name="chronic_illness" 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                        placeholder="e.g. Asthma, Diabetes">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Allergies</label>
                    <textarea name="allergies" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                        placeholder="List any drug or food allergies..."></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-white/10">
                <a href="{{ route('clinic.records.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all transform active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Save Record</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection