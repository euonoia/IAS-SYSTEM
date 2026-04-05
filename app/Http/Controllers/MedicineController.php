<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller 
{
    public function index(Request $request) 
    {
        $search = $request->get('search');
        
        $medicines = Medicine::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('batch_number', 'ILIKE', "%{$search}%")
                      ->orWhere('expiration_date', 'ILIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.medicines.partials.table', compact('medicines'))->render(),
                'pagination' => $medicines->appends($request->query())->links()->toHtml(),
                'total' => $medicines->total(),
                'search' => $search
            ]);
        }
        
        return view('clinic.medicines.index', compact('medicines', 'search'));
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
            'expiration_date' => 'required|date',
        ]);

        // AUTOMATIC MEDICINE NUMBER GENERATION
        // Format: MED- + Timestamp
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