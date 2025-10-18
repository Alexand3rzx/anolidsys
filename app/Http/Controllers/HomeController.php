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
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
    
    /**
 * Mark relevant notifications read for the current user (admin or useradmin).
 * This matches the same filters used when fetching notifications for the dropdown.
 */
public function markAllRead(Request $request): JsonResponse
{
    $user = Auth::user();

    if (! $user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    $usertype = $user->usertype;

    if ($usertype === 'admin') {
        // Admin: mark purok-wide request notifications as read
        Notification::where(function ($q) {
                $q->where('message', 'like', '%requested medicine%')
                  ->orWhere('message', 'like', '%updated a pending request%');
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);
    } elseif ($usertype === 'useradmin') {
        // Useradmin: mark only their personal notifications (approval/completed/rejected) as read
        Notification::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('message', 'like', '%Your request%')
                  ->orWhere('message', 'like', '%Your medicine request%')
                  ->orWhere('message', 'like', '%was rejected%');
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);
    } else {
        // do nothing for other usertypes
    }

    // Return no content or small JSON response
    return response()->json(['status' => 'ok']);
}

public function downloadReport(Request $request)
{
    // Fetch the same data as your dashboard
    
    $totalPregnants = Pregnant::count();
    $totalInfants = Infant::count();

    $pregnantBelow18 = Pregnant::where('prgage', '<', 18)->count();
    $pregnantAbove18 = Pregnant::where('prgage', '>=', 18)->count();

    $infantMale = Infant::where('child_gender', 'Male')->count();
    $infantFemale = Infant::where('child_gender', 'Female')->count();

    $pregnantsByPurok = Pregnant::select('purok', DB::raw('count(*) as total'))
        ->groupBy('purok')->get();
    $infantsByPurok = Infant::select('purok', DB::raw('count(*) as total'))
        ->groupBy('purok')->get();

    $topMedicinesByPurok = DB::table('medicine_requests')
    ->join('users', 'medicine_requests.user_id', '=', 'users.id')
    ->join('medicines', 'medicine_requests.medicine_id', '=', 'medicines.id')
    ->select(
        'users.purok',
        'medicines.name as medicine_name',
        DB::raw('SUM(medicine_requests.quantity) as total_quantity')
    )
    ->groupBy('users.purok', 'medicines.name')
    ->orderBy('users.purok')
    ->get()
    ->groupBy('purok');

    // Generate PDF
    $pdf = Pdf::loadView('reports.admin_report', compact(
        
        'totalPregnants',
        'totalInfants',
        'pregnantBelow18',
        'pregnantAbove18',
        'infantMale',
        'infantFemale',
        'pregnantsByPurok',
        'infantsByPurok',
        'topMedicinesByPurok'
    ))->setPaper('a4', 'portrait');

    return $pdf->download('Admin_Report_' . now()->format('Y-m-d') . '.pdf');
}
    





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
        $userPurok = Auth::user()->purok;

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
            // useradmin → only their purok
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
        // PREGNANT / INFANT COUNTS
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

        $pregnantBelow18 = (clone $pregnantQuery)->where('prgage', '<', 18)->count();
        $pregnantAbove18 = (clone $pregnantQuery)->where('prgage', '>=', 18)->count();

        $infantMale = (clone $infantQuery)->where('child_gender', 'Male')->count();
        $infantFemale = (clone $infantQuery)->where('child_gender', 'Female')->count();

        $pregnantAgeGroups = [
            'Under 18' => $pregnantBelow18,
            '18-24' => (clone $pregnantQuery)->whereBetween('prgage', [18, 24])->count(),
            '25-34' => (clone $pregnantQuery)->whereBetween('prgage', [25, 34])->count(),
            '35+' => (clone $pregnantQuery)->where('prgage', '>=', 35)->count(),
        ];

        // -------------------------------
        // Purok Distributions
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
                ->groupBy('purok')->orderBy('purok')->get();

            $infantsByPurok = Infant::select('purok', DB::raw('COUNT(*) as total'))
                ->groupBy('purok')->orderBy('purok')->get();
        }

        // -------------------------------
        // Notifications
        // -------------------------------
        if ($usertype === 'admin') {
            $notifications = Notification::where(function ($q) {
                    $q->where('message', 'like', '%requested medicine%')
                      ->orWhere('message', 'like', '%updated a pending request%');
                })
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notification::where(function ($q) {
                    $q->where('message', 'like', '%requested medicine%')
                      ->orWhere('message', 'like', '%updated a pending request%');
                })
                ->where('is_read', false)
                ->count();
        } elseif ($usertype === 'useradmin') {
            $notifications = Notification::where('user_id', Auth::id())
                ->where(function ($q) {
                    $q->where('message', 'like', '%Your request%')
                      ->orWhere('message', 'like', '%Your medicine request%')
                      ->orWhere('message', 'like', '%was rejected%');
                })
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->where(function ($q) {
                    $q->where('message', 'like', '%Your request%')
                      ->orWhere('message', 'like', '%Your medicine request%')
                      ->orWhere('message', 'like', '%was rejected%');
                })
                ->count();
        } else {
            $notifications = collect();
            $unreadCount = 0;
        }

        // -------------------------------
        // Top Requested Medicines (Completed Requests Only)
        // -------------------------------
        $topRequestedMedicines = MedicineRequest::select(
                'medicines.name',
                DB::raw('SUM(medicine_requests.quantity) as total_quantity')
            )
            ->join('medicines', 'medicine_requests.medicine_id', '=', 'medicines.id')
            ->whereMonth('medicine_requests.created_at', Carbon::now()->month)
            ->whereYear('medicine_requests.created_at', Carbon::now()->year)
            ->where('medicine_requests.status', 'completed');

        if ($usertype === 'useradmin') {
            $topRequestedMedicines->where('medicines.purok', $userPurok);
        }

        $topRequestedMedicines = $topRequestedMedicines
            ->groupBy('medicines.name')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // -------------------------------
        // Top Medicines by Purok (Completed Only)
        // -------------------------------
        $topMedicinesByPurok = MedicineRequest::select(
                'users.purok',
                'medicines.name',
                DB::raw('SUM(medicine_requests.quantity) as total_quantity')
            )
            ->join('medicines', 'medicine_requests.medicine_id', '=', 'medicines.id')
            ->join('users', 'medicine_requests.user_id', '=', 'users.id')
            ->whereMonth('medicine_requests.created_at', Carbon::now()->month)
            ->whereYear('medicine_requests.created_at', Carbon::now()->year)
            ->where('medicine_requests.status', 'completed')
            ->groupBy('users.purok', 'medicines.name')
            ->orderBy('users.purok')
            ->orderByDesc('total_quantity')
            ->get()
            ->groupBy('purok');

            // -------------------------------
