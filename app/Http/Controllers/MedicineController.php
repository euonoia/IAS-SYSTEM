<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str para sa random string

class MedicineController extends Controller 
{
    public function index() 
    {
        $medicines = Medicine::latest()->get();
        return view('clinic.medicines.index', compact('medicines'));
    }

    public function create() 
    {
        return view('clinic.medicines.create');
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        // AUTOMATIC MEDICINE NUMBER GENERATION
        // Format: MED- + Timestamp (para unique)
        $validated['batch_number'] = 'MED-' . time();

        Medicine::create($validated);

        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine added to inventory successfully!');
    }

    public function edit(Medicine $medicine)
    {
        return view('clinic.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->update($validated);

        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return back()->with('success', 'Medicine removed from inventory.');
    }
}