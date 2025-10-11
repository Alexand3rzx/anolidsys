<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregnant;
use App\Models\FirstPregnancyImmunization;
use App\Models\SecondToFifthPregnancyImmunization;
use App\Models\SixthPregnancyImmunization;

class PregnantImmunizationController extends Controller
{
    // Display immunization records for a specific pregnant woman
    public function index($id)
    {
        $pregnant = Pregnant::findOrFail($id);

        // Select model based on pregnancy times
        if ($pregnant->prgtimes == 1) {
            $records = FirstPregnancyImmunization::where('pregnant_id', $pregnant->id)->get();
        } elseif ($pregnant->prgtimes >= 2 && $pregnant->prgtimes <= 5) {
            $records = SecondToFifthPregnancyImmunization::where('pregnant_id', $pregnant->id)->get();
        } else {
            $records = SixthPregnancyImmunization::where('pregnant_id', $pregnant->id)->get();
        }

        return view('beneficiaries.pregnant_show', compact('pregnant', 'records'));
    }

    // Store a new immunization record
    public function store(Request $request, $id)
    {
        $pregnant = Pregnant::findOrFail($id);

        $validated = $request->validate([
            'visit_number' => 'required|string|max:50',
            'visit_date' => 'required|date',
            'expected_month' => 'nullable|string|max:100',
            'vaccine_given' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        $validated['pregnant_id'] = $pregnant->id;

        if ($pregnant->prgtimes == 1) {
            FirstPregnancyImmunization::create($validated);
        } elseif ($pregnant->prgtimes >= 2 && $pregnant->prgtimes <= 5) {
            SecondToFifthPregnancyImmunization::create($validated);
        } else {
            SixthPregnancyImmunization::create($validated);
        }

        return back()->with('success', 'Immunization record added successfully!');
    }

    // Delete a record
    public function destroy($id, $recordId)
    {
        $pregnant = Pregnant::findOrFail($id);

        if ($pregnant->prgtimes == 1) {
            FirstPregnancyImmunization::destroy($recordId);
        } elseif ($pregnant->prgtimes >= 2 && $pregnant->prgtimes <= 5) {
            SecondToFifthPregnancyImmunization::destroy($recordId);
        } else {
            SixthPregnancyImmunization::destroy($recordId);
        }

        return back()->with('success', 'Immunization record deleted successfully!');
    }
}
