<?php

namespace App\Http\Controllers;

use App\Models\Infant;
use App\Models\Immunization;
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

        // Create infant
        $infant = Infant::create($request->all());

        // Create empty immunization record associated with the infant
        Immunization::create(['infant_id' => $infant->id]);

        return redirect()->route('beneficiaries.index')->with('success', 'Infant added successfully!');
    }





    public function edit($id)
{
    // Fetch the infant record with its associated immunization data
    $infant = Infant::with('immunization')->findOrFail($id);

    // Log the infant data for debugging
    \Log::info('Infant Data:', $infant->toArray());

    // Return the infant data as JSON
    return response()->json($infant);
}


    public function update(Request $request, $id)
{
    $infant = Infant::findOrFail($id);
    $infant->update($request->only([
        'child_name', 'child_bday', 'child_place', 'child_address',
        'child_mother', 'child_father', 'child_gender', 'child_height', 'child_weight'
    ]));

    // Update or create immunization record
    $immunizationData = $request->only([
        'bcg_date', 'hepatitis_b_date', 'pentavalent_date_1', 'pentavalent_date_2', 'pentavalent_date_3',
        'opv_date_1', 'opv_date_2', 'opv_date_3', 'ipv_date_1', 'ipv_date_2',
        'pcv_date_1', 'pcv_date_2', 'pcv_date_3', 'mmr_date_1', 'mmr_date_2'
    ]);

    $infant->immunization()->updateOrCreate(
        ['infant_id' => $id], // Conditions
        $immunizationData // Data to update or create
    );

    return back()->with('success', 'Infant updated successfully');
}
     
 }