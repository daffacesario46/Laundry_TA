<?php

namespace App\Services\Midtrans;

use Midtrans\Config;
use Midtrans\Snap;

class Midtrans
{
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');

        $this->_configureMidtrans();
    }

    protected function _configureMidtrans()
    {
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }

    /**
     * Create snap token untuk pembayaran
     */
    public function createSnapToken($order, $pelanggan)
    {
        $pembayaran = $order->pembayaran;
        
        if (!$pembayaran) {
            throw new \Exception('Data pembayaran tidak ditemukan');
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pembayaran->pembayaran_id . '-' . time(),
                'gross_amount' => (int) $pembayaran->jumlah_bayar,
            ],
            'item_details' => [
                [
                    'id' => $order->cucian_id,
                    'price' => (int) $pembayaran->jumlah_bayar,
                    'quantity' => 1,
                    'name' => 'Pembayaran Laundry - ' . ($order->kode_cucian ?? 'ORD-' . $order->cucian_id),
                ]
            ],
            'customer_details' => [
                'first_name' => $pelanggan->nama_pelanggan,
                'email' => $pelanggan->email ?? 'customer@laundry.com',
                'phone' => $pelanggan->telepon ?? '08123456789',
            ]
        ];

        return Snap::getSnapToken($params);
    }
}
