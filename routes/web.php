<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\MasterCutiController;
use App\Http\Controllers\PenilaianPegawaiController;
use App\Http\Controllers\LaporanReimbursementController;
use App\Http\Controllers\MasterAssetController;
use App\Http\Controllers\MasterRestaurantController;
use App\Http\Controllers\LunchEventController;
use App\Http\Controllers\LunchEventUserOrderController;
use App\Http\Controllers\UserOrderDetailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LemburController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    Route::patch('reimbursements/{reimbursement}/approve', [App\Http\Controllers\ReimbursementController::class, 'approve'])->name('reimbursements.approve');
    Route::patch('reimbursements/{reimbursement}/reject', [ReimbursementController::class, 'reject'])->name('reimbursements.reject');
    Route::patch('reimbursements/{reimbursement}/pending', [ReimbursementController::class, 'pending'])->name('reimbursements.pending');
    Route::patch('reimbursements/{reimbursement}/done', [ReimbursementController::class, 'done'])->name('reimbursements.done');
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

    // Lembur (Overtime) Routes
    Route::get('lembur', [LemburController::class, 'index'])->name('lembur.index');
    Route::get('lembur/create', [LemburController::class, 'create'])->name('lembur.create');
    Route::post('lembur', [LemburController::class, 'store'])->name('lembur.store');
    Route::get('lembur/{lembur}', [LemburController::class, 'show'])->name('lembur.show');
    Route::get('lembur/{lembur}/edit', [LemburController::class, 'edit'])->name('lembur.edit');
    Route::put('lembur/{lembur}', [LemburController::class, 'update'])->name('lembur.update');
    Route::delete('lembur/{lembur}', [LemburController::class, 'destroy'])->name('lembur.destroy');
    Route::patch('lembur/{lembur}/approve', [LemburController::class, 'approve'])->name('lembur.approve');
    Route::patch('lembur/{lembur}/reject', [LemburController::class, 'reject'])->name('lembur.reject');
    Route::get('lembur/{lembur}/print', [LemburController::class, 'print'])->name('lembur.print');
    

    Route::resource('master-restaurants', MasterRestaurantController::class)->names('master-restaurants');
    Route::resource('lunch-events', LunchEventController::class)->names('lunch-events');
    Route::resource('lunch-event-user-orders', LunchEventUserOrderController::class)->except(['create', 'store']);
    Route::get('lunch-events/{lunchEvent}/order', [LunchEventUserOrderController::class, 'create'])->name('lunch-event-user-orders.create');
    Route::post('lunch-events/{lunchEvent}/order', [LunchEventUserOrderController::class, 'store'])->name('lunch-event-user-orders.store');
    // user-order-details.create
    Route::get('lunch-event-user-orders/{lunchEventUserOrder}/details/create', [UserOrderDetailController::class, 'create'])->name('user-order-details.create');
    Route::post('lunch-event-user-orders/{lunchEventUserOrder}/details', [UserOrderDetailController::class, 'store'])->name('user-order-details.store');
    Route::resource('user-order-details', UserOrderDetailController::class)->except(['create', 'store']);
    Route::put('/lunch-events/{lunchEvent}/items', [LunchEventUserOrderController::class, 'updateItem'])->name('lunch-event-user-orders.update-item');
    Route::delete('/lunch-events/{lunchEvent}/items', [LunchEventUserOrderController::class, 'destroyItem'])->name('lunch-event-user-orders.destroy-item');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    //route ajax_index
    Route::get('ajax-cuti-details', [CutiController::class, 'ajax_index'])->name('ajax-cuti-details');
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('penilaian', PenilaianPegawaiController::class)->names('penilaian');
    Route::get('penilaian/{penilaian}/print', [PenilaianPegawaiController::class, 'print'])->name('penilaian.print');
    Route::resource('master-cuti', MasterCutiController::class)->except(['show']);
    Route::resource('master-assets', MasterAssetController::class)->names('master-assets');
    Route::get('laporan-reimbursements', [LaporanReimbursementController::class, 'index'])->name('laporan-reimbursements.index');
    Route::get('laporan-reimbursements/create', [LaporanReimbursementController::class, 'create'])->name('laporan-reimbursements.create');
    Route::get('laporan-reimbursements/search', [LaporanReimbursementController::class, 'search'])->name('laporan-reimbursements.search');
    Route::post('laporan-reimbursements/generate', [LaporanReimbursementController::class, 'generate'])->name('laporan-reimbursements.generate');
    Route::post('laporan-reimbursements', [LaporanReimbursementController::class, 'store'])->name('laporan-reimbursements.store');
    Route::get('laporan-reimbursements/{laporanReimbursement}', [LaporanReimbursementController::class, 'show'])->name('laporan-reimbursements.show');
    // edit
    Route::get('laporan-reimbursements/{laporanReimbursement}/edit', [LaporanReimbursementController::class, 'edit'])->name('laporan-reimbursements.edit');
    Route::put('laporan-reimbursements/{laporanReimbursement}', [LaporanReimbursementController::class, 'update'])->name('laporan-reimbursements.update');
    Route::delete('laporan-reimbursements/{laporanReimbursement}', [LaporanReimbursementController::class, 'destroy'])->name('laporan-reimbursements.destroy');
    Route::put('/lunch-events/{lunchEvent}/items', [LunchEventUserOrderController::class, 'updateItem'])->name('lunch-event-user-orders.update-item');
    Route::delete('/lunch-events/{lunchEvent}/items', [LunchEventUserOrderController::class, 'destroyItem'])->name('lunch-event-user-orders.destroy-item');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('reimbursements/{reimbursement}', [ReimbursementController::class, 'destroy'])->name('reimbursements.destroy');
});

require __DIR__.'/auth.php';
