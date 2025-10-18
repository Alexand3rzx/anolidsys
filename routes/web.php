<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\PregnantController;
use App\Http\Controllers\InfantController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\MedicineRequestController;
use App\Http\Controllers\ImmunizationController;
use App\Http\Controllers\PregnantImmunizationController;
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

Route::get('/medicine-requests/admin', [MedicineRequestController::class, 'adminIndex'])
    ->name('medicine-requests.admin')
    ->middleware('auth');

    Route::get('/medicines/requests-admin', [MedicineController::class, 'requestsAdmin'])->name('medicine.requests.admin');


// meds
Route::middleware(['auth'])->group(function () {

    // Medicine request routes
    Route::post('/medicine-requests', [MedicineRequestController::class, 'store'])
        ->name('medicine-requests.store');
    Route::post('/medicine-requests/{id}/approve', [MedicineRequestController::class, 'approve'])
        ->name('medicine-requests.approve');
    Route::post('/medicine-requests/{id}/reject', [MedicineRequestController::class, 'reject'])
        ->name('medicine-requests.reject');
    Route::get('/medicine-requests/list', [MedicineRequestController::class, 'list'])
        ->name('medicine-requests.list');

    Route::get('/medicines/request', [MedicineController::class, 'request'])
        ->name('medicines.request');
    Route::get('/medicines/requests-admin', [MedicineController::class, 'requestsAdmin'])
        ->name('medicine.requests.admin');
    Route::get('/medicine-requests/admin', [MedicineRequestController::class, 'adminIndex'])
        ->name('medicine-requests.admin');
        Route::get('/medicines/template', [MedicineController::class, 'template'])->name('medicines.template');

    // Medicine resource routes
    Route::resource('medicines', MedicineController::class);

    Route::post('/medicines/{medicine}/receive', [MedicineController::class, 'receive'])
        ->name('medicines.receive');
    Route::post('/medicines/{medicine}/give', [MedicineController::class, 'give'])
        ->name('medicines.give');
    Route::get('medicines/{medicine}/edit', [MedicineController::class, 'edit'])
        ->name('medicines.edit');
    Route::delete('medicines/{medicine}', [MedicineController::class, 'destroy'])
        ->name('medicines.destroy');
    Route::put('medicines/{medicine}', [MedicineController::class, 'update'])
        ->name('medicines.update');
    Route::get('/medicines', [MedicineController::class, 'index'])
        ->name('medicines.index');
        
        Route::post('/medicines/import', [MedicineController::class, 'import'])->name('medicines.import');

});


//beneficiaries
//Route::resource('beneficiaries', BeneficiaryController::class);
Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
// Beneficiaries split
Route::get('/beneficiaries/pregnants', [BeneficiaryController::class, 'pregnants'])->name('beneficiaries.pregnants');
Route::get('/beneficiaries/infants', [BeneficiaryController::class, 'infants'])->name('beneficiaries.infants');
Route::get('/beneficiaries/pregnants', [PregnantController::class, 'index'])->name('beneficiaries.pregnants');
Route::get('/beneficiaries/infants', [InfantController::class, 'index'])->name('beneficiaries.infants');
// AJAX searches
Route::get('/beneficiaries/searchPregnant', [BeneficiaryController::class, 'searchPregnant'])->name('beneficiaries.searchPregnant');
Route::get('/beneficiaries/searchInfant', [BeneficiaryController::class, 'searchInfant'])->name('beneficiaries.searchInfant');
Route::post('/pregnants/{id}/immunizations', [App\Http\Controllers\PregnantImmunizationController::class, 'store'])->name('pregnant.immunization.store');
Route::delete('/pregnants/{id}/immunizations/{recordId}', [App\Http\Controllers\PregnantImmunizationController::class, 'destroy'])->name('pregnant.immunization.delete');
require __DIR__.'/auth.php';

//pregnant
Route::post('/pregnant', [PregnantController::class, 'store'])->name('pregnant.store');
Route::post('/pregnant/store', [PregnantController::class, 'store'])->name('pregnant.store');
Route::get('/pregnant/{id}/edit', [PregnantController::class, 'edit'])->name('pregnant.edit');
Route::put('/pregnant/{id}', [PregnantController::class, 'update'])->name('pregnant.update');
Route::delete('/pregnant/{id}', [PregnantController::class, 'destroy'])->name('pregnant.destroy');


