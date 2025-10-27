<?php

namespace App\Http\Controllers;

use App\Models\Infant;
use App\Models\Immunization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Barryvdh\DomPDF\Facade\Pdf;

class InfantController extends Controller
{
    // Store a new infant
   public function store(Request $request)
{
    $request->validate([
        'child_name' => 'required|string',
        'child_bday' => 'required|date',
        'child_place' => 'required|string',
        'child_address' => 'required|string',
        'purok' => 'required|string',
        'child_mother' => 'required|string',
        'child_father' => 'required|string',
        'child_gender' => 'required|in:Male,Female',
        'child_height' => 'required|numeric',
        'child_weight' => 'required|numeric',
    ]);

    // ✅ Generate unique code
    $lastInfant = Infant::latest('id')->first();
    $nextNumber = $lastInfant ? intval(substr($lastInfant->infant_code, 4)) + 1 : 1;
    $code = 'INF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    // ✅ Create infant with code
    $infant = Infant::create(array_merge($request->all(), [
        'infant_code' => $code,
    ]));

    // ✅ Create immunization record
    Immunization::create(['infant_id' => $infant->id]);

    return redirect()->route('beneficiaries.index')->with('success', 'Infant added successfully!');
}

public function index(Request $request)
{
    $user = Auth::user();
    $selectedPurok = $request->get('purok');

    // Start the query
    $query = Infant::query();

    // If user is useradmin, lock to their purok
    if ($user->usertype === 'useradmin') {
        $query->where('purok', $user->purok);
    }

    // If admin selected a purok filter
    if ($user->usertype === 'admin' && $selectedPurok) {
        $query->where('purok', $selectedPurok);
    }

    $infants = $query->paginate(6);

    // Get unique purok values for dropdown
    $puroks = Infant::select('purok')->distinct()->pluck('purok');

    return view('beneficiaries.infants', compact('infants', 'puroks', 'selectedPurok'));
}



    public function edit($id)
{
    // Fetch the infant record with its associated immunization data
    $infant = Infant::with('immunization')->findOrFail($id);

    // Log the infant data for debugging
    \Log::info('Infant Data:', $infant->toArray());

    // Return the infant data as JSON
    return response()->json($infant);
}


    public function update(Request $request, $id)
{
    $infant = Infant::findOrFail($id);
    $infant->update($request->only([
        'child_name', 'child_bday', 'child_place', 'child_address','purok',
        'child_mother', 'child_father', 'child_gender', 'child_height', 'child_weight'
    ]));

    // Update or create immunization record
    $immunizationData = $request->only([
        'bcg_date', 'hepatitis_b_date', 'pentavalent_date_1', 'pentavalent_date_2', 'pentavalent_date_3',
        'opv_date_1', 'opv_date_2', 'opv_date_3', 'ipv_date_1', 'ipv_date_2',
        'pcv_date_1', 'pcv_date_2', 'pcv_date_3', 'mmr_date_1', 'mmr_date_2'
    ]);

    $infant->immunization()->updateOrCreate(
        ['infant_id' => $id], // Conditions
        $immunizationData // Data to update or create
    );

    return back()->with('success', 'Infant updated successfully');
}

public function show($id)
{
    $infant = Infant::with('immunization')->findOrFail($id);
    return view('beneficiaries.infant_show', compact('infant'));
}
public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt',
    ]);

    $path = $request->file('csv_file')->getRealPath();

    if (($handle = fopen($path, 'r')) !== false) {
        $header = fgetcsv($handle, 1000, ','); // Read header row
        $counter = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $data = array_combine($header, $row);

            // ✅ Generate infant_code dynamically
            $lastInfant = Infant::latest('id')->first();
            $nextNumber = $lastInfant ? intval(substr($lastInfant->infant_code, 4)) + 1 : 1;
            $code = 'INF-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $infant = Infant::create([
                'infant_code' => $code,
                'child_name' => $data['child_name'] ?? null,
                'child_bday' => $data['child_bday'] ?? null,
                'child_place' => $data['child_place'] ?? null,
                'child_address' => $data['child_address'] ?? null,
                'purok' => $data['purok'] ?? null,
                'child_mother' => $data['child_mother'] ?? null,
                'child_father' => $data['child_father'] ?? null,
                'child_gender' => $data['child_gender'] ?? null,
                'child_height' => $data['child_height'] ?? null,
                'child_weight' => $data['child_weight'] ?? null,
            ]);

            Immunization::create(['infant_id' => $infant->id]);
            $counter++;
        }

        fclose($handle);
    }

    return redirect()->back()->with('success', "Imported {$counter} infants successfully!");
}

public function downloadTemplate()
{
    // Define the CSV headers (matching your import fields)
    $headers = [
        'child_name',
        'child_bday',
        'child_place',
        'child_address',
        'purok',
        'child_mother',
        'child_father',
        'child_gender',
        'child_height',
        'child_weight'
    ];

    // Create a sample row for guidance (optional)
    $sampleData = [
        ['Juan Dela Cruz', '2023-05-12', 'San Carlos', 'Purok 1', 'Purok 1', 'Maria Dela Cruz', 'Jose Dela Cruz', 'Male', '50', '3.2'],
    ];

    // Build CSV content
    $csvContent = implode(',', $headers) . "\n";
    foreach ($sampleData as $row) {
        $csvContent .= implode(',', $row) . "\n";
    }

    // Download response
    return response($csvContent)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="infant_template.csv"');
}

public function generateCertificate($id)
{
    $infant = Infant::with('immunization')->findOrFail($id);

    $pdf = Pdf::loadView('infant_certificate', compact('infant'))
              ->setPaper('a4', 'portrait');

    $filename = 'Vaccination_Certificate_' . str_replace(' ', '_', $infant->child_name) . '.pdf';
    return $pdf->download($filename);
}
     
 }