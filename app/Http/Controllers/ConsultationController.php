<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Function: View consultation history
     * Ipinapakita ang lahat ng records na may kasamang student profile data.
     */
    public function index()
    {
        $consultations = Consultation::with('student_medical_record')->latest()->get();
        return view('clinic.consultations.index', compact('consultations'));
    }

    /**
     * Function: Show create form
     * Kinukuha ang listahan ng mga estudyante para sa dropdown selection.
     */
    public function create()
    {
        $students = StudentMedicalRecordClinic::all();
        return view('clinic.consultations.create', compact('students'));
    }

    /**
     * Function: Create consultation record
     * Nagse-save ng symptoms, diagnosis, treatment, at attached medicines.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medicines_used' => 'nullable|string',
        ]);

        Consultation::create($validated);

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation recorded successfully!');
    }

    /**
     * Function: View specific consultation details
     * Ginagamit para sa "View" (eye icon) action.
     */
    public function show($id)
    {
        $consultation = Consultation::with('student_medical_record')->findOrFail($id);
        return view('clinic.consultations.show', compact('consultation'));
    }

    /**
     * Function: Delete consultation record
     * Ginagamit para sa "Delete" (trash icon) action.
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation record deleted successfully.');
    }
}