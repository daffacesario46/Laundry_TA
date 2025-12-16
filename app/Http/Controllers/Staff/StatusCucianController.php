<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusCucianController extends Controller
{
    // Dummy data cucian
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
                'status' => 'menunggu',
                'tanggal_masuk' => '2024-12-08 09:00:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-09 17:00:00',
                'catatan' => 'Tolong hati-hati dengan baju putih'
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
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-07 14:30:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-08 14:30:00',
                'catatan' => ''
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
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-06 10:15:00',
                'tanggal_selesai' => '2024-12-07 16:00:00',
                'estimasi_selesai' => '2024-12-07 10:15:00',
                'catatan' => ''
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
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-06 08:20:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-07 08:20:00',
                'catatan' => 'Express 24 jam'
            ],
            (object)[
                'id' => 5,
                'no_order' => 'WW005',
                'pelanggan_id' => 5,
                'nama_pelanggan' => 'Joko Widodo',
                'no_telp' => '085678901234',
                'alamat' => 'Jl. Kuningan No. 12, Jakarta Selatan',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 6.0,
                'total_harga' => 48000,
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-05 13:10:00',
                'tanggal_selesai' => '2024-12-06 10:00:00',
                'estimasi_selesai' => '2024-12-06 13:10:00',
                'catatan' => ''
            ],
            (object)[
                'id' => 6,
                'no_order' => 'WW006',
                'pelanggan_id' => 6,
                'nama_pelanggan' => 'Dewi Lestari',
                'no_telp' => '086789012345',
                'alamat' => 'Jl. Casablanca No. 56, Jakarta Selatan',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 3.0,
                'total_harga' => 30000,
                'status' => 'menunggu',
                'tanggal_masuk' => '2024-12-05 11:30:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-07 11:30:00',
                'catatan' => ''
            ],
            (object)[
                'id' => 7,
                'no_order' => 'WW007',
                'pelanggan_id' => 7,
                'nama_pelanggan' => 'Hendra Gunawan',
                'no_telp' => '087890123456',
                'alamat' => 'Jl. Rasuna Said No. 34, Jakarta Selatan',
                'jenis_layanan' => 'Cuci Kering',
                'berat' => 4.0,
                'total_harga' => 32000,
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-04 15:00:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-06 15:00:00',
                'catatan' => ''
            ],
            (object)[
                'id' => 8,
                'no_order' => 'WW008',
                'pelanggan_id' => 8,
                'nama_pelanggan' => 'Maya Sari',
                'no_telp' => '088901234567',
                'alamat' => 'Jl. TB Simatupang No. 89, Jakarta Selatan',
                'jenis_layanan' => 'Setrika Saja',
                'berat' => 2.5,
                'total_harga' => 18750,
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-04 08:45:00',
                'tanggal_selesai' => '2024-12-05 14:30:00',
                'estimasi_selesai' => '2024-12-05 08:45:00',
                'catatan' => ''
            ],
            (object)[
                'id' => 9,
                'no_order' => 'WW009',
                'pelanggan_id' => 9,
                'nama_pelanggan' => 'Bambang Suryanto',
                'no_telp' => '089012345678',
                'alamat' => 'Jl. HR Rasuna Said No. 100, Jakarta Selatan',
                'jenis_layanan' => 'Cuci + Setrika',
                'berat' => 5.5,
                'total_harga' => 55000,
                'status' => 'menunggu',
                'tanggal_masuk' => '2024-12-03 17:20:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-05 17:20:00',
                'catatan' => ''
            ],
            (object)[
                'id' => 10,
                'no_order' => 'WW010',
                'pelanggan_id' => 10,
                'nama_pelanggan' => 'Putri Handayani',
                'no_telp' => '081123456789',
                'alamat' => 'Jl. Senopati No. 67, Jakarta Selatan',
                'jenis_layanan' => 'Cuci Kering Express',
                'berat' => 3.5,
                'total_harga' => 52500,
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-03 12:00:00',
                'tanggal_selesai' => null,
                'estimasi_selesai' => '2024-12-04 12:00:00',
                'catatan' => ''
            ],
        ]);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        $currentPage = $request->get('page', 1);
        
        $allData = $this->getAllCucian();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allData = $allData->filter(function($item) use ($search) {
                return str_contains(strtolower($item->no_order), $search) ||
                       str_contains(strtolower($item->nama_pelanggan), $search);
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->status === $request->status;
            });
        }
        
        // Buat paginator manual
        $total = $allData->count();
        $items = $allData->forPage($currentPage, $perPage)->values();
        
        $cucian = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        // Hitung statistik
        $allCucian = $this->getAllCucian();
        $totalMenunggu = $allCucian->where('status', 'menunggu')->count();
        $totalProses = $allCucian->where('status', 'proses')->count();
        $totalSelesai = $allCucian->where('status', 'selesai')->count();
        $totalDiambil = $allCucian->where('status', 'diambil')->count();
        
        return view('staff.status-cucian.index', compact(
            'cucian',
            'totalMenunggu',
            'totalProses', 
            'totalSelesai',
            'totalDiambil'
        ));
    }

    public function konfirmasi(Request $request, $id)
    {
        // Simulasi konfirmasi status
        $status = $request->status;
        
        return redirect()->route('staff.status-cucian.index')
            ->with('success', "Status cucian berhasil diubah menjadi {$status}!");
    }

    public function updateStatus(Request $request, $id)
    {
        // Simulasi update status
        return redirect()->route('staff.status-cucian.index')
            ->with('success', 'Status cucian berhasil diupdate!');
    }
}