<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;

class StudentMedicalRecordClinicController extends Controller
{
    // List of all medical records
    public function index()
    {
        $records = StudentMedicalRecordClinic::latest()->get();
        return view('clinic.records.index', compact('records'));
    }

    // Show the form for creating a new record
    public function create()
    {
        return view('clinic.records.create');
    }

    // Save the new record to the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => 'required|unique:student_medical_record_clinics,student_id',
            'name'            => 'required|string|max:255',
            'blood_type'      => 'nullable|string',
            'allergies'       => 'nullable|string',
            'chronic_illness' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        StudentMedicalRecordClinic::create($validated);

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully created.');
    }

    // Show the specific medical record of a student
    public function show($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.show', compact('record'));
    }

    // Show the form for updating a record
    public function edit($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.edit', compact('record'));
    }

    // Save the updates to the record
    public function update(Request $request, $id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        
        $validated = $request->validate([
            'student_id'      => 'required|unique:student_medical_record_clinics,student_id,'.$id,
            'name'            => 'required|string|max:255',
            'blood_type'      => 'nullable|string',
            'allergies'       => 'nullable|string',
            'chronic_illness' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        $record->update($validated);

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully updated.');
    }

    
    public function destroy($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        $record->delete();

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully deleted.');
    }
}