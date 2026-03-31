@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('clinic.records.index') }}" class="p-3 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Student Health Profile</h2>
                <p class="text-sm text-slate-500 font-medium">Record ID: {{ $record->id }}</p>
            </div>
        </div>
        <a href="{{ route('clinic.records.edit', $record->id) }}" class="bg-white border border-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
            <i class="fas fa-edit text-amber-500"></i> Edit Record
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold mb-4 uppercase">
                {{ substr($record->student_id, -2) }}
            </div>
            <h3 class="text-lg font-bold text-slate-800">{{ $record->student_id }}</h3>
            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full uppercase mt-2">Active Student</span>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Medical Basics</h4>
                <div class="grid grid-cols-2 gap-8">
                    <div><p class="text-xs text-slate-400 mb-1 font-bold uppercase">Blood Type</p><p class="text-slate-700 font-semibold">{{ $record->blood_type ?? 'Not Specified' }}</p></div>
                    <div><p class="text-xs text-slate-400 mb-1 font-bold uppercase">Last Updated</p><p class="text-slate-700 font-semibold">{{ $record->updated_at->format('M d, Y') }}</p></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Conditions & History</h4>
                <div class="space-y-6">
                    <div><p class="text-xs text-red-400 mb-2 font-bold uppercase">Allergies</p><p class="text-slate-700 leading-relaxed">{{ $record->allergies ?? 'No known allergies.' }}</p></div>
                    <div class="h-px bg-slate-50"></div>
                    <div><p class="text-xs text-amber-500 mb-2 font-bold uppercase">Chronic Illness</p><p class="text-slate-700 leading-relaxed">{{ $record->chronic_illness ?? 'None declared.' }}</p></div>
                    <div class="h-px bg-slate-50"></div>
                    <div><p class="text-xs text-slate-400 mb-2 font-bold uppercase">Medical Notes</p><p class="text-slate-700 leading-relaxed italic">{{ $record->notes ?? 'No additional medical notes available.' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection