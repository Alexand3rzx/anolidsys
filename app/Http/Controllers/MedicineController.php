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

    public function receive(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'donor' => 'required|string',
            'receiver' => 'required|string',
            'details' => 'nullable|string',
        ]);
    
        // Increase stock
        $medicine->increment('stock', $request->quantity);
    
        // Log transaction
        $medicine->transactions()->create([
            'quantity' => $request->quantity,
            'donor' => $request->donor,
            'receiver' => $request->receiver,
            'details' => $request->details,
            'type' => 'receive',
        ]);
    
        return redirect()->route('medicines.index')->with('success', 'Medicine stock increased.');
    }

    public function give(Request $request, Medicine $medicine)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'receiver' => 'required|string',
        'administered_by' => 'required|string',
        'details' => 'nullable|string',
    ]);

    // Check if enough stock is available
    if ($medicine->stock < $request->quantity) {
        return redirect()->route('medicines.index')->with('error', 'Not enough stock available.');
    }

    // Decrease stock
    $medicine->decrement('stock', $request->quantity);

    // Log transaction
    $medicine->transactions()->create([
        'quantity' => $request->quantity,
        'receiver' => $request->receiver,
        'administered_by' => $request->administered_by,
        'details' => $request->details,
        'type' => 'give',
    ]);

    return redirect()->route('medicines.index')->with('success', 'Medicine stock decreased.');
}
}
