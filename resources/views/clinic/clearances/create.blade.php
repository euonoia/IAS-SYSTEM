@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.clearances.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-200 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Issue New Clearance</h2>
            <p class="text-sm text-slate-200 font-medium">Module 4: Health Certificate Request</p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        {{-- FORM START --}}
        <form action="{{ route('clinic.clearances.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Select Student --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Select Student</label>
                    <select name="student_medical_record_id" required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                        <option value="" disabled selected>-- Choose Student from Module 1 --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Purpose --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Purpose of Clearance</label>
                    <select name="purpose" required 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                        <option value="Enrollment">Enrollment</option>
                        <option value="OJT Requirement">OJT Requirement</option>
                        <option value="School Activity">School Activity</option>
                        <option value="PE Requirement">PE Requirement</option>
                        <option value="Athletics/Sports">Athletics/Sports</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Medical Remarks (Optional)</label>
                    <textarea name="remarks" rows="4" 
                        class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" 
                        placeholder="e.g. Fit to participate, Needs monitoring, etc."></textarea>
                </div>

            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100/20">
                <a href="{{ route('clinic.clearances.index') }}" 
                   class="text-sm font-bold text-slate-400 hover:text-slate-300 transition-colors">Cancel</a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Record
                </button>
            </div>
        </form>
        {{-- FORM END --}}
    </div>
</div>
@endsection