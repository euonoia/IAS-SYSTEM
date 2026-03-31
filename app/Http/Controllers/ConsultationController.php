<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        // Ginamit ang with() para sa efficient data loading
        $consultations = Consultation::with('student_medical_record')->latest()->get();
        return view('clinic.consultations.index', compact('consultations'));
    }

    public function create()
    {
        $students = StudentMedicalRecordClinic::orderBy('student_id', 'asc')->get();
        return view('clinic.consultations.create', compact('students'));
    }

    public function store(Request $request)
    {
        // REFINEMENT: Mas mahigpit na validation rules
        $validated = $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'symptoms' => 'required|string|min:3',
            'diagnosis' => 'required|string|min:3',
            'treatment' => 'required|string|min:3',
            'medicines_used' => 'nullable|string',
        ], [
            'student_medical_record_id.required' => 'Please select a student from the list.',
            'student_medical_record_id.exists'   => 'The selected student record does not exist.'
        ]);

        Consultation::create($validated);

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation recorded successfully!');
    }

    public function show($id)
    {
        $consultation = Consultation::with('student_medical_record')->findOrFail($id);
        return view('clinic.consultations.show', compact('consultation'));
    }

    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation record deleted successfully.');
    }
}