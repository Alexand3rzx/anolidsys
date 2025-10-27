<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MedicineBatch;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MedicineRequestController extends Controller
{
    /**
     * Store a new medicine request (Useradmin side).
     */
public function store(Request $request)
{
    $request->validate([
        'medicine_id' => 'required|exists:medicines,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    $medicine = Medicine::findOrFail($request->medicine_id);

    // 🧾 Check existing pending request
    $existingRequest = MedicineRequest::where('user_id', $user->id)
        ->where('medicine_id', $medicine->id)
        ->where('status', 'pending')
        ->first();

    if ($existingRequest) {
        $existingRequest->quantity += $request->quantity;
        $existingRequest->save();

        Notification::create([
            'user_id' => $user->id,
            'target_role' => 'admin',
            'message' => "Purok {$user->purok} updated a pending request for {$medicine->name}.",
        ]);

        return back()->with('success', 'Updated existing pending request.');
    }

    // 💾 Create new request (no batch involved)
    MedicineRequest::create([
        'user_id' => $user->id,
        'medicine_id' => $medicine->id,
        'quantity' => $request->quantity,
        'status' => 'pending',
    ]);

    Notification::create([
        'user_id' => $user->id,
        'target_role' => 'admin',
        'message' => "Purok {$user->purok} requested {$medicine->name}.",
    ]);

    return back()->with('success', 'Medicine request sent successfully.');
}
    /**
     * Admin approves a request (with pickup code & date)
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
        ]);

        $medRequest = MedicineRequest::findOrFail($id);
        $medicine = Medicine::findOrFail($medRequest->medicine_id);

        if ($medicine->stock < $medRequest->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        $pickupCode = 'RX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        $medRequest->update([
            'status' => 'approved',
            'pickup_code' => $pickupCode,
            'pickup_date' => $request->pickup_date,
        ]);

        // Notify useradmin of approval
        Notification::create([
            'user_id' => $medRequest->user_id,
            'target_role' => 'useradmin',
            'message' => "Your request for {$medicine->name} is approved. 
                          Pickup on {$request->pickup_date} with code: {$pickupCode}.",
        ]);

        return back()->with('success', 'Request approved and pickup details assigned.');
    }

    /**
     * Confirm pickup — Admin verifies code, completes transaction, and transfers stock.
     */
    public function confirmPickup(Request $request)
    {
        $request->validate([
            'pickup_code' => 'required|string',
        ]);

        $medRequest = MedicineRequest::where('pickup_code', $request->pickup_code)
            ->where('status', 'approved')
            ->first();

        if (!$medRequest) {
            return back()->with('error', 'Invalid or already used pickup code.');
        }

        $medicine = Medicine::findOrFail($medRequest->medicine_id);

        if ($medicine->stock < $medRequest->quantity) {
            return back()->with('error', 'Insufficient stock at pickup.');
        }

        // Deduct stock from admin
        $medicine->stock -= $medRequest->quantity;
        $medicine->save();

        // Add stock to requesting purok
        $user = $medRequest->useradmin;
        Medicine::create([
            'name' => $medicine->name,
            'details' => $medicine->details,
            'stock' => $medRequest->quantity,
            'expiration' => $medicine->expiration,
            'purok' => $user->purok,
        ]);

        $medRequest->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        // Notify useradmin that it's completed
        Notification::create([
            'user_id' => $medRequest->user_id,
            'target_role' => 'useradmin',
            'message' => "Your medicine request for {$medicine->name} is completed and added to Purok {$user->purok}.",
        ]);

        return back()->with('success', 'Pickup confirmed and stock transferred.');
    }

    /**
     * Reject a request (Admin side)
     */
    public function reject($id)
    {
        $req = MedicineRequest::findOrFail($id);
        $req->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $req->user_id,
            'target_role' => 'useradmin',
            'message' => "Your medicine request for {$req->medicine->name} was rejected.",
        ]);

        return back()->with('success', 'Request rejected.');
    }

    /**
     * User view — show available admin medicines & my requests
     */
    public function requestPage(Request $request)
{
    $query = DB::table('medicine_batch_medicine as mbm')
        ->join('medicines as m', 'mbm.medicine_id', '=', 'm.id')
        ->join('medicine_batches as b', 'mbm.batch_id', '=', 'b.id')
        ->where('m.purok', 'adminpurok')
        ->select(
            'm.id as medicine_id',
            'm.name',
            'm.details',
            'b.id as batch_id',
            'b.batch_number',
            'm.stock', // ✅ stock now fetched from medicines table
            'm.expiration' // ✅ expiration from medicines
        );

    if ($request->filled('search')) {
        $query->where('m.name', 'like', "%{$request->search}%");
    }

    $adminMedicines = $query->orderBy('m.name')->get();

    $myRequests = MedicineRequest::with('medicine')
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('medicines.request', compact('adminMedicines', 'myRequests'));
}
    /**
     * Admin view — see all requests
     */
    public function adminIndex()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        $requests = MedicineRequest::with('useradmin', 'medicine')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('medicines.requests_admin', compact('requests'));
    }
}
