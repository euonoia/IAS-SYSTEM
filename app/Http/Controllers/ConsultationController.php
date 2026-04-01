<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the consultations.
     */
    public function index()
    {
        // Ginamit ang with('student_medical_record') para maiwasan ang N+1 query problem
        // Ito ang sumisiguro na laging may data ang student_id sa view
        $consultations = Consultation::with('student_medical_record')
            ->latest()
            ->get();

        return view('clinic.consultations.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new consultation.
     */
    public function create()
    {
        // Kinuha ang lahat ng students para sa dropdown sa create form
        $students = StudentMedicalRecordClinic::orderBy('student_id', 'asc')->get();
        
        return view('clinic.consultations.create', compact('students'));
    }

    /**
     * Store a newly created consultation in storage.
     */
    public function store(Request $request)
    {
        // Validation rules para sa safe na data entry
        $validated = $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'symptoms' => 'required|string|min:2',
            'diagnosis' => 'required|string|min:2',
            'treatment' => 'required|string|min:2',
            'medicines_used' => 'nullable|string',
        ], [
            'student_medical_record_id.required' => 'Mangyaring pumili ng student sa listahan.',
            'student_medical_record_id.exists'   => 'Ang napiling student record ay hindi wasto.'
        ]);

        // Pag-save ng data
        Consultation::create($validated);

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation recorded successfully!');
    }

    /**
     * Display the specified consultation.
     */
    public function show($id)
    {
        // Fail gracefully kung hindi mahanap ang ID
        $consultation = Consultation::with('student_medical_record')->findOrFail($id);
        
        return view('clinic.consultations.show', compact('consultation'));
    }

    /**
     * Remove the specified consultation from storage.
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation record deleted successfully.');
    }
}