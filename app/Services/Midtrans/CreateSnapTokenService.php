<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $pembayaran;

    public function __construct($pembayaran)
    {
        parent::__construct();
        $this->pembayaran = $pembayaran;
    }

    public function getSnapToken()
    {
        $cucian = $this->pembayaran->cucian;
        $pelanggan = $cucian->pelanggan;
        
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $this->pembayaran->pembayaran_id . '-' . time(),
                'gross_amount' => (int) $this->pembayaran->jumlah_bayar,
            ],
            'item_details' => [
                [
                    'id' => $cucian->cucian_id,
                    'price' => (int) $this->pembayaran->jumlah_bayar,
                    'quantity' => 1,
                    'name' => 'Pembayaran Laundry - ' . $cucian->kode_cucian,
                ]
            ],
            'customer_details' => [
                'first_name' => $pelanggan->nama,
                'email' => $pelanggan->email ?? 'customer@laundry.com',
                'phone' => $pelanggan->no_hp ?? '08123456789',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        return $snapToken;
    }
}
