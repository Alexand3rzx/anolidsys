<?php

namespace App\Http\Controllers;

use App\Models\Pregnant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'purok' => 'required|string|max:255',
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
            'prgreligion' => 'required|string|max:255',
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

        return redirect()->route('beneficiaries.pregnants')->with('success', 'Record updated successfully!');
    }

    // Delete the record
    public function destroy($id)
    {
        $pregnantWoman = Pregnant::findOrFail($id);
        $pregnantWoman->delete();

        return redirect()->route('beneficiaries.index')->with('success', 'Record deleted successfully!');
    }

    // Show a single record
    public function show($id)
    {
        $woman = Pregnant::findOrFail($id);
        return view('beneficiaries.pregnant_show', compact('woman'));
    }

    // Pregnants list
    public function index()
    {
        $user = Auth::user();

        if ($user->usertype === 'admin') {
            // Admin sees all records
            $pregnantWomen = Pregnant::paginate(10);
        } elseif ($user->usertype === 'useradmin') {
            // Useradmin sees only their purok
            $pregnantWomen = Pregnant::where('purok', $user->purok)->paginate(10);
        } else {
            // fallback (normal users see nothing or handle differently)
            $pregnantWomen = collect();
        }

        return view('beneficiaries.pregnants', compact('pregnantWomen'));
    }
}
