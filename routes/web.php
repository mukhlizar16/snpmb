<?php

use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/siswa', [DataSiswaController::class, 'index'])->name('siswa');
        Route::get('/siswa/json', [DataSiswaController::class, 'json'])->name('siswa.json');
        Route::get('/siswa/export', [DataSiswaController::class, 'export'])->name('siswa.export');
        Route::get('/siswa/export-excel', [DataSiswaController::class, 'exportExcel'])->name('siswa.export-excel');
    });

    Route::prefix('import')->name('import.')->controller(ImportController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            // AJAX endpoint — semua tipe import lewat sini
            Route::post('/ajax', 'importAjax')->name('ajax');

            // Polling status
            Route::get('/status/{jobId}', 'importStatus')->name('status');

            // Download error log
            Route::get('/download-error/{file}', 'downloadError')->name('download-error');
        });
});

require __DIR__.'/auth.php';
