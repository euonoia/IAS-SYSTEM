<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller 
{
    public function index() 
    {
        // Kunin lahat ng gamot, unahin ang pinakabago
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
            'batch_number' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        Medicine::create($validated);

        // Pagkatapos i-save, babalik tayo sa listahan (index)
        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine added to inventory successfully!');
    }
}