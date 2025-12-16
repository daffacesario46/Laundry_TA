<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        $currentPage = $request->get('page', 1);
        
        // Dummy data cucian selesai
        $allData = collect([
            (object)[
                'no_order' => 'ONLINE001',
                'user' => (object)['nama' => 'Budi Santoso'],
                'atas_nama' => 'Budi Santoso',
                'total_item' => 5,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-05 14:30:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'ONLINE002',
                'user' => (object)['nama' => 'Siti Aminah'],
                'atas_nama' => 'Siti Aminah',
                'total_item' => 8,
                'jenis_ambil' => 'ambil sendiri',
                'wkt_diambil' => '2024-12-05 10:15:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE001',
                'user' => (object)['nama' => 'Ahmad Dahlan'],
                'atas_nama' => 'Ahmad Dahlan',
                'total_item' => 3,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-04 16:45:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE003',
                'user' => (object)['nama' => 'Rina Wati'],
                'atas_nama' => 'Rina Wati',
                'total_item' => 12,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-04 09:20:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE002',
                'user' => (object)['nama' => 'Joko Widodo'],
                'atas_nama' => 'Joko Widodo',
                'total_item' => 6,
                'jenis_ambil' => 'ambil sendiri',
                'wkt_diambil' => '2024-12-03 13:10:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE004',
                'user' => (object)['nama' => 'Dewi Lestari'],
                'atas_nama' => 'Dewi Lestari',
                'total_item' => 15,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-03 11:30:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE003',
                'user' => (object)['nama' => 'Hendra Gunawan'],
                'atas_nama' => 'Hendra Gunawan',
                'total_item' => 7,
                'jenis_ambil' => 'ambil sendiri',
                'wkt_diambil' => '2024-12-02 15:00:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE005',
                'user' => (object)['nama' => 'Maya Sari'],
                'atas_nama' => 'Maya Sari',
                'total_item' => 10,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-02 08:45:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE004',
                'user' => (object)['nama' => 'Bambang Suryanto'],
                'atas_nama' => 'Bambang Suryanto',
                'total_item' => 4,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-12-01 17:20:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE006',
                'user' => (object)['nama' => 'Putri Handayani'],
                'atas_nama' => 'Putri Handayani',
                'total_item' => 9,
                'jenis_ambil' => 'ambil sendiri',
                'wkt_diambil' => '2024-12-01 12:00:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE005',
                'user' => (object)['nama' => 'Agus Setiawan'],
                'atas_nama' => 'Agus Setiawan',
                'total_item' => 11,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-11-30 14:15:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE007',
                'user' => (object)['nama' => 'Lina Marlina'],
                'atas_nama' => 'Lina Marlina',
                'total_item' => 13,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-11-30 10:30:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE006',
                'user' => (object)['nama' => 'Rudi Hartono'],
                'atas_nama' => 'Rudi Hartono',
                'total_item' => 5,
                'jenis_ambil' => 'ambil sendiri',
                'wkt_diambil' => '2024-11-29 16:45:00',
                'jenis_order' => 'offline'
            ],
            (object)[
                'no_order' => 'ONLINE008',
                'user' => (object)['nama' => 'Tari Wulandari'],
                'atas_nama' => 'Tari Wulandari',
                'total_item' => 8,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-11-29 09:00:00',
                'jenis_order' => 'online'
            ],
            (object)[
                'no_order' => 'OFFLINE007',
                'user' => (object)['nama' => 'Eko Prasetyo'],
                'atas_nama' => 'Eko Prasetyo',
                'total_item' => 6,
                'jenis_ambil' => 'diantar',
                'wkt_diambil' => '2024-11-28 13:20:00',
                'jenis_order' => 'offline'
            ],
        ]);
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allData = $allData->filter(function($item) use ($search) {
                return str_contains(strtolower($item->no_order), $search) ||
                       str_contains(strtolower($item->atas_nama), $search) ||
                       str_contains(strtolower($item->user->nama), $search);
            });
        }
        
        // Filter berdasarkan jenis order
        if ($request->filled('jenis_order')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->jenis_order === $request->jenis_order;
            });
        }
        
        // Filter berdasarkan jenis ambil
        if ($request->filled('jenis_ambil')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->jenis_ambil === $request->jenis_ambil;
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
        
        $jenis_order = $request->query('jenis_order', 'Selesai');
        
        return view('admin.dashboard.index', compact('cucian', 'jenis_order'));
    }
    
    public function detail($no_order)
    {
        // Dummy data detail cucian
        $cucian = (object)[
            'no_order' => $no_order,
            'user' => (object)['nama' => 'Budi Santoso', 'telp' => '081234567890'],
            'atas_nama' => 'Budi Santoso',
            'total_item' => 5,
            'total_berat' => 3.5,
            'jenis_ambil' => 'diantar',
            'alamat_ambil' => 'Jl. Merdeka No. 123, Jakarta Selatan',
            'wkt_diambil' => '2024-12-05 14:30:00',
            'wkt_diterima' => '2024-12-02 09:00:00',
            'status' => 'selesai',
            'jenis_order' => 'online',
            'catatan' => 'Tolong hati-hati dengan baju putihnya',
            'total_harga' => 45000,
            'items' => [
                (object)[
                    'nama_item' => 'Kemeja Putih',
                    'jumlah' => 2,
                    'layanan' => 'Cuci + Setrika',
                    'harga' => 10000
                ],
                (object)[
                    'nama_item' => 'Celana Jeans',
                    'jumlah' => 1,
                    'layanan' => 'Cuci + Setrika',
                    'harga' => 15000
                ],
                (object)[
                    'nama_item' => 'Kaos',
                    'jumlah' => 2,
                    'layanan' => 'Cuci Saja',
                    'harga' => 10000
                ],
            ]
        ];
        
        return view('admin.dashboard.detail', compact('cucian'));
    }
}