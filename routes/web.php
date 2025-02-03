<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicineController;
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
});

require __DIR__.'/auth.php';