// Pregnant Monthly Check-ins (for chart)
// -------------------------------
$pregnantMonthlyCounts = Pregnant::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('COUNT(*) as total')
    )
    ->when($usertype === 'useradmin', fn($q) => $q->where('purok', $userPurok))
    ->whereYear('created_at', now()->year)
    ->groupBy('month')
    ->orderBy('month')
    ->get()
    ->mapWithKeys(fn($row) => [date('F', mktime(0, 0, 0, $row->month, 1)) => $row->total]);

// Get current month's count for quick display
$pregnantsThisMonth = Pregnant::when($usertype === 'useradmin', fn($q) => $q->where('purok', $userPurok))
    ->whereMonth('created_at', now()->month)
    ->count();


        // -------------------------------
// 💡 Smart Insights Section (Scoped by Usertype)
// -------------------------------
$insights = [];

// Determine scoping
$isUserAdmin = ($usertype === 'useradmin');
$targetPurok = $isUserAdmin ? $userPurok : null;

// 👶 Top Purok for infants (or their own purok summary)
if ($isUserAdmin) {
    $infantsByPurok = Infant::select('purok', DB::raw('COUNT(*) as total'))
        ->where('purok', $userPurok)
        ->groupBy('purok')
        ->first();
    $infantTotal = Infant::where('purok', $userPurok)->count();

    if ($infantsByPurok && $infantTotal > 0) {
        $insights[] = "👶 Your Purok ({$userPurok}) currently has {$infantsByPurok->total} infant registrations.";
    }
} else {
    $infantsByPurokAll = Infant::select('purok', DB::raw('COUNT(*) as total'))
        ->groupBy('purok')
        ->get();
    $totalInfantsAll = $infantsByPurokAll->sum('total');
    $topPurok = $infantsByPurokAll->sortByDesc('total')->first();

    if ($topPurok && $totalInfantsAll > 0) {
        $percent = round(($topPurok->total / $totalInfantsAll) * 100, 1);
        $insights[] = "👶 Purok {$topPurok->purok} has the highest infant registrations ({$percent}% of total).";
    }
}

