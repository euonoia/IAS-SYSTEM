@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    /* 1. The main container background and border */
    .ts-control {
        background-color: #1e293b !important; /* bg-slate-800 */
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.75rem !important; 
        padding: 0.75rem 1rem !important;
        color: white !important; /* Text color for selected item */
    }

    /* 2. The input field (where you actually type) */
    .ts-control input {
        color: white !important;
        font-size: 1rem !important;
    }

    /* 3. Placeholder color (before you start typing) */
    .ts-control input::placeholder {
        color: #94a3b8 !important; /* slate-400 */
    }

    /* 4. The dropdown menu background */
    .ts-dropdown {
        background-color: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 0.75rem !important;
        margin-top: 5px !important;
        color: white !important;
    }

    /* 5. Individual options in the list */
    .ts-dropdown .option {
        color: white !important; /* Makes list text white */
        padding: 10px 15px !important;
    }

    /* 6. Hover/Active state for options */
    .ts-dropdown .active {
        background-color: #2563eb !important; /* bg-blue-600 */
        color: white !important;
    }

    /* 7. Remove the default blue glow from Bootstrap if it appears */
    .ts-wrapper.focus .ts-control {
        box-shadow: 0 0 0 2px #60a5fa !important; /* blue-400 ring */
        border-color: #60a5fa !important;
    }
</style>

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clinic.consultations.index') }}" class="p-2 bg-white/10 border border-white/20 rounded-xl text-slate-200 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">New Consultation</h2>
            <p class="text-sm text-slate-200 font-medium">Module 2: Add a student check-up record</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-300">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <form action="{{ route('clinic.consultations.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Searchable Student Selection --}}
                <div class="md:col-span-2">
                    <label for="student_medical_record_id" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                        Search Student (Name or ID)
                    </label>
                    <select id="student_medical_record_id" name="student_medical_record_id" required autocomplete="off" placeholder="Start typing name or student ID...">
                        <option value="">Select a student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_medical_record_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} — {{ $student->student_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="symptoms" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Symptoms</label>
                    <textarea id="symptoms" name="symptoms" rows="3" required class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" placeholder="Describe the student's symptoms...">{{ old('symptoms') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="diagnosis" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" rows="3" required class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" placeholder="Enter the diagnosis...">{{ old('diagnosis') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="treatment" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Treatment</label>
                    <textarea id="treatment" name="treatment" rows="3" required class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" placeholder="Record the treatment given...">{{ old('treatment') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="medicines_used" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">Medicines Used</label>
                    <input id="medicines_used" type="text" name="medicines_used" value="{{ old('medicines_used') }}" class="w-full px-4 py-3 rounded-xl border border-white/20 bg-slate-800 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400 outline-none transition-all" placeholder="e.g. Paracetamol 500mg">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t border-slate-100/20">
                <a href="{{ route('clinic.consultations.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-300 transition-colors">Discard</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all transform active:scale-95">
                    Save Consultation
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#student_medical_record_id", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
</script>
@endsection