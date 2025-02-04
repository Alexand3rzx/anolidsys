<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');

    $beneficiaries = Beneficiary::when($search, function ($query, $search) {
        return $query->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('category', 'LIKE', "%{$search}%");
    })->when($category, function ($query, $category) {
        return $query->where('category', $category);
    })->get();

    return view('beneficiaries.index', compact('beneficiaries', 'search', 'category'));
}

 // Store Pregnant Beneficiary
 public function storePregnant(Request $request)
 {
     $request->validate([
         'name' => 'required',
         'birthday' => 'required|date',
         'age' => 'required|numeric',
         'months_pregnant' => 'required|numeric',
         'due_date' => 'required|date',
     ]);

     Beneficiary::create([
         'name' => $request->name,
         'category' => 'Pregnant',
         'birthday' => $request->birthday,
         'age' => $request->age,
         'months_pregnant' => $request->months_pregnant,
         'due_date' => $request->due_date,
     ]);

     return redirect()->route('beneficiaries.index');
 }

 // Store Infant Beneficiary
 public function storeInfant(Request $request)
 {
     $request->validate([
         'name' => 'required',
         'birthday' => 'required|date',
         'age' => 'required|numeric',
         'weight' => 'required|numeric',
         'height' => 'required|numeric',
     ]);

     Beneficiary::create([
         'name' => $request->name,
         'category' => 'Infant',
         'birthday' => $request->birthday,
         'age' => $request->age,
         'weight' => $request->weight,
         'height' => $request->height,
     ]);

     return redirect()->route('beneficiaries.index');
 }


}
