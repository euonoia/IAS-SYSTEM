<?php

namespace App\Http\Controllers;

use App\Models\MedicalClearance;
use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicalClearanceController extends Controller
{
    /**
     * MODULE 4: Listahan ng lahat ng Clearances (Index Page)
     */
    public function index()
{
    $clearances = MedicalClearance::with('student_medical_record')->latest()->get();
    return view('clinic.clearances.index', compact('clearances'));
}

    /**
     * MODULE 4: Form para sa New Clearance (Create Page)
     */
    public function create()
    {
        // Kunin ang lahat ng students para sa dropdown list
        $students = StudentMedicalRecordClinic::orderBy('name', 'asc')->get();
        
        return view('clinic.clearances.create', compact('students'));
    }

    /**
     * MODULE 4: Pag-save ng bagong Clearance sa Database
     */
    public function store(Request $request) 
    {
        $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        MedicalClearance::create([
            'student_medical_record_id' => $request->student_medical_record_id,
            'purpose' => $request->purpose,
            'remarks' => $request->remarks,
            'clearance_number' => 'MC-' . strtoupper(Str::random(8)),
            'status' => 'Pending'
        ]);

        // Pagkatapos i-save, redirect pabalik sa listahan (index) na may success message
        return redirect()->route('clinic.clearances.index')
                         ->with('success', 'Medical Clearance request has been created!');
    }

    /**
     * MODULE 4: Pag-approve ng Clearance
     */
    public function approve($id) 
    {
        $clearance = MedicalClearance::findOrFail($id);
        $clearance->update([
            'status' => 'Approved',
            'issued_date' => now()
        ]);

        return back()->with('success', 'Clearance has been approved and marked as ready!');
    }

    /**
     * MODULE 4: Pag-delete ng record
     */
    public function destroy($id)
    {
        $clearance = MedicalClearance::findOrFail($id);
        $clearance->delete();
        
        return back()->with('success', 'Clearance record deleted successfully.');
    }

    /**
     * MODULE 4: Placeholder para sa Print Function (Optional)
     */
    public function print($id)
    {
        $clearance = MedicalClearance::with('student_medical_record')->findOrFail($id);
        // Dito ilalagay ang logic para sa printing o view ng certificate
        return "Printing Clearance: " . $clearance->clearance_number;
    }
}