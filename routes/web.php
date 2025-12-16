<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ListHargaController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\StokBahanController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\KurirController;

// Import Staff Controllers
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\CucianController;
use App\Http\Controllers\Staff\PelangganStaffController;
use App\Http\Controllers\Staff\StatusCucianController;

// Import Pelanggan Controllers
use App\Http\Controllers\Pelanggan\PelangganDashboardController;
use App\Http\Controllers\Pelanggan\OrderController;
use App\Http\Controllers\Pelanggan\ProfileController;

// ==========================================
// HOME & AUTH (TEMPORARY - NO REAL AUTH)
// ==========================================

// Home
Route::get('/', function() {
    return view('welcome');
})->name('home');

// Login (Dummy - untuk development)
Route::get('/login', function() {
    return view('auth.login'); // Nanti kita buat view sederhana
})->name('login');
Route::get('/register', function() {
    return view('auth.register'); // Nanti kita buat view sederhana
})->name('register');

Route::post('/login', function() {
    // Dummy login - redirect ke admin dashboard
    return redirect()->route('admin.dashboard');
})->name('login.post');

// Logout (Dummy)
Route::post('/logout', function() {
    return redirect('/')->with('success', 'Logout berhasil!');
})->name('logout');

// Tracking Routes (Dummy)
Route::get('/tracking', function() {
    return view('tracking.index');
})->name('tracking.index');

Route::post('/tracking', function() {
    return redirect()->route('tracking.index')->with('error', 'Nomor order tidak ditemukan!');
})->name('tracking.track');

// ==========================================
// ADMIN VIEWS
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard 
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detail/{no_order}', [DashboardController::class, 'detail'])->name('detail');

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
        Route::post('/', [KurirController::class, 'store'])->name('store');
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

// ==========================================
// STAFF VIEWS
// ==========================================
Route::prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', function() {
        return view('staff.profile');
    })->name('profile');
    
    // CRUD Cucian
    Route::prefix('cucian')->name('cucian.')->group(function () {
        Route::get('/', [CucianController::class, 'index'])->name('index');
        Route::get('/create', [CucianController::class, 'create'])->name('create');
        Route::post('/', [CucianController::class, 'store'])->name('store');
        Route::get('/{id}', [CucianController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CucianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CucianController::class, 'update'])->name('update');
        Route::delete('/{id}', [CucianController::class, 'destroy'])->name('destroy');
    });
    
    // CRUD Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganStaffController::class, 'index'])->name('index');
        Route::get('/create', [PelangganStaffController::class, 'create'])->name('create');
        Route::post('/', [PelangganStaffController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PelangganStaffController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PelangganStaffController::class, 'update'])->name('update');
        Route::delete('/{id}', [PelangganStaffController::class, 'destroy'])->name('destroy');
    });
    
    // Status Cucian
    Route::prefix('status-cucian')->name('status-cucian.')->group(function () {
        Route::get('/', [StatusCucianController::class, 'index'])->name('index');
        Route::post('/{id}/konfirmasi', [StatusCucianController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/{id}/update-status', [StatusCucianController::class, 'updateStatus'])->name('update-status');
    });
});

// ==========================================
// KURIR VIEWS
// ==========================================
Route::prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', function() {
        return view('kurir.dashboard');
    })->name('dashboard');
});

// PELANGGAN VIEWS
Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    
    // Order Management
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    });
    
    // Profile Management - TAMBAHKAN INI
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
});