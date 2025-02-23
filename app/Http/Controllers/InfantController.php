<?php

namespace App\Http\Controllers;

use App\Models\Infant;
use Illuminate\Http\Request;

class InfantController extends Controller
{
    // Store a new infant
    public function store(Request $request)
    {
        $request->validate([
            'child_name' => 'required|string',
            'child_bday' => 'required|date',
            'child_place' => 'required|string',
            'child_address' => 'required|string',
            'child_mother' => 'required|string',
            'child_father' => 'required|string',
            'child_gender' => 'required|in:Male,Female',
            'child_height' => 'required|numeric',
            'child_weight' => 'required|numeric',
        ]);

        Infant::create($request->all());

        return redirect()->route('beneficiaries.index')->with('success', 'Infant added successfully!');
    }

    // Fetch infant data for editing
    public function edit($id)
    {
        $infant = Infant::findOrFail($id);
        return response()->json($infant);
    }

    // Update an existing infant
    public function update(Request $request, Infant $infant)
    {
        $request->validate([
            'child_name' => 'required|string',
            'child_bday' => 'required|date',
            'child_place' => 'required|string',
            'child_address' => 'required|string',
            'child_mother' => 'required|string',
            'child_father' => 'required|string',
            'child_gender' => 'required|in:Male,Female',
            'child_height' => 'required|numeric',
            'child_weight' => 'required|numeric',
        ]);

        $infant->update($request->all());

        return redirect()->route('beneficiaries.index')->with('success', 'Infant updated successfully!');
    }
}
