<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\CacheableIndex;
use Illuminate\Http\Request;
use App\Models\HealthIncident;
use App\Models\StudentMedicalRecordClinic;

class HealthIncidentController extends Controller
{
    use CacheableIndex;

    public function index(Request $request) {
        $search = $request->get('search');
        
        $incidents = HealthIncident::with('student_medical_record')
            ->when($search, function ($query) use ($search) {
                $query->where('incident_type', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%")
                      ->orWhere('location', 'ILIKE', "%{$search}%")
                      ->orWhere('first_aid_given', 'ILIKE', "%{$search}%")
                      ->orWhere('action_taken', 'ILIKE', "%{$search}%")
                      ->orWhere('reported_by', 'ILIKE', "%{$search}%")
                      ->orWhereHas('student_medical_record', function ($q) use ($search) {
                          $q->where('name', 'ILIKE', "%{$search}%")
                            ->orWhere('student_id', 'ILIKE', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.incidents.partials.table', compact('incidents'))->render(),
                'pagination' => $incidents->appends($request->query())->links()->toHtml(),
                'total' => $incidents->total(),
                'search' => $search
            ]);
        }
        
        return view('clinic.incidents.index', compact('incidents', 'search'));
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
        
        // Invalidate index cache
        $this->forgetAllIndexCache(HealthIncident::class);

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
        
        // Invalidate index cache
        $this->forgetAllIndexCache(HealthIncident::class);
        
        return back()->with('success', 'Incident report deleted.');
    }
}