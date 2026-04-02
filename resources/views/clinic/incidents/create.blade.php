@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Report Health Incident</h2>
    <form action="{{ route('clinic.incidents.store') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase">Student Involved</label>
                <select name="student_medical_record_id" class="w-full p-3 border rounded-xl" required>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase">Incident Type</label>
                <input type="text" name="incident_type" placeholder="e.g. Sports Injury" class="w-full p-3 border rounded-xl" required>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase">What Happened? (Description)</label>
            <textarea name="description" rows="3" class="w-full p-3 border rounded-xl" required></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase">First Aid Given</label>
            <textarea name="first_aid_given" rows="2" class="w-full p-3 border rounded-xl" required></textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <input type="text" name="location" placeholder="Location" class="p-3 border rounded-xl" required>
            <input type="datetime-local" name="incident_date" class="p-3 border rounded-xl" required>
            <input type="text" name="reported_by" placeholder="Witness/Reported By" class="p-3 border rounded-xl" required>
        </div>

        <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700">
            Submit Incident Report
        </button>
    </form>
</div>
@endsection