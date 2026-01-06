<?php

use Illuminate\Support\Facades\Route;

// Import Controllers Admin
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ListHargaController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\StokBahanController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\KurirController;

//Import Controllers Staff
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\CucianController;
use App\Http\Controllers\Staff\PelangganStaffController;
use App\Http\Controllers\Staff\StatusCucianController;
use App\Http\Controllers\Staff\PembayaranController;
use App\Http\Controllers\Staff\PenjemputanController;
use App\Http\Controllers\Staff\PengantaranController;
use App\Http\Controllers\Staff\StaffProfileController;


// Import Controllers Pelanggan
use App\Http\Controllers\Pelanggan\PelangganDashboardController;
use App\Http\Controllers\Pelanggan\OrderController;
use App\Http\Controllers\Pelanggan\ProfileController;


// Import Controllers Kurir
use App\Http\Controllers\Kurir\KurirDashboardController;
use App\Http\Controllers\Kurir\KurirPenjemputanController;
use App\Http\Controllers\Kurir\KurirPengantaranController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication)
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function() {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Tracking Routes  
Route::get('/tracking', [App\Http\Controllers\TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [App\Http\Controllers\TrackingController::class, 'track'])->name('tracking.track');
Route::get('/tracking/api/{id}', [App\Http\Controllers\TrackingController::class, 'api'])->name('tracking.api');



/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard/detail/{cucian_id}', [DashboardController::class, 'detail'])->name('dashboard.detail');

    // LAPORAN KEUANGAN
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
    });

    // STAFF MANAGEMENT
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::post('/', [StaffController::class, 'store'])->name('store');
        Route::put('/{id}', [StaffController::class, 'update'])->name('update');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('toggle-status');
    });

    // KURIR MANAGEMENT
    Route::prefix('kurir')->name('kurir.')->group(function () {
        Route::get('/', [KurirController::class, 'index'])->name('index');
        Route::get('/create', [KurirController::class, 'create'])->name('create');
        Route::post('/', [KurirController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [KurirController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KurirController::class, 'update'])->name('update');
        Route::delete('/{id}', [KurirController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [KurirController::class, 'toggleStatus'])->name('toggle-status');
    });

    // List Harga Routes
    Route::prefix('list-harga')->name('list-harga.')->group(function () {
        Route::get('/', [ListHargaController::class, 'index'])->name('index');
        Route::get('/create', [ListHargaController::class, 'create'])->name('create');
        Route::post('/', [ListHargaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ListHargaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ListHargaController::class, 'update'])->name('update');
        Route::delete('/{id}', [ListHargaController::class, 'destroy'])->name('destroy');
    });
    
    // Layanan Routes 
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('index');
        Route::get('/create', [LayananController::class, 'create'])->name('create');
        Route::post('/', [LayananController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [LayananController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LayananController::class, 'update'])->name('update');
        Route::delete('/{id}', [LayananController::class, 'destroy'])->name('destroy');
    });
    
    // Stok Bahan Routes
    Route::prefix('stok-bahan')->name('stok-bahan.')->group(function () {
        Route::get('/', [StokBahanController::class, 'index'])->name('index');
        Route::get('/create', [StokBahanController::class, 'create'])->name('create');
        Route::post('/', [StokBahanController::class, 'store'])->name('store');
        Route::get('/{id}', [StokBahanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StokBahanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StokBahanController::class, 'update'])->name('update');
        Route::delete('/{id}', [StokBahanController::class, 'destroy'])->name('destroy');
    });
    
    // Profile & Settings
    Route::get('/profile', function() {
        return view('admin.profile');
    })->name('profile');
    
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');
});

