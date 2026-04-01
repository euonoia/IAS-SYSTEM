@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.consultations.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">New Consultation</h2>
            <p class="text-sm text-slate-500 font-medium">Module 2: Add a student check-up record</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.consultations.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="student_medical_record_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Student Record</label>
                    <select id="student_medical_record_id" name="student_medical_record_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-white">
                        <option value="">Select a student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_medical_record_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->student_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="symptoms" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Symptoms</label>
                    <textarea id="symptoms" name="symptoms" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="Describe the student's symptoms...">{{ old('symptoms') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="diagnosis" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="Enter the diagnosis...">{{ old('diagnosis') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="treatment" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Treatment</label>
                    <textarea id="treatment" name="treatment" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="Record the treatment given...">{{ old('treatment') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="medicines_used" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Medicines Used</label>
                    <input id="medicines_used" type="text" name="medicines_used" value="{{ old('medicines_used') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="e.g. Paracetamol 500mg">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100">
                <a href="{{ route('clinic.consultations.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Discard</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-95">
                    Save Consultation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