//prgimmnzts
Route::post('/pregnant/{id}/add-immunization', [PregnantController::class, 'addImmunization'])->name('pregnant.addImmunization');


// Resource routes for infants
Route::resource('infants', InfantController::class)->except(['show']);

// Store a new infant
Route::post('/infants', [InfantController::class, 'store'])->name('infants.store');

// Fetch infant data for editing
Route::get('/infants/{id}/edit', [InfantController::class, 'edit']);

// Update an existing infant
Route::put('/infants/{infant}', [InfantController::class, 'update'])->name('infants.update');

Route::put('/infants/{id}/immunization', [ImmunizationController::class, 'update'])->name('immunization.update');

Route::get('/infants/{id}/edit', [InfantController::class, 'edit'])->name('infants.edit');

Route::get('/search-pregnant', [BeneficiaryController::class, 'searchPregnant']);

Route::get('/register', [RegisterController::class])->name('register');

// Show register form
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Handle register form submission
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/dashboard', function () {
    return view('dashboard'); // make sure this matches your blade file name
})->middleware(['auth'])->name('dashboard');

Route::get('/medicine_request', function () {
    return view('/medicines/medicine_request');
})->middleware(['auth'])->name('medicine.request');

Route::get('/useradmin/create', [UserAdminController::class, 'create'])->name('useradmin.create');
Route::post('/useradmin/store', [UserAdminController::class, 'store'])->name('useradmin.store');
Route::put('/useradmin/{id}', [UserAdminController::class, 'update'])->name('useradmin.update');
Route::delete('/useradmin/{id}', [UserAdminController::class, 'destroy'])->name('useradmin.destroy');

Route::resource('useradmin', UserAdminController::class);

Route::middleware(['auth'])->group(function () {
    Route::middleware('admin.only')->group(function () {
        Route::get('/useradmin/create', [UserAdminController::class, 'create'])->name('useradmin.create');
        Route::post('/useradmin/store', [UserAdminController::class, 'store'])->name('useradmin.store');
    });
});

// Medicine Request System
Route::get('/medicines/request', [MedicineRequestController::class, 'requestPage'])
    ->name('medicines.request');

Route::post('/medicine-requests', [MedicineRequestController::class, 'store'])
    ->name('medicine-requests.store');

Route::post('/medicine-requests/{id}/approve', [MedicineRequestController::class, 'approve'])
    ->name('medicine-requests.approve');

Route::post('/medicine-requests/{id}/reject', [MedicineRequestController::class, 'reject'])
    ->name('medicine-requests.reject');

Route::post('/medicine-requests/confirm-pickup', [MedicineRequestController::class, 'confirmPickup'])
    ->name('medicine-requests.confirmPickup');

Route::get('/medicine-requests/admin', [MedicineRequestController::class, 'adminIndex'])
    ->name('medicine-requests.admin');


// Pregnant detail page
Route::get('/beneficiaries/pregnant/{id}', [PregnantController::class, 'show'])->name('pregnant.show');

// Infant detail page
Route::get('/beneficiaries/infant/{id}', [InfantController::class, 'show'])->name('infant.show');

Route::post('/pregnant/import', [PregnantController::class, 'import'])->name('pregnant.import');

Route::get('/pregnant/template', [PregnantController::class, 'downloadTemplate'])->name('pregnant.template');

Route::post('/infants/import', [InfantController::class, 'import'])->name('infants.import');
Route::get('/infants/template', [InfantController::class, 'downloadTemplate'])->name('infants.template');

Route::get('/pregnant/{id}/certificate', [PregnantImmunizationController::class, 'generateCertificate'])
    ->name('pregnant.certificate');

Route::get('/pregnant/{id}/certificate', [PregnantController::class, 'certificate'])
    ->name('pregnant.certificate');

Route::get('/infants/{id}/certificate', [App\Http\Controllers\InfantController::class, 'generateCertificate'])
     ->name('infants.generateCertificate');

    Route::get('/notifications/mark-all-read', [App\Http\Controllers\HomeController::class, 'markAllRead'])
    ->name('notifications.markAllRead')
    ->middleware('auth');

    Route::get('/admin/report/download', [App\Http\Controllers\HomeController::class, 'downloadReport'])
    ->name('admin.report.download');

  // Medicine Request pages (handled by HomeController)
Route::get('/requests', [HomeController::class, 'useradminRequests'])->name('requests.index');
Route::get('/requests_admin', [HomeController::class, 'adminRequests'])->name('requests.admin');