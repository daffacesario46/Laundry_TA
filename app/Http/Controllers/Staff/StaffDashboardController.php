<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index()
    {
        // Dummy data cucian
        $allCucian = collect([
            (object)[
                'id' => 1,
                'no_order' => 'WW001',
                'pelanggan_id' => 1,
                'nama_pelanggan' => 'Budi Santoso',
                'no_telp' => '081234567890',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 3.5,
                'status' => 'menunggu',
                'total_harga' => 35000,
                'created_at' => '2024-12-08 09:00:00'
            ],
            (object)[
                'id' => 2,
                'no_order' => 'WW002',
                'pelanggan_id' => 2,
                'nama_pelanggan' => 'Siti Aminah',
                'no_telp' => '082345678901',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 5.0,
                'status' => 'proses',
                'total_harga' => 40000,
                'created_at' => '2024-12-07 14:30:00'
            ],
            (object)[
                'id' => 3,
                'no_order' => 'WW003',
                'pelanggan_id' => 3,
                'nama_pelanggan' => 'Ahmad Dahlan',
                'no_telp' => '083456789012',
                'jenis_layanan' => 'Setrika Saja',
                'berat' => 2.0,
                'status' => 'selesai',
                'total_harga' => 15000,
                'created_at' => '2024-12-06 10:15:00'
            ],
            (object)[
                'id' => 4,
                'no_order' => 'WW004',
                'pelanggan_id' => 4,
                'nama_pelanggan' => 'Rina Wati',
                'no_telp' => '084567890123',
                'jenis_layanan' => 'Cuci + Setrika Express',
                'berat' => 4.5,
                'status' => 'proses',
                'total_harga' => 67500,
                'created_at' => '2024-12-06 08:20:00'
            ],
            (object)[
                'id' => 5,
                'no_order' => 'WW005',
                'pelanggan_id' => 5,
                'nama_pelanggan' => 'Joko Widodo',
                'no_telp' => '085678901234',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 6.0,
                'status' => 'selesai',
                'total_harga' => 48000,
                'created_at' => '2024-12-05 13:10:00'
            ],
            (object)[
                'id' => 6,
                'no_order' => 'WW006',
                'pelanggan_id' => 6,
                'nama_pelanggan' => 'Dewi Lestari',
                'no_telp' => '086789012345',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 3.0,
                'status' => 'menunggu',
                'total_harga' => 30000,
                'created_at' => '2024-12-05 11:30:00'
            ],
            (object)[
                'id' => 7,
                'no_order' => 'WW007',
                'pelanggan_id' => 7,
                'nama_pelanggan' => 'Hendra Gunawan',
                'no_telp' => '087890123456',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 4.0,
                'status' => 'proses',
                'total_harga' => 32000,
                'created_at' => '2024-12-04 15:00:00'
            ],
            (object)[
                'id' => 8,
                'no_order' => 'WW008',
                'pelanggan_id' => 8,
                'nama_pelanggan' => 'Maya Sari',
                'no_telp' => '088901234567',
                'jenis_layanan' => 'Setrika Saja',
                'berat' => 2.5,
                'status' => 'selesai',
                'total_harga' => 18750,
                'created_at' => '2024-12-04 08:45:00'
            ],
            (object)[
                'id' => 9,
                'no_order' => 'WW009',
                'pelanggan_id' => 9,
                'nama_pelanggan' => 'Bambang Suryanto',
                'no_telp' => '089012345678',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 5.5,
                'status' => 'menunggu',
                'total_harga' => 55000,
                'created_at' => '2024-12-03 17:20:00'
            ],
            (object)[
                'id' => 10,
                'no_order' => 'WW010',
                'pelanggan_id' => 10,
                'nama_pelanggan' => 'Putri Handayani',
                'no_telp' => '081123456789',
                'jenis_layanan' => 'Cuci Kering Express',
                'berat' => 3.5,
                'status' => 'proses',
                'total_harga' => 52500,
                'created_at' => '2024-12-03 12:00:00'
            ],
        ]);
        
        // Statistik untuk dashboard
        $totalCucian = $allCucian->count();
        $cucianProses = $allCucian->where('status', 'proses')->count();
        $cucianSelesai = $allCucian->where('status', 'selesai')->count();
        $cucianMenunggu = $allCucian->where('status', 'menunggu')->count();
        
        // Dummy data pelanggan
        $totalPelanggan = 25;
        
        // Cucian terbaru (10 data terakhir)
        $cucianTerbaru = $allCucian->sortByDesc('created_at')->take(10);
        
        // Cucian yang perlu dikonfirmasi (status menunggu)
        $cucianMenungguKonfirmasi = $allCucian->where('status', 'menunggu')->sortByDesc('created_at');

        return view('staff.dashboard', compact(
            'totalCucian',
            'cucianProses',
            'cucianSelesai',
            'cucianMenunggu',
            'totalPelanggan',
            'cucianTerbaru',
            'cucianMenungguKonfirmasi'
        ));
    }
}