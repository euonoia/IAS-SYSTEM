@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.incidents.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-200 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Report Health Incident</h2>
            <p class="text-sm text-slate-200 font-medium">Module 3: Health Incident Documentation</p>
        </div>
    </div>
    
    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <form action="{{ route('clinic.incidents.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Student Involved</label>
                    <select name="student_medical_record_id" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required>
                        <option value="">Select a student</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_id }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Incident Type</label>
                    <input type="text" name="incident_type" placeholder="e.g. Sports Injury" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Location</label>
                    <input type="text" name="location" placeholder="Location" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">What Happened? (Description)</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required></textarea>
            </div>

            <div class="mt-6">
                <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">First Aid Given</label>
                <textarea name="first_aid_given" rows="2" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required></textarea>
            </div>

            <div class="mt-6">
                <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Incident Date & Time</label>
                <input type="datetime-local" name="incident_date" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required>
            </div>

            <div class="mt-6">
                <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Witness / Reported By</label>
                <input type="text" name="reported_by" placeholder="Name of witness" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" required>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100/20">
                <a href="{{ route('clinic.incidents.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-300 transition-colors">Discard</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all active:scale-95">
                    Submit Incident Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection