<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthIncident;
use App\Models\StudentMedicalRecordClinic;

class HealthIncidentController extends Controller
{
    public function index() {
        $incidents = HealthIncident::with('student_medical_record')->latest()->get();
        return view('clinic.incidents.index', compact('incidents'));
    }

    public function create() {
        $students = StudentMedicalRecordClinic::orderBy('name', 'asc')->get();
        return view('clinic.incidents.create', compact('students'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'incident_type' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'incident_date' => 'required',
            'first_aid_given' => 'required|string',
            'reported_by' => 'required|string'
        ]);

        HealthIncident::create($validated);

        return redirect()->route('clinic.incidents.index')
                         ->with('success', 'Health incident has been recorded successfully!');
    }

    public function show($id) {
        $incident = HealthIncident::with('student_medical_record')->findOrFail($id);
        return view('clinic.incidents.show', compact('incident'));
    }

    public function destroy($id) {
        $incident = HealthIncident::findOrFail($id);
        $incident->delete();
        return back()->with('success', 'Incident report deleted.');
    }
}