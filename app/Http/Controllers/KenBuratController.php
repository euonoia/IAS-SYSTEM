<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KenBurat;

class KenBuratController extends Controller
{
    public function index()
    {
        $items = KenBurat::latest()->get();
        return view('ken_burat.index', compact('items'));
    }

    public function create()
    {
        return view('ken_burat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_pogi' => 'required' 
        ]);

        KenBurat::create([
            'name' => $request->name,
            'description' => $request->description,
            // .boolean() converts "true"/"false" strings to actual booleans
            'is_pogi' => $request->boolean('is_pogi') 
        ]);

        return redirect()->route('ken-burat.index')
            ->with('success', 'Data created successfully.');
    }

    public function edit($id)
    {
        $item = KenBurat::findOrFail($id);
        return view('ken_burat.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_pogi' => 'sometimes'
        ]);

        $item = KenBurat::findOrFail($id);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            // Handles both the checkbox (edit) and dropdown (create)
            'is_pogi' => $request->boolean('is_pogi')
        ]);

        return redirect()->route('ken-burat.index')
            ->with('success', 'Data updated successfully.');
    }

    public function destroy($id)
    {
        KenBurat::findOrFail($id)->delete();
        return redirect()->route('ken-burat.index')
            ->with('success', 'Data deleted successfully.');
    }
}