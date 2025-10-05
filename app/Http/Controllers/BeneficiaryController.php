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
        // Old combined page (if you want to keep it for reference)
        $pregnantWomen = Pregnant::paginate(8, ['*'], 'pregnant_page');
        $infants = Infant::paginate(8, ['*'], 'infant_page');

        // Counts (optional if you no longer need them in separate views)
        $pregnantBelow18 = Pregnant::where('prgage', '<', 18)->count();
        $pregnantAbove18 = Pregnant::where('prgage', '>=', 18)->count();
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

    /**
     * Show Pregnant Women list page
     */
    public function pregnants(Request $request)
    {
        $pregnantWomen = Pregnant::paginate(8);

        return view('beneficiaries.pregnants', compact('pregnantWomen'));
    }

    /**
     * Show Infants list page
     */
    public function infants(Request $request)
    {
        $infants = Infant::paginate(8);

        return view('beneficiaries.infants', compact('infants'));
    }

    /**
     * AJAX Search Pregnant
     */
    public function searchPregnant(Request $request)
    {
        $query = $request->input('query');

        $pregnantWomen = Pregnant::where(function ($q) use ($query) {
            $q->where('prgname', 'LIKE', "%{$query}%")
              ->orWhere('prgage', 'LIKE', "%{$query}%")
              ->orWhere('prgaddress', 'LIKE', "%{$query}%");
        })->get();

        return response()->json($pregnantWomen);
    }

    /**
     * AJAX Search Infant
     */
    public function searchInfant(Request $request)
    {
        $query = $request->input('query');

        $infants = Infant::where(function ($q) use ($query) {
            $q->where('child_name', 'LIKE', "%{$query}%")
              ->orWhere('child_gender', 'LIKE', "%{$query}%")
              ->orWhere('child_mother', 'LIKE', "%{$query}%")
              ->orWhere('child_father', 'LIKE', "%{$query}%");
        })->get();

        return response()->json($infants);
    }
}