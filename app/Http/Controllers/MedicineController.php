<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Show the medicine list
    public function index()
    {
        $medicines = Medicine::all(); // Retrieve all medicines
        return view('medicines.index', compact('medicines'));
    }

    // Show the form to add new medicine
    public function create()
    {
        return view('medicines.create');
    }

    // Store new medicine in the database
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);
    
        // Create a new medicine record
        Medicine::create([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'stock' => $validated['stock'],
        ]);
    
        // Redirect with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
    }
    // Show the form to edit a specific medicine
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    // Update medicine in the database
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'details' => 'required|string',
            'stocks' => 'required|integer|min:1',
        ]);

        $medicine->update([
            'name' => $validated['medicine_name'],
            'details' => $validated['details'],
            'stock' => $validated['stocks'],
        ]);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    // Increase stock of a specific medicine
    public function receive(Medicine $medicine)
    {
        $medicine->increment('stock', 1); // Increase stock by 1 (or adjust as needed)

        return redirect()->route('medicines.index')->with('success', 'Medicine stock increased.');
    }

    // Decrease stock of a specific medicine
    public function give(Medicine $medicine)
    {
        if ($medicine->stock > 0) {
            $medicine->decrement('stock', 1); // Decrease stock by 1 (or adjust as needed)
            return redirect()->route('medicines.index')->with('success', 'Medicine stock decreased.');
        } else {
            return redirect()->route('medicines.index')->with('error', 'No stock available to give.');
        }
    }

    // Delete a specific medicine from the inventory
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
