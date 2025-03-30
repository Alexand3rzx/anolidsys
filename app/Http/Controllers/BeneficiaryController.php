<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Pregnant;
use App\Models\Infant;

class BeneficiaryController extends Controller
{
  

    public function index(Request $request)
    {
        // Fetch pregnant women with separate pagination
        $pregnantWomen = Pregnant::paginate(8, ['*'], 'pregnant_page');
    
        // Fetch infants with separate pagination
        $infants = Infant::paginate(8, ['*'], 'infant_page');
    
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
