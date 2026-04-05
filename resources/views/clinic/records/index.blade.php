@extends('layouts.app')

@section('content')
<div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-white">Patient Medical Records</h2>
            <p class="text-sm text-slate-200 font-medium mt-1">Manage and view all student health profiles</p>
        </div>
        <a href="{{ route('clinic.records.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Add New Patient
        </a>
    </div>

    <div class="bg-slate-950/80 rounded-2xl border border-slate-800 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-900/90 text-slate-100 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Student ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Blood Type</th>
                        <th class="px-6 py-4">Allergies</th>
                        <th class="px-6 py-4">Chronic Illness</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($records as $record)
                        <tr class="bg-slate-900 even:bg-slate-800/60 hover:bg-slate-800/70 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">{{ $record->student_id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-100">{{ $record->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ $record->blood_type ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-rose-200">{{ Str::limit($record->allergies ?? 'None', 30) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-200">{{ Str::limit($record->chronic_illness ?? 'None', 30) }}</td>
                            <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                    <a href="{{ route('clinic.records.show', $record->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors p-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('clinic.records.edit', $record->id) }}" class="text-slate-400 hover:text-amber-600 transition-colors p-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-folder-open text-4xl mb-4"></i>
                        <p class="italic text-sm">No medical records found yet.</p>
                        <a href="{{ route('clinic.records.create') }}" class="mt-4 text-blue-600 font-bold hover:underline">Create the first record</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection