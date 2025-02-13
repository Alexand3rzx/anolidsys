<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    if (Auth::id()) {
        $usertype = Auth()->user()->usertype;

        if ($usertype == 'user') {
            return view('dashboard');
        } elseif ($usertype == 'admin') {
            // Fetch medicine data
            $medicines = Medicine::select('name', 'stock')->get();

            // Fetch count of beneficiaries
            $pregnantCount = Beneficiary::where('category', 'Pregnant')->count();
            $infantCount = Beneficiary::where('category', 'Infant')->count();

            return view('admin.adminhome', compact('medicines', 'pregnantCount', 'infantCount'));
        } else {
            return redirect()->back();
        }
    }
}

    
}
