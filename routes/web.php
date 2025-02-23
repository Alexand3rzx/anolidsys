<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\PregnantController;
use App\Http\Controllers\InfantController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('/home',[HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//meds
Route::middleware(['auth'])->group(function () {
    Route::resource('medicines', MedicineController::class);

    // Receive, Give, Edit, and Delete actions
    //Route::post('medicines/{medicine}/receive', [MedicineController::class, 'receive'])->name('medicines.receive');
    //Route::post('medicines/{medicine}/give', [MedicineController::class, 'give'])->name('medicines.give');

    Route::post('/medicines/{medicine}/receive', [MedicineController::class, 'receive'])->name('medicines.receive');
Route::post('/medicines/{medicine}/give', [MedicineController::class, 'give'])->name('medicines.give');
    Route::get('medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::delete('medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    Route::put('medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');

});

//beneficiaries
//Route::resource('beneficiaries', BeneficiaryController::class);
Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
//Route::post('/beneficiaries/store', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
//Route::post('/beneficiaries/store-pregnant', [BeneficiaryController::class, 'storePregnant'])->name('beneficiaries.storePregnant');
//Route::post('/beneficiaries/store-infant', [BeneficiaryController::class, 'storeInfant'])->name('beneficiaries.storeInfant');

//Route::delete('/beneficiaries/{id}', [BeneficiaryController::class, 'destroy'])->name('beneficiaries.destroy');
//Route::put('/beneficiaries/{id}', [BeneficiaryController::class, 'update'])->name('beneficiaries.update');
require __DIR__.'/auth.php';

//pregnant
Route::post('/pregnant', [PregnantController::class, 'store'])->name('pregnant.store');
Route::post('/pregnant/store', [PregnantController::class, 'store'])->name('pregnant.store');
Route::get('/pregnant/{id}/edit', [PregnantController::class, 'edit'])->name('pregnant.edit');
Route::put('/pregnant/{id}', [PregnantController::class, 'update'])->name('pregnant.update');
Route::delete('/pregnant/{id}', [PregnantController::class, 'destroy'])->name('pregnant.destroy');
// Resource routes for infants
Route::resource('infants', InfantController::class)->except(['show']);

// Store a new infant
Route::post('/infants', [InfantController::class, 'store'])->name('infants.store');

// Fetch infant data for editing
Route::get('/infants/{id}/edit', [InfantController::class, 'edit']);

// Update an existing infant
Route::put('/infants/{infant}', [InfantController::class, 'update'])->name('infants.update');