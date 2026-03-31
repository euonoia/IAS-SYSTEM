@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('clinic.consultations.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Consultation Details</h2>
            <p class="text-sm text-slate-500 font-medium">Review the recorded check-up information</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Student ID</p>
            <p class="text-slate-800 font-semibold">{{ optional($consultation->student_medical_record)->student_id ?? 'N/A' }}</p>
        </div>

        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Consultation Date</p>
            <p class="text-slate-800 font-semibold">{{ $consultation->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <div class="md:col-span-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Symptoms</p>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-slate-700">{{ $consultation->symptoms }}</div>
        </div>

        <div class="md:col-span-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Diagnosis</p>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-slate-700">{{ $consultation->diagnosis }}</div>
        </div>

        <div class="md:col-span-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Treatment</p>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-slate-700">{{ $consultation->treatment }}</div>
        </div>

        <div class="md:col-span-2">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Medicines Used</p>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-slate-700">{{ $consultation->medicines_used ?: 'None recorded' }}</div>
        </div>
    </div>
</div>
@endsection
