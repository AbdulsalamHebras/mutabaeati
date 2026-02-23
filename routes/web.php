<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isMuraqib()) {
            return redirect()->route('muraqib.dashboard');
        }
        return redirect()->route('muhdir.dashboard');
    })->name('dashboard');

    // Admin Dashboard & Student Management
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::get('/students', [App\Http\Controllers\AdminController::class, 'students'])->name('students.index');
        Route::get('/students/create', [App\Http\Controllers\AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\AdminController::class, 'storeStudent'])->name('students.store');
    });

    // Muhdir Dashboard & Student List
    Route::middleware(['role:muhdir'])->prefix('muhdir')->name('muhdir.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\MuhdirController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports/create/{student}', [App\Http\Controllers\ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
    });

    // Muraqib Dashboard
    Route::middleware(['role:muraqib'])->prefix('muraqib')->name('muraqib.')->group(function () {
        Route::get('/dashboard', function () {
            return view('muraqib.dashboard');
        })->name('dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
