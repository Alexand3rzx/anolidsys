<?php

namespace App\Http\Controllers;

use App\Models\Pregnant;
use Illuminate\Http\Request;

class PregnantController extends Controller
{
    // Store a new pregnant woman
    public function store(Request $request)
    {
        $request->validate([
            'prgname' => 'required|string|max:255',
            'prgage' => 'required|integer',
            'prgbday' => 'required|date',
            'prgaddress' => 'required|string|max:255',
            'prgoccupation' => 'nullable|string|max:255',
            'prgreligion' => 'nullable|string|max:255',
            'prgmother_name' => 'nullable|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'partner_age' => 'nullable|integer',
            'partner_bday' => 'nullable|date',
            'partner_occupation' => 'nullable|string|max:255',
            'partner_religion' => 'nullable|string|max:255',
            'partner_number' => 'nullable|string|max:15',
        ]);

        Pregnant::create($request->all());

        return back()->with('success', 'Pregnant woman added successfully');
    }

// Show a single record for editing
public function edit($id)
{
    $woman = Pregnant::findOrFail($id);
    return response()->json($woman);
}

// Update the record
public function update(Request $request, $id)
{
    $request->validate([
        'prgname' => 'required|string|max:255',
        'prgage' => 'required|integer',
        'prgbday' => 'required|date',
        'prgaddress' => 'required|string|max:255',
        'prgoccupation' => 'nullable|string|max:255',
        'prgreligion' => 'nullable|string|max:255',
        'prgmother_name' => 'nullable|string|max:255',
        'partner_name' => 'nullable|string|max:255',
        'partner_age' => 'nullable|integer',
        'partner_bday' => 'nullable|date',
        'partner_occupation' => 'nullable|string|max:255',
        'partner_religion' => 'nullable|string|max:255',
        'partner_number' => 'nullable|string|max:15',
    ]);

    $pregnantWoman = Pregnant::findOrFail($id);
    $pregnantWoman->update($request->all());

    return redirect()->route('beneficiaries.index')->with('success', 'Record updated successfully!');
}

// Delete the record
public function destroy($id)
{
    $pregnantWoman = Pregnant::findOrFail($id);
    $pregnantWoman->delete();

    return redirect()->route('beneficiaries.index')->with('success', 'Record deleted successfully!');
}
}