// 📈 Monthly change in infants
$infantsThisMonth = Infant::when($isUserAdmin, fn($q) => $q->where('purok', $userPurok))
    ->whereMonth('created_at', now()->month)
    ->count();
$infantsLastMonth = Infant::when($isUserAdmin, fn($q) => $q->where('purok', $userPurok))
    ->whereMonth('created_at', now()->subMonth()->month)
    ->count();

if ($infantsLastMonth > 0) {
    $change = round((($infantsThisMonth - $infantsLastMonth) / $infantsLastMonth) * 100, 1);
    if ($change > 0) {
        $insights[] = $isUserAdmin
            ? "📈 Infant registrations in your purok increased by {$change}% this month."
            : "📈 Infant registrations increased by {$change}% this month.";
    } elseif ($change < 0) {
        $insights[] = $isUserAdmin
            ? "📉 Infant registrations in your purok decreased by " . abs($change) . "% this month."
            : "📉 Infant registrations decreased by " . abs($change) . "% this month.";
    }
}

// 💊 Detect low or dropped medicine stocks
$lowStockQuery = Medicine::when($isUserAdmin, fn($q) => $q->where('purok', $userPurok));
$lowStockCount = (clone $lowStockQuery)->where('stock', '<', 20)->count();

if ($lowStockCount > 0) {
    $scopeText = $isUserAdmin ? "in your purok" : "across all puroks";
    $insights[] = "⚠️ There are {$lowStockCount} medicines with low stock levels {$scopeText}.";
}

$recentLow = (clone $lowStockQuery)->orderBy('stock', 'asc')->first();
if ($recentLow && $recentLow->stock < 10) {
    $scopeText = $isUserAdmin ? "in your purok" : "";
 $insights[] = "🧠 Insight: {$recentLow->name} supply {$scopeText} is nearly depleted ({$recentLow->stock} left). The system predicts a shortage soon.";
}

// 🤰 Pregnant count changes
$pregnantsThisMonth = Pregnant::when($isUserAdmin, fn($q) => $q->where('purok', $userPurok))
    ->whereMonth('created_at', now()->month)
    ->count();
$pregnantsLastMonth = Pregnant::when($isUserAdmin, fn($q) => $q->where('purok', $userPurok))
    ->whereMonth('created_at', now()->subMonth()->month)
    ->count();

if ($pregnantsLastMonth > 0) {
    $change = round((($pregnantsThisMonth - $pregnantsLastMonth) / $pregnantsLastMonth) * 100, 1);
    if ($change > 0) {
        $insights[] = $isUserAdmin
            ? "🤰 Pregnant registrations in your purok increased by {$change}% this month."
            : "🤰 Pregnant registrations increased by {$change}% this month.";
    } elseif ($change < 0) {
        $insights[] = $isUserAdmin
            ? "🤰 Pregnant registrations in your purok decreased by " . abs($change) . "% this month."
            : "🤰 Pregnant registrations decreased by " . abs($change) . "% this month.";
    }
}
        // -------------------------------
        // Return all data
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
            'topRequestedMedicines',
            'topMedicinesByPurok',
            'unreadCount',
            'insights', // 👈 new variable for the AI insights panel
             'pregnantMonthlyCounts', // ✅ added
    'pregnantsThisMonth'     // ✅ added
        ));
    }

    // -------------------------------
    // Fallback for Beneficiary Dashboards
    // -------------------------------
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
