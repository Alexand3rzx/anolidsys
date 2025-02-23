<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Pregnant;
use App\Models\Infant;

class BeneficiaryController extends Controller
{
  


public function storePregnant(Request $request)
{
    $request->validate([
        'Fname' => 'required',
        'Lname' => 'required',
        'birthday' => 'required|date',
        'age' => 'required|numeric',
        'months_pregnant' => 'required|numeric',
        'due_date' => 'required|date',
        'address' => 'required|string',
        'occupation' => 'nullable|string',
        'educational_attainment' => 'nullable|string',
        'religion' => 'nullable|string',
        'mothers_name' => 'nullable|string',
        'partner_name' => 'nullable|string',
        'partner_age' => 'nullable|numeric',
        'partner_bday' => 'nullable|date',
        'partner_occupation' => 'nullable|string',
        'partner_eduattain' => 'nullable|string',
        'partner_religion' => 'nullable|string',
    ]);

    Beneficiary::create([
        'Fname' => $request->Fname,
        'Lname' => $request->Lname,
        'category' => 'Pregnant',
        'birthday' => $request->birthday,
        'age' => $request->age,
        'months_pregnant' => $request->months_pregnant,
        'due_date' => $request->due_date,
        'address' => $request->address,
        'occupation' => $request->occupation,
        'educational_attainment' => $request->input('educational_attainment'), // Explicitly set
        'religion' => $request->religion,
        'mothers_name' => $request->mothers_name,
        'partner_name' => $request->partner_name,
        'partner_age' => $request->partner_age,
        'partner_bday' => $request->partner_bday,
        'partner_occupation' => $request->partner_occupation,
        'partner_eduattain' => $request->input('partner_eduattain'), // Explicitly set
        'partner_religion' => $request->partner_religion,
    ]);
    return redirect()->route('beneficiaries.index');
}

public function update(Request $request, $id)
{
    $request->validate([
        'Fname' => 'required|string|max:255',
        'Lname' => 'required|string|max:255',
        'birthday' => 'required|date',
        'age' => 'required|numeric',
        'months_pregnant' => 'nullable|numeric',
        'due_date' => 'nullable|date',
        'address' => 'required|string|max:255',
        'occupation' => 'nullable|string|max:255',
        'educational_attainment' => 'nullable|string|max:255',
        'religion' => 'nullable|string|max:255',
        'mothers_name' => 'nullable|string|max:255',
        'partner_name' => 'nullable|string|max:255',
        'partner_age' => 'nullable|numeric',
        'partner_bday' => 'nullable|date',
        'partner_occupation' => 'nullable|string|max:255',
        'partner_eduattain' => 'nullable|string|max:255',
        'partner_religion' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:20',
    ]);

    $beneficiary = Beneficiary::findOrFail($id);
    $beneficiary->update([
        'Fname' => $request->Fname,
        'Lname' => $request->Lname,
        'birthday' => $request->birthday,
        'age' => $request->age,
        'months_pregnant' => $request->months_pregnant,
        'due_date' => $request->due_date,
        'address' => $request->address,
        'occupation' => $request->occupation,
        'educational_attainment' => $request->educational_attainment,
        'religion' => $request->religion,
        'mothers_name' => $request->mothers_name,
        'partner_name' => $request->partner_name,
        'partner_age' => $request->partner_age,
        'partner_bday' => $request->partner_bday,
        'partner_occupation' => $request->partner_occupation,
        'partner_eduattain' => $request->partner_eduattain,
        'partner_religion' => $request->partner_religion,
        'contact_number' => $request->contact_number,
    ]);

    return redirect()->route('beneficiaries.index')->with('success', 'Beneficiary updated successfully!');
}




 // Store Infant Beneficiary
 public function storeInfant(Request $request)
{
    $request->validate([
        'Fname' => 'required',
        'Lname' => 'required',
        'birthday' => 'required|date',
        'age' => 'required|numeric',
        'weight' => 'required|numeric',
        'height' => 'required|numeric',
    ]);

    Beneficiary::create([
        'Fname' => $request->Fname,
        'Lname' => $request->Lname,
        'category' => 'Infant',
        'birthday' => $request->birthday,
        'age' => $request->age,
        'weight' => $request->weight,
        'height' => $request->height,
    ]);

    return redirect()->route('beneficiaries.index');
}

public function destroy($id)
{
    Beneficiary::findOrFail($id)->delete();
    return response()->json(['success' => true]);
}

public function index()
{
    // Fetch pregnant women data
    $pregnantWomen = Pregnant::all();

    // Fetch infants data
    $infants = Infant::all();

    // Pass both variables to the view
    return view('beneficiaries.index', compact('pregnantWomen', 'infants'));
}


}
