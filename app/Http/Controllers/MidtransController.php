<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cucian;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap Token for Payment
     */
    public function createSnapToken($cucianId)
    {
        $cucian = Cucian::with(['pelanggan.users', 'layanan', 'pembayaran'])->findOrFail($cucianId);

        // Check if payment already exists
        if (!$cucian->pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ], 404);
        }

        $pembayaran = $cucian->pembayaran;

        // Prepare transaction details
        $transactionDetails = [
            'order_id' => 'WW-' . $cucian->cucian_id . '-' . time(),
            'gross_amount' => (int) $cucian->total_harga,
        ];

        // Customer details
        $customerDetails = [
            'first_name' => $cucian->pelanggan->nama ?? 'Customer',
            'email' => $cucian->pelanggan->users->email ?? 'customer@washwes.com',
            'phone' => $cucian->pelanggan->no_telp ?? '08123456789',
        ];

        // Item details
        $itemDetails = [
            [
                'id' => 'LAUNDRY-' . $cucian->cucian_id,
                'price' => (int) $cucian->total_harga,
                'quantity' => 1,
                'name' => $cucian->layanan->nama_layanan ?? 'Layanan Laundry',
            ]
        ];

        // Transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Save snap token to database
            $pembayaran->update([
                'snap_token' => $snapToken,
                'transaction_id' => $transactionDetails['order_id']
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $transactionDetails['order_id']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans Callback/Notification
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;

            // Find payment by transaction_id
            $pembayaran = Pembayaran::where('transaction_id', $orderId)->first();

            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->updatePaymentStatus($pembayaran, 'settlement', 'lunas', $notification->payment_type);
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updatePaymentStatus($pembayaran, 'settlement', 'lunas', $notification->payment_type);
            } elseif ($transactionStatus == 'pending') {
                $this->updatePaymentStatus($pembayaran, 'pending', 'belum', $notification->payment_type);
            } elseif ($transactionStatus == 'deny') {
                $this->updatePaymentStatus($pembayaran, 'deny', 'belum', $notification->payment_type);
            } elseif ($transactionStatus == 'expire') {
                $this->updatePaymentStatus($pembayaran, 'expire', 'belum', $notification->payment_type);
            } elseif ($transactionStatus == 'cancel') {
                $this->updatePaymentStatus($pembayaran, 'cancel', 'belum', $notification->payment_type);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Payment Status
     */
    private function updatePaymentStatus($pembayaran, $transactionStatus, $statusBayar, $paymentType = null)
    {
        $pembayaran->update([
            'transaction_status' => $transactionStatus,
            'status_bayar' => $statusBayar,
            'payment_type' => $paymentType,
            'metode_bayar' => $paymentType ?? $pembayaran->metode_bayar,
            'tgl_bayar' => $statusBayar === 'lunas' ? now() : $pembayaran->tgl_bayar
        ]);
    }

    /**
     * Payment Finish Page
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        return view('payment.finish', compact('orderId', 'statusCode', 'transactionStatus'));
    }

    /**
     * Update payment status from frontend (after Snap JS success)
     * Ini dipanggil dari JavaScript setelah pembayaran berhasil di Midtrans
     */
    public function updateStatusFromFrontend(Request $request)
    {
        try {
            $pembayaranId = $request->pembayaran_id;
            $transactionId = $request->transaction_id;
            $paymentType = $request->payment_type ?? 'unknown';
            
            $pembayaran = Pembayaran::findOrFail($pembayaranId);
            
            // Update status pembayaran
            $pembayaran->update([
                'status_bayar' => 'lunas',
                'transaction_status' => 'settlement',
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'metode_bayar' => $paymentType,
                'tgl_bayar' => now()
            ]);
            
            \Log::info('Payment updated from frontend', [
                'pembayaran_id' => $pembayaranId,
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating payment from frontend: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}