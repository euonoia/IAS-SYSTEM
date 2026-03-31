@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Patient Medical Records</h2>
        <p class="text-sm text-slate-500 font-medium">Manage and view all student health profiles</p>
    </div>
    <a href="{{ route('clinic.records.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Add New Patient
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Student ID</th>
                <th class="px-6 py-4">Blood Type</th>
                <th class="px-6 py-4">Allergies</th>
                <th class="px-6 py-4">Chronic Illness</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($records as $record)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $record->student_id }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $record->blood_type ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-red-500">{{ Str::limit($record->allergies ?? 'None', 30) }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($record->chronic_illness ?? 'None', 30) }}</td>
                <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                    <a href="{{ route('clinic.records.show', $record->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors p-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('clinic.records.edit', $record->id) }}" class="text-slate-400 hover:text-amber-600 transition-colors p-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('clinic.records.destroy', $record->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-2">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
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