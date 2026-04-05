<?php

namespace App\Http\Controllers;

use App\Models\StudentMedicalRecordClinic;
use App\Traits\CacheableIndex;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentMedicalRecordClinicController extends Controller
{
    use CacheableIndex;

    // List of all medical records
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $records = StudentMedicalRecordClinic::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'ILIKE', "%{$search}%")
                  ->orWhere('name', 'ILIKE', "%{$search}%")
                  ->orWhere('blood_type', 'ILIKE', "%{$search}%")
                  ->orWhere('allergies', 'ILIKE', "%{$search}%")
                  ->orWhere('chronic_illness', 'ILIKE', "%{$search}%")
                  ->orWhere('medical_history', 'ILIKE', "%{$search}%")
                  ->orWhere('notes', 'ILIKE', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.records.partials.table', compact('records'))->render(),
                'pagination' => $records->appends($request->query())->links()->toHtml(),
                'total' => $records->total(),
                'search' => $search
            ]);
        }
        
        return view('clinic.records.index', compact('records', 'search'));
    }

    // Form para sa bagong record
    public function create()
    {
        // FORMAT: ST-YYYY-001
        $year = Carbon::now()->format('Y');
        $prefix = 'ST-' . $year . '-';

        // Kunin ang huling record na may ganitong prefix
        $lastRecord = StudentMedicalRecordClinic::where('student_id', 'LIKE', $prefix . '%')
                        ->orderBy('student_id', 'desc')
                        ->first();

        if ($lastRecord) {
            // Kunin yung huling 3 digits (e.g., 001) at dagdagan ng isa
            $lastNumber = intval(substr($lastRecord->student_id, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Kung wala pang record sa taong ito, simulan sa 001
            $newNumber = '001';
        }

        $generatedId = $prefix . $newNumber;

        return view('clinic.records.create', compact('generatedId'));
    }

    // Save the record
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
        
        // Invalidate index cache
        $this->forgetAllIndexCache(StudentMedicalRecordClinic::class);

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully created.');
    }

    public function show($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.show', compact('record'));
    }

    public function edit($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        return view('clinic.records.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        
        $validated = $request->validate([
            // Kapag nag-edit, student_id ay readonly pero kailangan pa rin sa validation
            'student_id'      => 'required|unique:student_medical_record_clinics,student_id,'.$id,
            'name'            => 'required|string|max:255',
            'blood_type'      => 'nullable|string',
            'allergies'       => 'nullable|string',
            'chronic_illness' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        $record->update($validated);
        
        // Invalidate index cache
        $this->forgetAllIndexCache(StudentMedicalRecordClinic::class);

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully updated.');
    }

    public function destroy($id)
    {
        $record = StudentMedicalRecordClinic::findOrFail($id);
        $record->delete();
        
        // Invalidate index cache
        $this->forgetAllIndexCache(StudentMedicalRecordClinic::class);

        return redirect()->route('clinic.records.index')
                         ->with('success', 'Medical record successfully deleted.');
    }
}