<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Pregnant;
use App\Models\Infant;
use App\Models\MedicineRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
{
    if (! Auth::id()) {
        return redirect()->route('login');
    }

    $usertype = Auth::user()->usertype;

    // Regular user view
    if ($usertype === 'user') {
        return view('dashboard');
    }

    // Admin / Useradmin view
    if ($usertype === 'admin' || $usertype === 'useradmin') {
        $selectedPurok = $request->get('purok');
        $userPurok = Auth::user()->purok; // ⭐

        // -------------------------------
        // MEDICINE INVENTORY
        // -------------------------------
        if ($usertype === 'admin') {
            $query = Medicine::select(
                    DB::raw('MIN(id) as id'),
                    'name',
                    'purok',
                    DB::raw('SUM(stock) as stock'),
                    'expiration'
                )
                ->groupBy('name', 'purok', 'expiration')
                ->orderBy('name', 'asc');

            if ($selectedPurok) {
                $query->where('purok', $selectedPurok);
            }

            $medicines = $query->get();
            $puroks = Medicine::distinct()->pluck('purok');
        } else {
            // useradmin -> only their purok
            $medicines = Medicine::where('purok', $userPurok)
                ->select(
                    DB::raw('MIN(id) as id'),
                    'name',
                    'purok',
                    DB::raw('SUM(stock) as stock'),
                    'expiration'
                )
                ->groupBy('name', 'purok', 'expiration')
                ->orderBy('name', 'asc')
                ->get();

            $puroks = collect([$userPurok]);
            $selectedPurok = $userPurok;
        }

        // -------------------------------
        // PREGNANT / INFANT COUNTS ⭐
        // -------------------------------
        $pregnantQuery = Pregnant::query();
        $infantQuery = Infant::query();

        if ($usertype === 'useradmin') {
            $pregnantQuery->where('purok', $userPurok);
            $infantQuery->where('purok', $userPurok);
        }

        $totalPregnants = $pregnantQuery->count();
        $totalInfants = $infantQuery->count();
        $totalBeneficiaries = $totalPregnants + $totalInfants;

        // Pregnant age split
        $pregnantBelow18 = (clone $pregnantQuery)->where('prgage', '<', 18)->count();
        $pregnantAbove18 = (clone $pregnantQuery)->where('prgage', '>=', 18)->count();

        // Infant gender counts
        $infantMale = (clone $infantQuery)->where('child_gender', 'Male')->count();
        $infantFemale = (clone $infantQuery)->where('child_gender', 'Female')->count();

        // Pregnant age buckets
        $pregnantAgeGroups = [
            'Under 18' => $pregnantBelow18,
            '18-24' => (clone $pregnantQuery)->whereBetween('prgage', [18, 24])->count(),
            '25-34' => (clone $pregnantQuery)->whereBetween('prgage', [25, 34])->count(),
            '35+' => (clone $pregnantQuery)->where('prgage', '>=', 35)->count(),
        ];

        // -------------------------------
        // Purok distributions ⭐
        // -------------------------------
        if ($usertype === 'useradmin') {
            $pregnantsByPurok = Pregnant::select('purok', DB::raw('COUNT(*) as total'))
                ->where('purok', $userPurok)
                ->groupBy('purok')
                ->get();

            $infantsByPurok = Infant::select('purok', DB::raw('COUNT(*) as total'))
                ->where('purok', $userPurok)
                ->groupBy('purok')
                ->get();
        } else {
            $pregnantsByPurok = Pregnant::select('purok', DB::raw('COUNT(*) as total'))
                ->groupBy('purok')
                ->orderBy('purok')
                ->get();

            $infantsByPurok = Infant::select('purok', DB::raw('COUNT(*) as total'))
                ->groupBy('purok')
                ->orderBy('purok')
                ->get();
        }

        // -------------------------------
        // Notifications (latest 10)
        // -------------------------------
        $notifications = Notification::latest()->take(10)->get();

        // -------------------------------
        // Top Requested Medicines (filter by purok) ⭐
        // -------------------------------
        $topRequestedMedicines = MedicineRequest::select(
                'medicines.name',
                DB::raw('SUM(medicine_requests.quantity) as total_quantity')
            )
            ->join('medicines', 'medicine_requests.medicine_id', '=', 'medicines.id')
            ->whereMonth('medicine_requests.created_at', Carbon::now()->month)
            ->whereYear('medicine_requests.created_at', Carbon::now()->year)
            ->where('medicine_requests.status', 'approved');

        if ($usertype === 'useradmin') {
            $topRequestedMedicines->where('medicines.purok', $userPurok);
        }

        $topRequestedMedicines = $topRequestedMedicines
            ->groupBy('medicines.name')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // -------------------------------
        // Pass everything to view
        // -------------------------------
        return view('admin.adminhome', compact(
            'medicines',
            'puroks',
            'selectedPurok',
            'totalPregnants',
            'totalInfants',
            'totalBeneficiaries',
            'pregnantBelow18',
            'pregnantAbove18',
            'infantMale',
            'infantFemale',
            'pregnantAgeGroups',
            'pregnantsByPurok',
            'infantsByPurok',
            'notifications',
            'topRequestedMedicines'
        ));
    }

    // Fallback for beneficiary dashboards
    $user = Auth::user();
    switch ($user->beneficiary_type ?? null) {
        case 'pregnant':
            return view('user.pregnant_dashboard');
        case 'senior':
            return view('user.senior_dashboard');
        case 'normal':
            return view('user.normal_dashboard');
        default:
            return view('user.home');
    }
}
}
