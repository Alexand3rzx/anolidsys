<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    $userId = auth()->id();
    $medicineId = $request->medicine_id;

    // fetch medicine name
    $medicineName = \App\Models\Medicine::find($medicineId)->name ?? 'Unknown Medicine';

    // get the latest request for this user & medicine
    $existingRequest = MedicineRequest::where('user_id', $userId)
                                      ->where('medicine_id', $medicineId)
                                      ->latest()
                                      ->first();

    if ($existingRequest && $existingRequest->status === 'pending') {
        // if still pending → just add quantity
        $existingRequest->quantity += $request->quantity;
        $existingRequest->save();

        // ✅ log/update notification
        Notification::create([
            'user_id' => $userId,
            'message' => auth()->user()->purok . " updated request for {$medicineName}",
        ]);

        return back()->with('success', 'Request updated successfully (added to existing pending).');
    } else {
        // otherwise, create a new pending request
        MedicineRequest::create([
            'user_id' => $userId,
            'medicine_id' => $medicineId,
            'quantity' => $request->quantity,
            'status' => 'pending',
        ]);

        // ✅ new notification
        Notification::create([
            'user_id' => $userId,
            'message' => auth()->user()->purok . " requested medicine: {$medicineName}",
        ]);

        return back()->with('success', 'New request created successfully.');
    }
}





    /**
     * Approve a request (Admin side).
     * Deducts from adminpurok and transfers to requesting purok.
     */
    public function approve($id)
{
    $request = MedicineRequest::findOrFail($id);
    $medicine = Medicine::findOrFail($request->medicine_id);

    // check if enough stock exists
    if ($medicine->stock < $request->quantity) {
        return back()->with('error', 'Not enough stock available.');
    }

    // deduct from adminpurok
    $medicine->stock -= $request->quantity;
    $medicine->save();

    // add stock to requesting user's purok
    $requestingUser = $request->useradmin; // relationship from MedicineRequest model
    Medicine::create([
        'name'       => $medicine->name,
        'details'    => $medicine->details,
        'stock'      => $request->quantity,
        'expiration' => $medicine->expiration,
        'purok'      => $requestingUser->purok, // ✅ purok of requester
    ]);

    // mark request as approved
    $request->status = 'approved';
    $request->save();

    return back()->with('success', 'Medicine request approved and stock transferred.');
}


    /**
     * Reject a request (Admin side).
     */
    public function reject($id)
    {
        $request = MedicineRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        return back()->with('success', 'Medicine request rejected.');
    }

public function list(Request $request)
{
    $search = $request->get('search');

    $medicines = Medicine::where('purok', 'adminpurok')
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%");
        })
        ->orderBy('name', 'asc')
        ->paginate(6);

    // return partial view for ajax
    if ($request->ajax()) {
        return view('partials.medicine_request_table', compact('medicines'))->render();
    }

    return view('medicines.index', compact('medicines'));
}

public function create(Request $request)
{
    $query = Medicine::where('purok', 'adminpurok')
        ->select(
            'name',
            'details',
            'expiration',
            DB::raw('SUM(stock) as stock')
        )
        ->groupBy('name', 'details', 'expiration');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('details', 'like', "%{$request->search}%");
        });
    }

    $adminMedicines = $query->get();

    return view('medicines.request', compact('adminMedicines'));
}

public function adminIndex()
{
    // ✅ Mark all unread notifications as read
    \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);

    $requests = MedicineRequest::with('useradmin', 'medicine')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

    return view('medicines.requests_admin', compact('requests'));
}



}
