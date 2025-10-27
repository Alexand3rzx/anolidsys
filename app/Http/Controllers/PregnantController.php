<?php

namespace App\Http\Controllers;

use App\Models\Pregnant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompletedImmunizationRecord;
use PDF;

class PregnantController extends Controller
{
    // Store a new pregnant woman
    public function store(Request $request)
    {
        $request->validate([
            'prgname' => 'required|string|max:255',
            'prgage' => 'required|integer',
            'prgbday' => 'required|date',
            'prgaddress' => 'required|string|max:255',
            'purok' => 'required|string|max:255',
            'prgoccupation' => 'nullable|string|max:255',
            'prgreligion' => 'nullable|string|max:255',
            'prgmother_name' => 'nullable|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'partner_age' => 'nullable|integer',
            'prgtimes' => 'required|integer|min:1',
            'partner_bday' => 'nullable|date',
            'partner_occupation' => 'nullable|string|max:255',
            'partner_religion' => 'nullable|string|max:255',
            'partner_number' => 'nullable|string|max:15',
        ]);

        Pregnant::create($request->all());

        return back()->with('success', 'Pregnant woman added successfully');
    }

    public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = fopen($request->file('csv_file')->getRealPath(), 'r');
    $header = fgetcsv($file); // Skip header row

    $imported = 0;
    while (($row = fgetcsv($file)) !== false) {
        // Map CSV columns (make sure your CSV matches this order)
        $data = [
            'prgname' => $row[0] ?? null,
            'prgbday' => $row[1] ?? null,
            'prgage' => $row[2] ?? null,
            'prgaddress' => $row[3] ?? null,
            'purok' => $row[4] ?? null,
            'prgoccupation' => $row[5] ?? null,
            'prgreligion' => $row[6] ?? null,
            'prgmother_name' => $row[7] ?? null,
            'partner_name' => $row[8] ?? null,
            'partner_age' => $row[9] ?? null,
            'partner_bday' => $row[10] ?? null,
            'partner_occupation' => $row[11] ?? null,
            'partner_religion' => $row[12] ?? null,
            'partner_number' => $row[13] ?? null,
            'prgtimes' => $row[14] ?? 1,
        ];

        // Skip incomplete rows
        if (!$data['prgname'] || !$data['prgbday'] || !$data['prgaddress']) {
            continue;
        }

        try {
            \App\Models\Pregnant::create($data);
            $imported++;
        } catch (\Exception $e) {
            continue; // Skip bad rows
        }
    }

    fclose($file);

    return redirect()->back()->with('success', "{$imported} records imported successfully!");
}

