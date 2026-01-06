<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\PembayaranController;
use App\Http\Controllers\Pelanggan\OrderController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/payments/midtrans-notification', [PembayaranController::class, 'handleMidtransCallback']);
Route::post('/midtrans/notification', [OrderController::class, 'handleMidtransCallback'])
    ->name('midtrans.callback');