<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Beneficiary;
use App\Models\Pregnant;
use App\Models\Infant;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
 



public function index(Request $request)
{
    if (Auth::id()) {
        $usertype = Auth()->user()->usertype;

        if ($usertype == 'user') {
            return view('dashboard');
        } elseif ($usertype == 'admin' || $usertype == 'useradmin') {
            
            $selectedPurok = $request->get('purok');

            if ($usertype == 'admin') {
                $query = Medicine::select(
                        DB::raw('MIN(id) as id'),
                        'name',
                        'purok',
                        DB::raw('SUM(stock) as stock')
                    )
                    ->groupBy('name', 'purok')
                    ->orderBy('name', 'asc');

                if ($selectedPurok) {
                    $query->where('purok', $selectedPurok);
                }

                $medicines = $query->get();

                // Get list of distinct puroks for dropdown
                $puroks = Medicine::distinct()->pluck('purok');
            } else {
                // useradmin → only their purok
                $medicines = Medicine::where('purok', Auth::user()->purok)
                    ->select(
                        DB::raw('MIN(id) as id'),
                        'name',
                        'purok',
                        DB::raw('SUM(stock) as stock')
                    )
                    ->groupBy('name', 'purok')
                    ->orderBy('name', 'asc')
                    ->get();

                $puroks = collect([Auth::user()->purok]); // just their purok
                $selectedPurok = Auth::user()->purok;
            }

            // ✅ Pregnant statistics
            $pregnantBelow18 = Pregnant::where('prgage', '<', 18)->count();
            $pregnantAbove18 = Pregnant::where('prgage', '>=', 18)->count();

            // ✅ Infant gender statistics
            $infantMale = Infant::where('child_gender', 'Male')->count();
            $infantFemale = Infant::where('child_gender', 'Female')->count();

            // ✅ Notifications (latest 10)
            $notifications = \App\Models\Notification::latest()->take(10)->get();

            

            return view('admin.adminhome', compact(
                'medicines', 
                'puroks',
                'selectedPurok',
                'pregnantBelow18', 
                'pregnantAbove18', 
                'infantMale', 
                'infantFemale',
                'notifications',
                
            ));
        } else {
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
