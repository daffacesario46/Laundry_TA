<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    // Dummy data cucian untuk tracking
    private function getAllCucian()
    {
        return collect([
            (object)[
                'id' => 1,
                'no_order' => 'WW001',
                'pelanggan_id' => 1,
                'nama_pelanggan' => 'Budi Santoso',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 3.5,
                'total_harga' => 35000,
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-08 09:00:00',
                'tanggal_proses' => '2024-12-08 10:30:00',
                'tanggal_selesai' => null,
                'tanggal_diambil' => null,
                'estimasi_selesai' => '2024-12-09 17:00:00',
                'catatan' => 'Tolong hati-hati dengan baju putih',
                'staff_name' => 'Siti Nurhaliza'
            ],
            (object)[
                'id' => 2,
                'no_order' => 'WW002',
                'pelanggan_id' => 2,
                'nama_pelanggan' => 'Siti Aminah',
                'no_telp' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 5.0,
                'total_harga' => 40000,
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-07 14:30:00',
                'tanggal_proses' => '2024-12-07 15:00:00',
                'tanggal_selesai' => '2024-12-08 10:00:00',
                'tanggal_diambil' => null,
                'estimasi_selesai' => '2024-12-08 14:30:00',
                'catatan' => '',
                'staff_name' => 'Ahmad Fauzi'
            ],
            (object)[
                'id' => 3,
                'no_order' => 'WW003',
                'pelanggan_id' => 3,
                'nama_pelanggan' => 'Ahmad Dahlan',
                'no_telp' => '083456789012',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan',
                'jenis_layanan' => 'Setrika Saja',
                'berat' => 2.0,
                'total_harga' => 15000,
                'status' => 'diambil',
                'tanggal_masuk' => '2024-12-06 10:15:00',
                'tanggal_proses' => '2024-12-06 11:00:00',
                'tanggal_selesai' => '2024-12-07 16:00:00',
                'tanggal_diambil' => '2024-12-07 17:30:00',
                'estimasi_selesai' => '2024-12-07 10:15:00',
                'catatan' => '',
                'staff_name' => 'Dewi Lestari'
            ],
            (object)[
                'id' => 4,
                'no_order' => 'WW004',
                'pelanggan_id' => 4,
                'nama_pelanggan' => 'Rina Wati',
                'no_telp' => '084567890123',
                'alamat' => 'Jl. Thamrin No. 90, Jakarta Pusat',
                'jenis_layanan' => 'Cuci + Setrika Express',
                'berat' => 4.5,
                'total_harga' => 67500,
                'status' => 'menunggu',
                'tanggal_masuk' => '2024-12-08 08:20:00',
                'tanggal_proses' => null,
                'tanggal_selesai' => null,
                'tanggal_diambil' => null,
                'estimasi_selesai' => '2024-12-09 08:20:00',
                'catatan' => 'Express 24 jam',
                'staff_name' => null
            ],
        ]);
    }

    // Halaman form tracking
    public function index()
    {
        return view('tracking.index');
    }

    // Proses tracking
    public function track(Request $request)
    {
        $request->validate([
            'no_order' => 'required|string'
        ]);

        $no_order = strtoupper(trim($request->no_order));
        $cucian = $this->getAllCucian()->firstWhere('no_order', $no_order);

        if (!$cucian) {
            return redirect()->route('tracking.index')
                ->with('error', 'No Order tidak ditemukan! Pastikan No Order yang Anda masukkan benar.');
        }

        return view('tracking.result', compact('cucian'));
    }
}