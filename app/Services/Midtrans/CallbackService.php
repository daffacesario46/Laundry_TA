<?php

namespace App\Services\Midtrans;

use App\Models\Pembayaran;
use Midtrans\Notification;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $pembayaran;
    protected $serverKey;

    public function __construct()
    {
        parent::__construct();
        $this->serverKey = config('midtrans.server_key');
        $this->_handleNotification();
    }

    public function isSignatureKeyVerified()
    {
        return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($this->notification->fraud_status) ? ($this->notification->fraud_status == 'accept') : true;

        return ($statusCode == 200 && $fraudStatus && ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }

    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function getPembayaran()
    {
        return $this->pembayaran;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    protected function _createLocalSignatureKey()
    {
        $orderId = $this->notification->order_id;
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->notification->gross_amount;
        $serverKey = $this->serverKey;
        
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');

        return $signature;
    }

    protected function _handleNotification()
    {
        try {
            // Log untuk debugging
            \Log::info('CallbackService: Starting notification handling');
            
            $notification = new Notification();
            
            // Log notification data
            \Log::info('CallbackService: Notification received', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'status_code' => $notification->status_code
            ]);

            $orderId = $notification->order_id;
            
            // Parse order_id dengan lebih aman
            $parts = explode('-', $orderId);
            
            if (count($parts) < 2) {
                \Log::error('CallbackService: Invalid order_id format', ['order_id' => $orderId]);
                throw new \Exception('Invalid order_id format: ' . $orderId);
            }
            
            $pembayaranId = $parts[1];
            \Log::info('CallbackService: Looking for pembayaran', ['pembayaran_id' => $pembayaranId]);
            
            $pembayaran = Pembayaran::find($pembayaranId);
            
            // Throw exception jika pembayaran tidak ditemukan
            if (!$pembayaran) {
                \Log::error('CallbackService: Pembayaran not found', ['pembayaran_id' => $pembayaranId]);
                throw new \Exception('Pembayaran not found with ID: ' . $pembayaranId);
            }
            
            \Log::info('CallbackService: Pembayaran found', [
                'pembayaran_id' => $pembayaran->pembayaran_id,
                'status' => $pembayaran->status_bayar
            ]);

            $this->notification = $notification;
            $this->pembayaran = $pembayaran;
            
        } catch (\Exception $e) {
            \Log::error('CallbackService: Exception in _handleNotification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
