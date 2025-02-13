<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $medicines = Medicine::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('details', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc') // Order by medicine name alphabetically
            ->get();

        return view('medicines.index', compact('medicines'));
    }

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
            'expiration' => 'required|date', // New validation rule for expiration
        ]);

        // Create a new medicine record
        Medicine::create([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'stock' => $validated['stock'],
            'expiration' => $validated['expiration'],
        ]);

        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    // Update medicine in the database
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'stock' => 'required|integer|min:1',
            'expiration' => 'required|date', // Ensure expiration is updated
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function receive(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'donor' => 'required|string',
            'receiver' => 'required|string',
            'details' => 'nullable|string',
            'expiration' => 'required|date', // Ensure expiration is included
        ]);

        // Increase stock
        $medicine->increment('stock', $request->quantity);

        // Update expiration if needed (optional)
        $medicine->update(['expiration' => $request->expiration]);

        // Log transaction
        $medicine->transactions()->create([
            'quantity' => $request->quantity,
            'donor' => $request->donor,
            'receiver' => $request->receiver,
            'details' => $request->details,
            'expiration' => $request->expiration,
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

    public function destroy($id)
{
    // Find the medicine by ID
    $medicine = Medicine::findOrFail($id);

    // Delete the medicine
    $medicine->delete();

    // Redirect back with a success message
    return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
}

    
}

