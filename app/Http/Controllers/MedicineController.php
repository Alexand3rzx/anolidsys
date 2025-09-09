<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{


public function index(Request $request)
{
    $search = $request->input('search');
    $user = auth()->user();

    $query = Medicine::query();

    if ($user->usertype === 'admin') {
        // Admin sees all medicines
    } elseif ($user->usertype === 'useradmin') {
        // Useradmin only sees medicines in their own purok
        $query->where('purok', $user->purok)
              ->where('purok', '!=', 'adminpurok'); // exclude adminpurok
    } else {
        abort(403, 'Unauthorized');
    }

    // ✅ Select grouped fields
    $query->select(
        DB::raw('MIN(id) as id'),   // one ID to keep actions working
        'name',
        'details',
        'expiration',
        DB::raw('SUM(stock) as stock')
    )
    ->groupBy('name', 'details', 'expiration');

    // ✅ Search filter
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%");
        });
    }

    // ✅ Grouped medicines
    $medicines = $query->orderBy('name', 'asc')
                       ->paginate(6)
                       ->appends(['search' => $search]);

    // ✅ Separate list of admin medicines for requests
    $adminMedicines = collect();
    if ($user->usertype === 'useradmin') {
        $adminMedicines = Medicine::where('purok', 'adminpurok')
            ->select(
                DB::raw('MIN(id) as id'),
                'name',
                'details',
                'expiration',
                DB::raw('SUM(stock) as stock')
            )
            ->groupBy('name', 'details', 'expiration')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('details', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(6)
            ->appends(['search' => $search]);
    }

    return view('medicines.index', compact('medicines', 'adminMedicines'));
}



    public function create()
    {
        return view('medicines.create');
    }

   // Store new medicine in the database
public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'details' => 'required|string',
        'stock' => 'required|integer|min:0',
        'expiration' => 'required|date',
    ]);

    // Create a new medicine record with forced adminpurok
    Medicine::create([
        'name' => $validated['name'],
        'details' => $validated['details'],
        'stock' => $validated['stock'],
        'expiration' => $validated['expiration'],
        'purok' => 'adminpurok', // ✅ always tag as adminpurok
    ]);

    return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
}

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    // Update medicine in the database
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'stock' => 'required|integer|min:1',
            'expiration' => 'required|date', // Ensure expiration is updated
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function receive(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'donor' => 'required|string',
            'receiver' => 'required|string',
            'details' => 'nullable|string',
            
        ]);

        // Increase stock
        $medicine->increment('stock', $request->quantity);

        // Update expiration if needed (optional)
        $medicine->update(['expiration' => $request->expiration]);

        // Log transaction
        $medicine->transactions()->create([
            'quantity' => $request->quantity,
            'donor' => $request->donor,
            'receiver' => $request->receiver,
            'details' => $request->details,
            'type' => 'receive',
        ]);

        return redirect()->route('medicines.index')->with('success', 'Medicine stock increased.');
    }

    

    public function give(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'receiver' => 'required|string',
            'administered_by' => 'required|string',
            'details' => 'nullable|string',
        ]);

        // Check if enough stock is available
        if ($medicine->stock < $request->quantity) {
            return redirect()->route('medicines.index')->with('error', 'Not enough stock available.');
        }

        // Decrease stock
        $medicine->decrement('stock', $request->quantity);

        // Log transaction
        $medicine->transactions()->create([
            'quantity' => $request->quantity,
            'receiver' => $request->receiver,
            'administered_by' => $request->administered_by,
            'details' => $request->details,
            'type' => 'give',
        ]);

        return redirect()->route('medicines.index')->with('success', 'Medicine stock decreased.');
    }

    public function destroy($id)
{
    // Find the medicine by ID
    $medicine = Medicine::findOrFail($id);

    // Delete the medicine
    $medicine->delete();

    // Redirect back with a success message
    return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
}

public function request(Request $request)
{
    $search = $request->input('search');

    // Only fetch adminpurok medicines
    $adminMedicines = Medicine::where('purok', 'adminpurok')
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(6);

    return view('medicines.request', compact('adminMedicines'));
}

public function requestsAdmin(Request $request)
{
    $search = $request->input('search');

    $requests = \App\Models\MedicineRequest::with(['medicine', 'useradmin'])
        ->when($search, function ($query, $search) {
            $query->whereHas('medicine', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('medicines.requests_admin', compact('requests'));
}



    
}

