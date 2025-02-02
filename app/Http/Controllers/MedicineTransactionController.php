<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineTransaction;
use Illuminate\Http\Request;

class MedicineTransactionController extends Controller
{
    // Method to handle storing the transaction (Receive or Give)
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',  // Ensure the medicine exists
            'quantity' => 'required|integer|min:1',  // Quantity must be at least 1
            'donor' => 'nullable|string',
            'receiver' => 'nullable|string',
            'details' => 'nullable|string',
            'type' => 'required|in:receive,give',  // Transaction type must be either "receive" or "give"
        ]);
    
        // Find the medicine being affected by the transaction
        $medicine = Medicine::find($validated['medicine_id']);
    
        if (!$medicine) {
            return back()->with('error', 'Medicine not found!');
        }
    
        // Handle the 'receive' type transaction (increase stock)
        if ($validated['type'] === 'receive') {
            $medicine->stock += $validated['quantity'];  // Increase stock
        }
    
        // Handle the 'give' type transaction (decrease stock)
        if ($validated['type'] === 'give') {
            if ($medicine->stock < $validated['quantity']) {
                return back()->with('error', 'Not enough stock to give!');
            }
            $medicine->stock -= $validated['quantity'];  // Decrease stock
        }
    
        // Save the updated stock for the medicine
        $medicine->save();
    
        // Create the transaction record in the medicine_transactions table
        MedicineTransaction::create([
            'medicine_id' => $validated['medicine_id'],
            'quantity' => $validated['quantity'],  // Store the quantity in the transaction
            'donor' => $validated['donor'],        // Donor info if available
            'receiver' => $validated['receiver'],  // Receiver info if available
            'details' => $validated['details'],    // Any additional details
            'type' => $validated['type'],          // Store transaction type (receive/give)
        ]);
    
        // Redirect back with a success message
        return back()->with('success', 'Transaction recorded successfully.');
    }
    
    // back to zero sa receive at give yehey

}
