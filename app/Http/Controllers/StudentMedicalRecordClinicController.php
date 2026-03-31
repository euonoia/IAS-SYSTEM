<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use Illuminate\Http\Request;

class StudentMedicalRecordClinicController extends Controller
{
    // Ipakita ang listahan ng lahat ng medical records
    public function index()
    {
        $records = StudentMedicalRecordClinic::latest()->get();
        return view('clinic.records.index', compact('records'));
    }

    // Ipakita ang form para sa paggawa ng bagong record
    public function create()
    {
        return view('clinic.records.create');
    }

    // I-save ang bagong record sa database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => 'required|unique:student_medical_record_clinics,student_id',
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

    // Ipakita ang specific na medical record ng isang estudyante
    public function show($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.show', compact('record'));
    }

    // Ipakita ang form para i-update ang record
    public function edit($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.edit', compact('record'));
    }

    // I-save ang mga pagbabago sa record
    public function update(Request $request, $id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        
        $validated = $request->validate([
            'student_id'      => 'required|unique:student_medical_record_clinics,student_id,'.$id,
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

    // Burahin ang record sa database
    public function destroy($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        $record->delete();

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully deleted.');
    }
}