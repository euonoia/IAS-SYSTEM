@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.clearances.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-indigo-600 transition-all active:scale-95">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Issue New Clearance</h2>
            <p class="text-sm text-slate-500 font-medium">Module 4: Health Certificate Request</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        {{-- FORM START --}}
        <form action="{{ route('clinic.clearances.store') }}" method="POST" class="p-8">
            @csrf
            <div class="space-y-6">
                
                {{-- Select Student --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Select Student</label>
                    <select name="student_medical_record_id" required 
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-slate-700 font-medium">
                        <option value="" disabled selected>-- Choose Student from Module 1 --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Purpose --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Purpose of Clearance</label>
                    <select name="purpose" required 
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-slate-700 font-medium">
                        <option value="Enrollment">Enrollment</option>
                        <option value="OJT Requirement">OJT Requirement</option>
                        <option value="School Activity">School Activity</option>
                        <option value="PE Requirement">PE Requirement</option>
                        <option value="Athletics/Sports">Athletics/Sports</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                {{-- Remarks --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Medical Remarks (Optional)</label>
                    <textarea name="remarks" rows="4" 
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-slate-700" 
                        placeholder="e.g. Fit to participate, Needs monitoring, etc."></textarea>
                </div>

            </div>

            <div class="flex items-center justify-end gap-4 mt-10 pt-8 border-t border-slate-100">
                <a href="{{ route('clinic.clearances.index') }}" 
                   class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Record
                </button>
            </div>
        </form>
        {{-- FORM END --}}
    </div>
</div>
@endsection