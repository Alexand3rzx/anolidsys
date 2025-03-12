<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImmunizationController extends Controller
{
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'bcg_date' => 'nullable|date',
        'hepatitis_b_date' => 'nullable|date',
        'pentavalent_date_1' => 'nullable|date',
        'pentavalent_date_2' => 'nullable|date',
        'pentavalent_date_3' => 'nullable|date',
        'opv_date_1' => 'nullable|date',
        'opv_date_2' => 'nullable|date',
        'opv_date_3' => 'nullable|date',
        'ipv_date_1' => 'nullable|date',
        'ipv_date_2' => 'nullable|date',
        'pcv_date_1' => 'nullable|date',
        'pcv_date_2' => 'nullable|date',
        'pcv_date_3' => 'nullable|date',
        'mmr_date_1' => 'nullable|date',
        'mmr_date_2' => 'nullable|date',
    ]);

    $immunization = Immunization::updateOrCreate(
        ['infant_id' => $id],
        $validated
    );

    return redirect()->back()->with('success', 'Immunization updated successfully!');
}
}
