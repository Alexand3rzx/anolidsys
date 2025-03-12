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
        $infant = Infant::find($id);
    
        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }
    
        return response()->json($infant);
    }



     // Update an existing infant
     public function update(Request $request, $id)
     {
         $infant = Infant::findOrFail($id);
         $infant->update($request->only([
             'child_name', 'child_bday', 'child_place', 'child_address',
             'child_mother', 'child_father', 'child_gender', 'child_height', 'child_weight'
         ]));

         // Find the immunization record or create a new one
         $immunization = Immunization::firstOrNew(['infant_id' => $id]);

         // Ensure the infant_id is set
         $immunization->infant_id = $id;

         // Update only the fields that are present in the request
         $immunization->fill(array_filter([
             'bcg_date' => $request->bcg_date ?? $immunization->bcg_date,
             'hepatitis_b_date' => $request->hepatitis_b_date ?? $immunization->hepatitis_b_date,
             'pentavalent_date_1' => $request->pentavalent_date_1 ?? $immunization->pentavalent_date_1,
             'pentavalent_date_2' => $request->pentavalent_date_2 ?? $immunization->pentavalent_date_2,
             'pentavalent_date_3' => $request->pentavalent_date_3 ?? $immunization->pentavalent_date_3,
             'opv_date_1' => $request->opv_date_1 ?? $immunization->opv_date_1,
             'opv_date_2' => $request->opv_date_2 ?? $immunization->opv_date_2,
             'opv_date_3' => $request->opv_date_3 ?? $immunization->opv_date_3,
             'ipv_date_1' => $request->ipv_date_1 ?? $immunization->ipv_date_1,
             'ipv_date_2' => $request->ipv_date_2 ?? $immunization->ipv_date_2,
             'pcv_date_1' => $request->pcv_date_1 ?? $immunization->pcv_date_1,
             'pcv_date_2' => $request->pcv_date_2 ?? $immunization->pcv_date_2,
             'pcv_date_3' => $request->pcv_date_3 ?? $immunization->pcv_date_3,
             'mmr_date_1' => $request->mmr_date_1 ?? $immunization->mmr_date_1,
             'mmr_date_2' => $request->mmr_date_2 ?? $immunization->mmr_date_2
         ]));

         $immunization->save();

         return back()->with('success', 'Infant updated successfully');
     }
     
 }