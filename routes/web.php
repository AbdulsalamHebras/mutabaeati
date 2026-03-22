<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LessonController;

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
        if (auth()->user()->isMuraqib()) {
            return redirect()->route('muraqib.dashboard');
        }
        return redirect()->route('muhdir.dashboard');
    })->name('dashboard');

    // Muhdir Dashboard & Student List
    Route::middleware(['role:muhdir'])->prefix('muhdir')->name('muhdir.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\MuhdirController::class, 'dashboard'])->name('dashboard');
        Route::get('/distributions', [App\Http\Controllers\MuhdirController::class, 'distributions'])->name('distribution');
        Route::get('/reports', [App\Http\Controllers\MuhdirController::class, 'reports'])->name('reports.index');
        Route::get('/reports/create/{student}', [App\Http\Controllers\ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
        Route::get('/lessons', [App\Http\Controllers\LessonController::class, 'index'])->name('lessons.index');
        Route::post('/lessons', [App\Http\Controllers\LessonController::class, 'store'])->name('lessons.store');
        Route::post('/lessons/update', [App\Http\Controllers\LessonController::class, 'update'])->name('lessons.update');
        Route::get('/lesson-filter', [App\Http\Controllers\MuhdirController::class, 'lessonFilter'])->name('lessonFilter');
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
Route::post('/notifications/read/{id}', function ($id) {
    $notification = auth()->user()->notifications()->find($id);

    if ($notification) {
        $notification->markAsRead();
    }

    return response()->json(['success' => true]);
})->name('notifications.read');
Route::get('/notifications/count', function () {
    return response()->json([
        'count' => auth()->user()->unreadNotifications->count()
    ]);
});

require __DIR__.'/auth.php';
