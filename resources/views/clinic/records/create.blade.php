@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">New Medical Profile</h2>
            <p class="text-sm text-slate-500 font-medium">Module 1: Student Medical Records</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.records.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Student ID Number</label>
                    <input type="text" name="student_id" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        placeholder="e.g. 2026-1234">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Blood Type</label>
                    <select name="blood_type" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-white">
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Chronic Illness</label>
                    <input type="text" name="chronic_illness" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        placeholder="e.g. Asthma, Diabetes (Optional)">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Allergies</label>
                    <textarea name="allergies" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        placeholder="List any drug or food allergies..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Additional Medical Notes</label>
                    <textarea name="notes" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        placeholder="Any other important medical information..."></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    Discard Changes
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-95">
                    Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection