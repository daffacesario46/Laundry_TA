<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        // Dummy data statistik pelanggan
        $stats = [
            'total_order' => 15,
            'sedang_proses' => 3,
            'selesai' => 10,
            'menunggu_diambil' => 2
        ];
        
        // Dummy data order terbaru
        $recentOrders = collect([
            (object)[
                'no_order' => 'WW001',
                'tanggal' => '2024-12-08 09:00:00',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 3.5,
                'status' => 'proses',
                'total_harga' => 35000
            ],
            (object)[
                'no_order' => 'WW002',
                'tanggal' => '2024-12-07 14:30:00',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 5.0,
                'status' => 'selesai',
                'total_harga' => 40000
            ],
            (object)[
                'no_order' => 'WW003',
                'tanggal' => '2024-12-06 10:15:00',
                'jenis_layanan' => 'Setrika Saja',
                'berat' => 2.0,
                'status' => 'diambil',
                'total_harga' => 15000
            ],
        ]);
        
        return view('pelanggan.dashboard', compact('stats', 'recentOrders'));
    }
}