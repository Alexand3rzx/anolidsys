<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Pregnant;
use App\Models\Infant;

class BeneficiaryController extends Controller
{
  

public function index()
{
    // Fetch pregnant women data with pagination (8 per page)
    $pregnantWomen = Pregnant::paginate(8);

    // Fetch paginated infants (8 per page)
    $infants = Infant::paginate(8);
    // Pass both variables to the view
    // Count pregnant beneficiaries by age
    $pregnantBelow18 = Pregnant::where('prgage', '<', 18)->count();
    $pregnantAbove18 = Pregnant::where('prgage', '>=', 18)->count();

    // Count infants by gender
    $infantMale = Infant::where('child_gender', 'Male')->count();
    $infantFemale = Infant::where('child_gender', 'Female')->count();

    return view('beneficiaries.index', compact(
        'pregnantWomen',
        'infants',
        'pregnantBelow18',
        'pregnantAbove18',
        'infantMale',
        'infantFemale'
    ));
}

public function searchPregnant(Request $request)
{
    $query = $request->input('query');
    
    $pregnantWomen = Pregnant::where('prgname', 'LIKE', "%{$query}%")
        ->orWhere('prgage', 'LIKE', "%{$query}%")
        ->orWhere('prgaddress', 'LIKE', "%{$query}%")
        ->get();

    return response()->json($pregnantWomen);
}

}