public function downloadTemplate()
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="pregnants_template.csv"',
    ];

    $columns = [
        'prgname',
        'prgage',
        'prgtimes',
        'prgbday',
        'prgaddress',
        'purok',
        'prgoccupation',
        'prgreligion',
        'prgmother_name',
        'partner_name',
        'partner_age',
        'partner_bday',
        'partner_occupation',
        'partner_religion',
        'partner_number'
    ];

    $callback = function() use ($columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    // Show a single record for editing
    public function edit($id)
    {
        $woman = Pregnant::findOrFail($id);
        return response()->json($woman);
    }

    // Update the record
   public function update(Request $request, $id)
{
    $pregnantWoman = Pregnant::findOrFail($id);

    // --- Validate all fields (main + immunization + photo)
    $request->validate([
        'prgname' => 'required|string|max:255',
        'prgage' => 'required|integer',
        'prgbday' => 'required|date',
        'prgaddress' => 'required|string|max:255',
        'purok' => 'required|string|max:255',
        'prgoccupation' => 'nullable|string|max:255',
        'prgreligion' => 'required|string|max:255',
        'prgmother_name' => 'nullable|string|max:255',
        'partner_name' => 'nullable|string|max:255',
        'partner_age' => 'nullable|integer',
        'partner_bday' => 'nullable|date',
        'partner_occupation' => 'nullable|string|max:255',
        'partner_religion' => 'nullable|string|max:255',
        'partner_number' => 'nullable|string|max:15',
        'prgtimes' => 'required|integer|min:1',

        // Immunization input fields
        'visit_number' => 'nullable|string|max:50',
        'visit_date' => 'nullable|date',
        'expected_month' => 'nullable|string|max:50',
        'vaccine_given' => 'nullable|string|max:255',
        'remarks' => 'nullable|string|max:255',

        // Photo field
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // --- Prepare main data
    $data = $request->only([
        'prgname', 'prgage', 'prgbday', 'prgaddress','purok', 'prgoccupation',
        'prgreligion', 'prgmother_name', 'partner_name', 'partner_age',
        'partner_bday', 'partner_occupation', 'partner_religion', 
        'partner_number', 'prgtimes'
    ]);

    // ✅ Handle uploaded photo
    if ($request->hasFile('photo')) {
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/pregnants', $photoName);

        // Delete old photo if exists
        if ($pregnantWoman->photo && \Storage::exists('public/pregnants/' . $pregnantWoman->photo)) {
            \Storage::delete('public/pregnants/' . $pregnantWoman->photo);
        }

        $data['photo'] = $photoName;
    }

    // --- Update main pregnant record
    $pregnantWoman->update($data);

    // --- If immunization data provided, update or create
    if ($request->filled('visit_date') && $request->filled('vaccine_given')) {
        if ($pregnantWoman->prgtimes == 1) {
            \App\Models\FirstPregnancyImmunization::updateOrCreate(
                ['pregnant_id' => $pregnantWoman->id, 'visit_number' => $request->visit_number],
                $request->only(['visit_number', 'visit_date', 'expected_month', 'vaccine_given', 'remarks'])
            );
        } elseif ($pregnantWoman->prgtimes >= 2 && $pregnantWoman->prgtimes <= 5) {
            \App\Models\SecondToFifthPregnancyImmunization::updateOrCreate(
                ['pregnant_id' => $pregnantWoman->id, 'visit_number' => $request->visit_number],
                $request->only(['visit_number', 'visit_date', 'expected_month', 'vaccine_given', 'remarks'])
            );
        } else {
            \App\Models\SixthPregnancyImmunization::updateOrCreate(
                ['pregnant_id' => $pregnantWoman->id, 'visit_number' => $request->visit_number],
                $request->only(['visit_number', 'visit_date', 'expected_month', 'vaccine_given', 'remarks'])
            );
        }
    }

    return back()->with('success', 'Pregnant record, photo, and immunization data updated successfully!');
}


public function addImmunization(Request $request, $id)
{
    $woman = Pregnant::findOrFail($id);

    if ($woman->prgtimes == 1) {
        $modelClass = \App\Models\FirstPregnancyImmunization::class;
    } elseif ($woman->prgtimes >= 2 && $woman->prgtimes <= 5) {
        $modelClass = \App\Models\SecondToFifthPregnancyImmunization::class;
    } else {
        $modelClass = \App\Models\SixthPregnancyImmunization::class;
    }

    // Define visit titles
    $checkups = [
        ['month' => '1st Trimester', 'vaccine' => 'First Trimester Check-up'],
        ['month' => '2nd Trimester', 'vaccine' => '2nd Trimester Check-up (Tetanus Diphtheria, Ferrous Sulfate + Folic Acid Supplement Distribution)'],
        ['month' => '2nd Trimester', 'vaccine' => '2nd Trimester Check-up & Tetanus Diphtheria Vaccination'],
        ['month' => '3rd Trimester', 'vaccine' => 'Check-up'],
        ['month' => '3rd Trimester', 'vaccine' => 'Check-up'],
    ];

    // Delete previous to prevent duplicates
    $modelClass::where('pregnant_id', $woman->id)->delete();

    foreach ($request->visit_dates as $i => $date) {
        if (!empty($date)) {
            $modelClass::create([
                'pregnant_id' => $woman->id,
                'visit_number' => $i + 1,
                'visit_date' => $date,
                'expected_month' => $checkups[$i]['month'] ?? null,
                'vaccine_given' => $checkups[$i]['vaccine'] ?? null,
                'remarks' => $request->remarks[$i] ?? 'null',
            ]);
        }
    }

    return redirect()->back()->with('success', 'Immunization records saved successfully!');
}

    // Delete the record
    public function destroy($id)
    {
        $pregnantWoman = Pregnant::findOrFail($id);
        $pregnantWoman->delete();

        return redirect()->route('beneficiaries.index')->with('success', 'Record deleted successfully!');
    }

    // Show a single record
   public function show($id)
{
    $woman = Pregnant::findOrFail($id);

    // Auto-load immunization records based on prgtimes
    if ($woman->prgtimes == 1) {
        $records = \App\Models\FirstPregnancyImmunization::where('pregnant_id', $woman->id)->get();
    } elseif ($woman->prgtimes >= 2 && $woman->prgtimes <= 5) {
        $records = \App\Models\SecondToFifthPregnancyImmunization::where('pregnant_id', $woman->id)->get();
    } else {
        $records = \App\Models\SixthPregnancyImmunization::where('pregnant_id', $woman->id)->get();
    }

    return view('beneficiaries.pregnant_show', compact('woman', 'records'));
}
    // Pregnants list
    public function index()
    {
        $user = Auth::user();

        if ($user->usertype === 'admin') {
            // Admin sees all records
            $pregnantWomen = Pregnant::paginate(6);
        } elseif ($user->usertype === 'useradmin') {
            // Useradmin sees only their purok
            $pregnantWomen = Pregnant::where('purok', $user->purok)->paginate(6);
        } else {
            // fallback (normal users see nothing or handle differently)
            $pregnantWomen = collect();
        }

        return view('beneficiaries.pregnants', compact('pregnantWomen'));
    }

public function certificate($id)
{
    $woman = Pregnant::findOrFail($id);

    $pdf = Pdf::loadView('pregnant_certificate', compact('woman'))
              ->setPaper('a4', 'portrait'); // <- portrait ensures vertical orientation

    $filename = 'Maternal_Health_Certificate_' . str_replace(' ', '_', $woman->prgname) . '.pdf';

    return $pdf->download($filename);
}

public function saveCompletedRecord($id)
{
    $woman = Pregnant::findOrFail($id);

    // Determine which immunization table applies
    if ($woman->prgtimes == 1) {
        $records = $woman->firstPregnancyRecords;
    } elseif ($woman->prgtimes >= 2 && $woman->prgtimes <= 5) {
        $records = $woman->secondToFifthPregnancyRecords;
    } else {
        $records = $woman->sixthPregnancyRecords;
    }

    if (!$records || $records->isEmpty()) {
        return back()->with('error', 'No immunization records found to save.');
    }

    // Save to completed records table
    $completed = \App\Models\CompletedImmunizationRecord::create([
        'pregnant_id' => $woman->id,
        'records' => $records->toJson(),
    ]);

    return back()->with('success', 'Completed immunization record saved successfully!');
}


public function viewCompletedRecord($id, $recordId)
{
    $woman = Pregnant::findOrFail($id);
    $record = \App\Models\CompletedImmunizationRecord::findOrFail($recordId);
    $records = json_decode($record->records);

    return response()->json([
        'woman_name' => $woman->prgname,
        'completed_at' => $record->created_at->format('F d, Y'),
        'records' => $records
    ]);
}


}
