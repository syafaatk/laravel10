<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\MasterCutiController;
use App\Http\Controllers\LaporanReimbursementController;
use App\Http\Controllers\RoleController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Reimbursement Routes
    Route::get('reimbursements', [ReimbursementController::class, 'index'])->name('reimbursements.index');
    Route::get('reimbursements/create', [ReimbursementController::class, 'create'])->name('reimbursements.create');
    Route::post('reimbursements', [ReimbursementController::class, 'store'])->name('reimbursements.store');
    Route::get('reimbursements/{reimbursement}', [ReimbursementController::class, 'show'])->name('reimbursements.show');
    Route::patch('reimbursements/{reimbursement}/approve', [ReimbursementController::class, 'approve'])->name('reimbursements.approve');
    Route::patch('reimbursements/{reimbursement}/reject', [ReimbursementController::class, 'reject'])->name('reimbursements.reject');
    Route::patch('reimbursements/{reimbursement}/pending', [ReimbursementController::class, 'pending'])->name('reimbursements.pending');
    Route::get('reimbursements/{reimbursement}/download', [ReimbursementController::class, 'downloadAttachment'])->name('reimbursements.download');
    Route::get('reimbursements/{reimbursement}/download-note', [ReimbursementController::class, 'downloadNote'])->name('reimbursements.downloadNote');
    Route::post('reimbursements/print', [ReimbursementController::class, 'printSelected'])->name('reimbursements.print')->middleware('role:admin');

    // Cuti (Leave) Routes
    Route::get('cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::get('cuti/create', [CutiController::class, 'create'])->name('cuti.create');
    Route::post('cuti', [CutiController::class, 'store'])->name('cuti.store');
    Route::get('cuti/{cuti}', [CutiController::class, 'show'])->name('cuti.show');
    Route::get('cuti/{cuti}/print', [CutiController::class, 'print'])->name('cuti.print');
    Route::patch('cuti/{cuti}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
    Route::patch('cuti/{cuti}/reject', [CutiController::class, 'reject'])->name('cuti.reject');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('master-cuti', MasterCutiController::class)->except(['show']);
    Route::get('laporan-reimbursements', [LaporanReimbursementController::class, 'index'])->name('laporan-reimbursements.index');
    Route::post('laporan-reimbursements/generate', [LaporanReimbursementController::class, 'generate'])->name('laporan-reimbursements.generate');
    
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('reimbursements/{reimbursement}', [ReimbursementController::class, 'destroy'])->name('reimbursements.destroy');
});

require __DIR__.'/auth.php';
