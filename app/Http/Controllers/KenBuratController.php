<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KenBurat;

class KenBuratController extends Controller
{
    // Display all records
    public function index()
    {
        $items = KenBurat::latest()->get();
        return view('ken_burat.index', compact('items'));
    }

    // Show create form
    public function create()
    {
        return view('ken_burat.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_pogi' => 'nullable|boolean'
        ]);

        KenBurat::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_pogi' => $request->has('is_pogi')
        ]);

        return redirect()->route('ken-burat.index')
            ->with('success', 'Data created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $item = KenBurat::findOrFail($id);
        return view('ken_burat.edit', compact('item'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'is_pogi' => 'nullable|boolean'
        ]);

        $item = KenBurat::findOrFail($id);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_pogi' => $request->has('is_pogi')
        ]);

        return redirect()->route('ken-burat.index')
            ->with('success', 'Data updated successfully.');
    }

    // Delete record
    public function destroy($id)
    {
        KenBurat::findOrFail($id)->delete();

        return redirect()->route('ken-burat.index')
            ->with('success', 'Data deleted successfully.');
    }
}