/*
|--------------------------------------------------------------------------
| STAFF ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // PROFILE STAFF
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [App\Http\Controllers\Staff\ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('update-password');
        Route::delete('/delete-photo', [App\Http\Controllers\Staff\ProfileController::class, 'deletePhoto'])->name('delete-photo');
    });
    
    // CRUD Cucian
    Route::prefix('cucian')->name('cucian.')->group(function () {
        Route::get('/', [CucianController::class, 'index'])->name('index');
        Route::get('/create', [CucianController::class, 'create'])->name('create');
        Route::post('/', [CucianController::class, 'store'])->name('store');
        Route::get('/{id}', [CucianController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CucianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CucianController::class, 'update'])->name('update');
        Route::delete('/{id}', [CucianController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/input-berat', [CucianController::class, 'showInputBerat'])->name('input-berat');
        Route::put('/{id}/update-berat', [CucianController::class, 'updateBerat'])->name('update-berat');
    });
    
    // CRUD Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganStaffController::class, 'index'])->name('index');
        Route::get('/create', [PelangganStaffController::class, 'create'])->name('create');
        Route::post('/', [PelangganStaffController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PelangganStaffController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PelangganStaffController::class, 'update'])->name('update');
        Route::delete('/{id}', [PelangganStaffController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/delete-foto', [PelangganStaffController::class, 'deleteFoto'])->name('delete-foto');
    });
    
    // Status Cucian
    Route::prefix('status-cucian')->name('status-cucian.')->group(function () {
        Route::get('/', [StatusCucianController::class, 'index'])->name('index');
        Route::post('/{id}/konfirmasi', [StatusCucianController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/{id}/update-status', [StatusCucianController::class, 'updateStatus'])->name('update-status');
    });

    // PEMBAYARAN MANAGEMENT
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/{id}', [PembayaranController::class, 'show'])->name('show');
        
        // Process payment for offline customer
        Route::get('/cucian/{cucian_id}/bayar', [PembayaranController::class, 'showPaymentForm'])->name('form');
        Route::post('/cucian/{cucian_id}/proses', [PembayaranController::class, 'processPayment'])->name('process');
        
        // Validate transfer payment for online customer
        Route::get('/{id}/validate', [PembayaranController::class, 'showValidateForm'])->name('validate-form');
        Route::put('/{id}/validate', [PembayaranController::class, 'validatePayment'])->name('validate');
        
        // Delete/Cancel payment
        Route::delete('/{id}', [PembayaranController::class, 'destroy'])->name('destroy');
    });

    // PENJEMPUTAN MANAGEMENT
    Route::prefix('penjemputan')->name('penjemputan.')->group(function () {
        Route::get('/', [PenjemputanController::class, 'index'])->name('index');
        Route::get('/{id}', [PenjemputanController::class, 'show'])->name('show');
        
        // Assign kurir (create new)
        Route::get('/cucian/{cucian_id}/assign', [PenjemputanController::class, 'assignForm'])->name('assign-form');
        Route::post('/cucian/{cucian_id}/assign', [PenjemputanController::class, 'assign'])->name('assign');
        
        // Edit penjemputan (update existing) - TAMBAHAN BARU
        Route::get('/{id}/edit', [PenjemputanController::class, 'edit'])->name('edit');
        
        // Update status
        Route::put('/{id}/status', [PenjemputanController::class, 'updateStatus'])->name('update-status');
        
        // Upload foto
        Route::post('/{id}/upload-foto', [PenjemputanController::class, 'uploadFoto'])->name('upload-foto');
        
        // Delete
        Route::delete('/{id}', [PenjemputanController::class, 'destroy'])->name('destroy');
    });
    
    // PENGANTARAN MANAGEMENT
    Route::prefix('pengantaran')->name('pengantaran.')->group(function () {
        Route::get('/', [PengantaranController::class, 'index'])->name('index');
        Route::get('/{id}', [PengantaranController::class, 'show'])->name('show');
        
        // Assign kurir (create new)
        Route::get('/cucian/{cucian_id}/assign', [PengantaranController::class, 'assignForm'])->name('assign-form');
        Route::post('/cucian/{cucian_id}/assign', [PengantaranController::class, 'assign'])->name('assign');
        
        // Edit pengantaran (update existing) - TAMBAHAN BARU
        Route::get('/{id}/edit', [PengantaranController::class, 'edit'])->name('edit');
        
        // Update status
        Route::put('/{id}/status', [PengantaranController::class, 'updateStatus'])->name('update-status');
        
        // Upload foto
        Route::post('/{id}/upload-foto', [PengantaranController::class, 'uploadFoto'])->name('upload-foto');
        
        // Delete
        Route::delete('/{id}', [PengantaranController::class, 'destroy'])->name('destroy');
    });

    // PROFILE STAFF
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [StaffProfileController::class, 'index'])->name('index');
    Route::get('/edit', [StaffProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [StaffProfileController::class, 'update'])->name('update');
    Route::get('/change-password', [StaffProfileController::class, 'changePassword'])->name('change-password');
    Route::put('/update-password', [StaffProfileController::class, 'updatePassword'])->name('update-password');
    Route::delete('/delete-photo', [StaffProfileController::class, 'deletePhoto'])->name('delete-photo');
});
}); // TUTUP STAFF ROUTES


/*
|--------------------------------------------------------------------------
| KURIR ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('kurir')->name('kurir.')->middleware(['auth', 'role:kurir'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [KurirDashboardController::class, 'index'])->name('dashboard');
    
    // PENJEMPUTAN TASKS
    Route::prefix('penjemputan')->name('penjemputan.')->group(function () {
        Route::get('/', [KurirPenjemputanController::class, 'index'])->name('index');
        Route::get('/{id}', [KurirPenjemputanController::class, 'show'])->name('show');
        Route::post('/{id}/start', [KurirPenjemputanController::class, 'start'])->name('start');
        Route::post('/{id}/complete', [KurirPenjemputanController::class, 'complete'])->name('complete');
    });
    
    // PENGANTARAN TASKS
    Route::prefix('pengantaran')->name('pengantaran.')->group(function () {
        Route::get('/', [KurirPengantaranController::class, 'index'])->name('index');
        Route::get('/{id}', [KurirPengantaranController::class, 'show'])->name('show');
        Route::post('/{id}/start', [KurirPengantaranController::class, 'start'])->name('start');
        Route::post('/{id}/complete', [KurirPengantaranController::class, 'complete'])->name('complete');
    });
    
    // Profile
    Route::get('/profile', function() {
        return view('kurir.profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| PELANGGAN ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('pelanggan')->name('pelanggan.')->middleware(['auth', 'role:pelanggan'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    
    // Order Management
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/{id}/detail', [OrderController::class, 'detail'])->name('detail');
        
        // PAYMENT ROUTES
        Route::get('/{id}/upload-bukti', [OrderController::class, 'showUploadBukti'])->name('show-upload-bukti');
        Route::post('/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('upload-bukti');
        Route::get('/{id}/payment-status', [OrderController::class, 'paymentStatus'])->name('payment-status');
        
        Route::delete('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // Home/Landing
    Route::get('/home', function() {
        return view('pelanggan.home');
    })->name('home');
}); // TUTUP PELANGGAN ROUTES
    
// Midtrans Payment Routes
Route::post('/pembayaran/midtrans/create/{cucian_id}', [PembayaranController::class, 'createMidtransPayment'])
    ->name('staff.pembayaran.midtrans.create');

Route::get('/pembayaran/midtrans/{id}', [PembayaranController::class, 'showMidtransPayment'])
    ->name('staff.pembayaran.midtrans');

// Route::post('/payments/midtrans-notification', [PembayaranController::class, 'handleMidtransCallback']);
