<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\StudentMedicalRecordClinic;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the consultations.
     */
    public function index()
    {
        // Ginamit ang with('student_medical_record') para maiwasan ang N+1 query problem
        // Ito ang sumisiguro na laging may data ang student_id sa view
        $consultations = Consultation::with('student_medical_record', 'medicine')
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
        
        // Kinuha ang lahat ng medicines na may stock para sa dropdown
        $medicines = Medicine::where('stock_quantity', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
        
        return view('clinic.consultations.create', compact('students', 'medicines'));
    }

    /**
     * Store a newly created consultation in storage.
     * 
     * Uses DB::transaction to ensure atomicity:
     * - Either both consultation and stock decrement succeed
     * - Or both rollback if any error occurs
     */
    public function store(Request $request)
    {
        // Validation rules para sa safe na data entry
        $validated = $request->validate([
            'student_medical_record_id' => 'required|exists:student_medical_record_clinics,id',
            'symptoms' => 'required|string|min:2',
            'diagnosis' => 'required|string|min:2',
            'treatment' => 'required|string|min:2',
            'medicine_id' => 'nullable|exists:medicines,id',
            'quantity_used' => 'nullable|numeric|min:1',
        ], [
            'student_medical_record_id.required' => 'Mangyaring pumili ng student sa listahan.',
            'student_medical_record_id.exists'   => 'Ang napiling student record ay hindi wasto.',
            'medicine_id.exists'                 => 'Ang napiling gamot ay hindi wasto.',
            'quantity_used.numeric'              => 'Ang dami ng gamot na ginamit ay dapat numeric.',
            'quantity_used.min'                  => 'Ang dami ng gamot ay dapat hindi mababa sa 1.',
        ]);

        // Process with transaction protection
        try {
            DB::transaction(function () use ($validated) {
                // Step 1: Validate stock availability if medicine is selected
                if ($validated['medicine_id'] && $validated['quantity_used']) {
                    $medicine = Medicine::lockForUpdate()->findOrFail($validated['medicine_id']);
                    
                    $quantityToUse = (int) $validated['quantity_used'];
                    $currentStock = (int) $medicine->stock_quantity;
                    
                    // Validation: Check if we have enough stock
                    if ($quantityToUse > $currentStock) {
                        throw new \Exception(
                            "Insufficient stock for {$medicine->name}. " .
                            "Available: {$currentStock}, Requested: {$quantityToUse}"
                        );
                    }
                    
                    // Validation: Check that stock will never go below 0
                    $newStock = $currentStock - $quantityToUse;
                    if ($newStock < 0) {
                        throw new \Exception(
                            "Stock calculation error. Would result in negative stock for {$medicine->name}."
                        );
                    }
                    
                    // Step 2: Explicit math - decrement exact amount
                    $medicine->decrement('stock_quantity', $quantityToUse);
                    
                    // Verify the decrement worked
                    $medicine->refresh();
                    if ($medicine->stock_quantity < 0) {
                        throw new \Exception(
                            "Critical error: {$medicine->name} stock went below zero. Transaction rolled back."
                        );
                    }
                }
                
                // Step 3: Create consultation record only after stock validation and update
                Consultation::create($validated);
            });
            
            return redirect()->route('clinic.consultations.index')
                            ->with('success', 'Consultation recorded successfully!');
                  
        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified consultation.
     */
    public function show($id)
    {
        // Fail gracefully kung hindi mahanap ang ID
        $consultation = Consultation::with('student_medical_record', 'medicine')->findOrFail($id);
        
        return view('clinic.consultations.show', compact('consultation'));
    }

    /**
     * Remove the specified consultation from storage.
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Restore medicine stock when consultation is deleted
        if ($consultation->medicine_id && $consultation->quantity_used) {
            $medicine = Medicine::findOrFail($consultation->medicine_id);
            $medicine->increment('stock_quantity', $consultation->quantity_used);
        }
        
        $consultation->delete();

        return redirect()->route('clinic.consultations.index')
                         ->with('success', 'Consultation record deleted successfully.');
    }
}