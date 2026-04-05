<?php

namespace App\Http\Controllers;

use App\Models\MedicalClearance;
use App\Models\StudentMedicalRecordClinic;
use App\Traits\CacheableIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicalClearanceController extends Controller
{
    use CacheableIndex;

    /**
     * MODULE 4: Listahan ng Medical Clearances (Index Page)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $clearances = MedicalClearance::with('student_medical_record')
            ->when($search, function ($query) use ($search) {
                $query->where('clearance_number', 'ILIKE', "%{$search}%")
                      ->orWhere('purpose', 'ILIKE', "%{$search}%")
                      ->orWhere('status', 'ILIKE', "%{$search}%")
                      ->orWhereHas('student_medical_record', function ($q) use ($search) {
                          $q->where('name', 'ILIKE', "%{$search}%")
                            ->orWhere('student_id', 'ILIKE', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10);

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.clearances.partials.table', compact('clearances'))->render(),
                'pagination' => $clearances->appends($request->query())->links()->toHtml(),
                'total' => $clearances->total(),
                'search' => $search
            ]);
        }
        
        return view('clinic.clearances.index', compact('clearances', 'search'));
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
        
        // Invalidate index cache
        $this->forgetAllIndexCache(MedicalClearance::class);

        // Pagkatapos i-save, redirect pabalik sa listahan (index) na may success message
        return redirect()->route('clinic.clearances.index')
                         ->with('success', 'Medical Clearance request has been created!');
    }

    /**
     * MODULE 4: Approve clearance at mark as ready (Update status to Approved)
     */
    public function approve($id) 
    {
        $clearance = MedicalClearance::findOrFail($id);
        $clearance->update([
            'status' => 'Approved',
            'issued_date' => now()
        ]);
        
        // Invalidate index cache since status changed
        $this->forgetAllIndexCache(MedicalClearance::class);

        return back()->with('success', 'Clearance has been approved and marked as ready!');
    }

    /**
     * MODULE 4: Delete record
     */
    public function destroy($id)
    {
        $clearance = MedicalClearance::findOrFail($id);
        $clearance->delete();
        
        // Invalidate index cache
        $this->forgetAllIndexCache(MedicalClearance::class);
        
        return back()->with('success', 'Clearance record deleted successfully.');
    }

    /**
     * MODULE 4: Print the Clearance Certificate
     */
    public function print($id)
    {
        $clearance = MedicalClearance::with('student_medical_record')->findOrFail($id);
        return view('clinic.clearances.print', compact('clearance'));
    }
}