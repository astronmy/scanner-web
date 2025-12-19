<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableAssignmentController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('assignments')
        ->name('assignments.')
        ->controller(TableAssignmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');                
            Route::get('/form', 'importForm')->name('import-form'); 
            Route::post('/import', 'import')->name('import');       

            Route::get('/{assignment}/edit', 'edit')->name('edit');
            Route::put('/{assignment}', 'update')->name('update');
            Route::delete('/{assignment}', 'destroy')->name('destroy');
        });

    Route::prefix('scanners')
        ->name('scanners.')
        ->controller(ScannerController::class)
        ->group(function () {
            Route::get('/', 'start')->name('start');
            Route::post('/', 'storage')->name('storage');
        });

    Route::prefix('scans')
    ->name('scans.')
    ->controller(ScanController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::delete('/scans/{scan}', [ScanController::class, 'destroy'])
    ->name('destroy');      
    });

     Route::resource('events', EventController::class)->except(['show']);
     Route::resource('users', UserController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
