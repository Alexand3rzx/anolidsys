<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Beneficiary;
use App\Models\Pregnant;
use App\Models\Infant;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
   public function index()
{
    if (Auth::id()) {
        $usertype = Auth()->user()->usertype;

        if ($usertype == 'user') {
            return view('dashboard');
        } elseif ($usertype == 'admin' || $usertype == 'useradmin') {
            
            // Fetch medicine data depending on role
            if ($usertype == 'admin') {
                $medicines = Medicine::select('name', 'stock', 'purok')->get();
            } elseif ($usertype == 'useradmin') {
                $medicines = Medicine::where('purok', Auth::user()->purok)
                                     ->select('name', 'stock', 'purok')
                                     ->get();
            }

            // Fetch pregnant statistics
            $pregnantBelow18 = Pregnant::where('prgage', '<', 18)->count();
            $pregnantAbove18 = Pregnant::where('prgage', '>=', 18)->count();

            // Fetch infant gender statistics
            $infantMale = Infant::where('child_gender', 'Male')->count();
            $infantFemale = Infant::where('child_gender', 'Female')->count();

            return view('admin.adminhome', compact(
                'medicines', 
                'pregnantBelow18', 
                'pregnantAbove18', 
                'infantMale', 
                'infantFemale'
            ));
        } else {
            // For normal users
            $user = Auth::user();
            switch ($user->beneficiary_type) {
                case 'pregnant':
                    return view('user.pregnant_dashboard');
                case 'senior':
                    return view('user.senior_dashboard');
                case 'normal':
                    return view('user.normal_dashboard');
                default:
                    return view('user.home'); // fallback
            }
        }
    }
}



    
}
