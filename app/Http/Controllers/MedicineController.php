<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterPurok = $request->input('purok');
        $filterBatch = $request->input('batch');
        $user = auth()->user();

        $query = Medicine::query();

       if ($filterBatch && $filterBatch !== 'all') {
    $query->whereIn('id', DB::table('medicine_batch_medicine')
        ->where('batch_id', (int)$filterBatch)
        ->pluck('medicine_id'));
}
        if ($user->usertype === 'admin') {
            if ($filterPurok) {
                $query->where('purok', $filterPurok);
            }
        } elseif ($user->usertype === 'useradmin') {
            $query->where('purok', $user->purok)
                  ->where('purok', '!=', 'adminpurok');
        } else {
            abort(403, 'Unauthorized');
        }

        $query->select(
            DB::raw('MIN(id) as id'),
            'name',
            'details',
            'expiration',
            DB::raw('SUM(stock) as stock')
        )
        ->groupBy('name', 'details', 'expiration');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        $medicines = $query->orderBy('name', 'asc')
                           ->paginate(6)
                           ->appends($request->query());

        // Separate list of admin medicines for useradmins
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
                ->appends($request->query());
        }

        // Purok dropdown
        $puroks = collect();
        if ($user->usertype === 'admin') {
            $puroks = Medicine::select('purok')->distinct()->pluck('purok');
        }

        // Logs
        $transactionQuery = \App\Models\MedicineTransaction::query();
        $filterType = $request->input('type');
        $filterLogPurok = $request->input('log_purok');

        if ($user->usertype === 'admin') {
            if ($filterType) {
                $transactionQuery->where('type', $filterType);
            }
            if ($filterLogPurok) {
                $transactionQuery->whereHas('medicine', fn($q) => $q->where('purok', $filterLogPurok));
            }
        } elseif ($user->usertype === 'useradmin') {
            $transactionQuery->where('type', 'give')
                             ->whereHas('medicine', fn($q) => $q->where('purok', $user->purok));
        }

        $logs = $transactionQuery->with('medicine')->latest()->paginate(4, ['*'], 'logs_page');
        $logPuroks = Medicine::select('purok')->distinct()->pluck('purok');

        // 🔹 Only show batches belonging to user's purok
        $batches = DB::table('medicine_batches')
            ->where('purok', $user->purok ?? 'adminpurok')
            ->select('id', 'batch_number')
            ->get();

        return view('medicines.index', compact('medicines', 'adminMedicines', 'puroks', 'logs', 'logPuroks', 'batches'));
    }

    public function getBatches($medicineId)
    {
        $user = Auth::user();
        $batches = DB::table('medicine_batches')
            ->where('purok', $user->purok ?? 'adminpurok')
            ->select('id', 'batch_number')
            ->get();

        return response()->json($batches);
    }

    public function create()
    {
        $user = Auth::user();
        $batches = MedicineBatch::where('purok', $user->purok ?? 'adminpurok')->get();
        return view('medicines.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'stock' => 'required|integer|min:0',
            'expiration' => 'required|date',
            'batch_option' => 'required|string',
            'batch_id' => 'nullable|integer|exists:medicine_batches,id',
            'batch_number' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $userPurok = Auth::user()->purok ?? 'adminpurok';

            $medicine = Medicine::create([
                'name' => $validated['name'],
                'details' => $validated['details'],
                'stock' => $validated['stock'],
                'expiration' => $validated['expiration'],
                'purok' => $userPurok,
            ]);

            $batchId = null;

            if ($validated['batch_option'] === 'existing' && !empty($validated['batch_id'])) {
                $batchId = $validated['batch_id'];
            }

            if ($validated['batch_option'] === 'new' && !empty($validated['batch_number'])) {
                $existingBatch = MedicineBatch::where('batch_number', $validated['batch_number'])
                    ->where('purok', $userPurok)
                    ->first();

                if ($existingBatch) {
                    $batchId = $existingBatch->id;
                } else {
                    $batchId = MedicineBatch::insertGetId([
                        'batch_number' => $validated['batch_number'],
                        'purok' => $userPurok,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($batchId) {
                DB::table('medicine_batch_medicine')->insert([
                    'medicine_id' => $medicine->id,
                    'batch_id' => $batchId,
                ]);
            }

            DB::commit();
            return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'stock' => 'required|integer|min:1',
            'expiration' => 'required|date',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function receive(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'nullable|string',
            'expiration' => 'nullable|date',
            'donor' => 'required|string',
            'details' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $medicine = Medicine::findOrFail($validated['medicine_id']);
            $userPurok = Auth::user()->purok ?? 'adminpurok';
            $currentExpiration = $medicine->expiration;
            $newExpiration = $validated['expiration'] ?? $currentExpiration;

            if ($newExpiration == $currentExpiration) {
                $medicine->update([
                    'stock' => $medicine->stock + $validated['quantity'],
                    'expiration' => $currentExpiration,
                ]);

                \App\Models\MedicineTransaction::create([
                    'medicine_id' => $medicine->id,
                    'quantity' => $validated['quantity'],
                    'donor' => $validated['donor'],
                    'receiver' => auth()->user()->name,
                    'details' => $validated['details'],
                    'type' => 'receive',
                ]);
            } else {
                $newMedicine = Medicine::create([
                    'name' => $medicine->name,
                    'details' => $medicine->details,
                    'stock' => $validated['quantity'],
                    'purok' => $userPurok,
                    'expiration' => $newExpiration,
                ]);

                $existingBatch = null;
                if (!empty($validated['batch_number'])) {
                    $existingBatch = DB::table('medicine_batches')
                        ->where('batch_number', $validated['batch_number'])
                        ->where('purok', $userPurok)
                        ->first();
                }

                if ($existingBatch) {
                    $batchId = $existingBatch->id;
                } else {
                    $batchId = DB::table('medicine_batches')->insertGetId([
                        'batch_number' => $validated['batch_number'] ?? 'BATCH-' . strtoupper(uniqid()),
                        'purok' => $userPurok,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('medicine_batch_medicine')->insert([
                    'medicine_id' => $newMedicine->id,
                    'batch_id' => $batchId,
                ]);

                \App\Models\MedicineTransaction::create([
                    'medicine_id' => $newMedicine->id,
                    'quantity' => $validated['quantity'],
                    'donor' => $validated['donor'],
                    'receiver' => auth()->user()->name,
                    'details' => $validated['details'],
                    'type' => 'receive',
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Medicine successfully received.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function give(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'receiver' => 'required|string',
            'administered_by' => 'required|string',
            'details' => 'nullable|string',
        ]);

        if ($medicine->stock < $request->quantity) {
            return redirect()->route('medicines.index')->with('error', 'Not enough stock available.');
        }

        $medicine->decrement('stock', $request->quantity);

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
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }

  public function request(Request $request)
{
    $search = $request->input('search');

    $adminMedicines = DB::table('medicine_batch_medicine as mbm')
        ->join('medicines as m', 'mbm.medicine_id', '=', 'm.id')
        ->join('medicine_batches as b', 'mbm.batch_id', '=', 'b.id')
        ->where('m.purok', 'adminpurok')
        ->when($search, fn($q) => $q->where('m.name', 'like', "%{$search}%"))
        ->select(
            'm.id as medicine_id',
            'm.name',
            'm.details',
            'b.id as batch_id',
            'b.batch_number',
            'm.stock', // ✅ use the stock from medicines table
            'm.expiration' // ✅ expiration from medicines too
        )
        ->orderBy('m.name')
        ->paginate(6);

    return view('medicines.request', compact('adminMedicines'));
}

    public function requestsAdmin(Request $request)
    {
        $search = $request->input('search');

        $requests = MedicineRequest::with(['medicine', 'useradmin'])
            ->when($search, function ($query, $search) {
                $query->whereHas('medicine', fn($q) => $q->where('name', 'like', "%$search%"))
                      ->orWhereHas('useradmin', fn($q) => $q->where('name', 'like', "%$search%"))
                      ->orWhere('pickup_code', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('medicines.requests_admin', compact('requests'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle, 0, ',')) !== false) {
                    $name = $row[0] ?? null;
                    $details = $row[1] ?? null;
                    $stock = isset($row[2]) ? (int)$row[2] : 0;
                    $purok = $row[3] ?? 'adminpurok';
                    $expiration = $row[4] ?? null;
                    $batchName = $row[5] ?? null;

                    if (!$name || !$details || !$expiration) continue;

                    $medicine = Medicine::create([
                        'name' => $name,
                        'details' => $details,
                        'stock' => $stock,
                        'purok' => $purok,
                        'expiration' => $expiration,
                    ]);

                    if ($batchName) {
                        $batch = DB::table('medicine_batches')
                            ->where('batch_number', $batchName)
                            ->where('purok', $purok)
                            ->first();

                        if (!$batch) {
                            $batchId = DB::table('medicine_batches')->insertGetId([
                                'batch_number' => $batchName,
                                'purok' => $purok,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            $batchId = $batch->id;
                        }

                        DB::table('medicine_batch_medicine')->insert([
                            'medicine_id' => $medicine->id,
                            'batch_id' => $batchId,
                        ]);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
            }

            fclose($handle);
        }

        return redirect()->back()->with('success', 'Medicines imported successfully with purok-based batch support!');
    }

    public function template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="medicines_template.csv"',
        ];

        $columns = ['name', 'details', 'stock', 'purok', 'expiration', 'batch_name'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['Paracetamol', 'Pain reliever/tablet', 100, 'purok1', '2026-12-31', 'Batch 1']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
