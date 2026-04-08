<?php

use App\Http\Controllers\DashboardController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{event_id}/scan', [DashboardController::class, 'selectEvent'])->name('dashboard.event');
    Route::get('/dashboard/{event_id}/scanner', [DashboardController::class, 'selectEventAndScan'])->name('dashboard.event.scanner');
    Route::post('/dashboard/{event_id}/context', [DashboardController::class, 'setEventContext'])->name('dashboard.event.context');

    Route::prefix('assignments')
        ->name('assignments.')
        ->middleware('elevated')
        ->controller(TableAssignmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/form', 'importForm')->name('import-form');
            Route::get('/template', 'downloadTemplate')->name('template');
            Route::post('/import', 'import')->name('import');
            Route::delete('/destroy-all', 'destroyAll')->name('destroy-all');
            Route::get('/{assignment}/edit', 'edit')->name('edit');
            Route::put('/{assignment}', 'update')->name('update');
            Route::delete('/{assignment}', 'destroy')->name('destroy');
        });

    Route::prefix('scanners')
        ->name('scanners.')
        ->controller(ScannerController::class)
        ->group(function () {
            Route::get('/', 'start')->name('start');
            Route::get('/list/{event}', 'list')->name('list');
            Route::get('/list/{event}/scans/{scan}/edit-data', 'listScanEditData')->name('list.scan.edit-data');
            Route::put('/list/{event}/scans/{scan}', 'updateListScan')->name('list.scan.update');
            Route::delete('/list/{event}/scans/{scan}', 'destroyListScan')->name('list.scan.destroy');
            Route::post('/list/{event}/assignments/{tableAssignment}/scan', 'storeListScan')->name('list.scan');
            Route::post('/', 'storage')->name('storage');
            Route::post('/manual', 'storeManual')->name('manual');
            Route::get('/assignments/search', 'searchAssignments')->name('assignments.search');
        });

    Route::prefix('scans')
        ->name('scans.')
        ->controller(ScanController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');

            Route::middleware('elevated')->group(function () {
                Route::get('/{scan}/edit', 'edit')->name('edit');
                Route::put('/{scan}', 'update')->name('update');
                Route::delete('/scans/{scan}', 'destroy')->name('destroy');
            });
        });

    Route::middleware('elevated')->group(function () {
        Route::get('/events/export/scans-by-event', [EventController::class, 'exportScansByEvent'])->name('events.export-scans-by-event');
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
