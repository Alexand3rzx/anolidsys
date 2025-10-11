<?php

namespace App\Http\Controllers;

use App\Models\Pregnant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // --- Validate main fields
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

        // --- Immunization input fields
        'visit_number' => 'nullable|string|max:50',
        'visit_date' => 'nullable|date',
        'expected_month' => 'nullable|string|max:50',
        'vaccine_given' => 'nullable|string|max:255',
        'remarks' => 'nullable|string|max:255',
    ]);

    // --- Update main pregnant record
    $pregnantWoman->update($request->only([
        'prgname', 'prgage', 'prgbday', 'prgaddress','purok', 'prgoccupation',
        'prgreligion', 'prgmother_name', 'partner_name', 'partner_age',
        'partner_bday', 'partner_occupation', 'partner_religion', 'partner_number', 'prgtimes'
    ]));

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

    return back()->with('success', 'Pregnant record and immunization data updated successfully!');
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
            $pregnantWomen = Pregnant::paginate(10);
        } elseif ($user->usertype === 'useradmin') {
            // Useradmin sees only their purok
            $pregnantWomen = Pregnant::where('purok', $user->purok)->paginate(10);
        } else {
            // fallback (normal users see nothing or handle differently)
            $pregnantWomen = collect();
        }

        return view('beneficiaries.pregnants', compact('pregnantWomen'));
    }
}
