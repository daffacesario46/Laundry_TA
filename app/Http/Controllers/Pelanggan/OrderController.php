<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    private static $orders = [];
    
    public function __construct()
    {
        if (empty(self::$orders)) {
            self::$orders = [
                1 => [
                    'id' => 1,
                    'no_order' => 'WW001',
                    'tanggal_order' => '2024-12-08 09:00:00',
                    'jenis_layanan' => 'Cuci + Setrika Express',
                    'berat' => 3.5,
                    'total_harga' => 35000,
                    'status' => 'proses',
                    'estimasi_selesai' => '2024-12-09 17:00:00',
                    'catatan' => 'Tolong hati-hati dengan baju putih',
                    'metode_pembayaran' => 'cash',
                    'status_pembayaran' => 'belum_bayar',
                    'jenis_pengambilan' => 'diantar',
                    'alamat_pengambilan' => 'Jl. Merdeka No. 123, Jakarta Selatan'
                ],
                2 => [
                    'id' => 2,
                    'no_order' => 'WW002',
                    'tanggal_order' => '2024-12-07 14:30:00',
                    'jenis_layanan' => 'Cuci Kering',
                    'berat' => 5.0,
                    'total_harga' => 40000,
                    'status' => 'selesai',
                    'estimasi_selesai' => '2024-12-08 14:30:00',
                    'catatan' => '',
                    'metode_pembayaran' => 'transfer',
                    'status_pembayaran' => 'sudah_bayar',
                    'jenis_pengambilan' => 'ambil_sendiri',
                    'alamat_pengambilan' => ''
                ],
                3 => [
                    'id' => 3,
                    'no_order' => 'WW003',
                    'tanggal_order' => '2024-12-06 10:15:00',
                    'jenis_layanan' => 'Setrika Saja',
                    'berat' => 2.0,
                    'total_harga' => 15000,
                    'status' => 'diambil',
                    'estimasi_selesai' => '2024-12-07 10:15:00',
                    'catatan' => '',
                    'metode_pembayaran' => 'cash',
                    'status_pembayaran' => 'sudah_bayar',
                    'jenis_pengambilan' => 'diantar',
                    'alamat_pengambilan' => 'Jl. Sudirman No. 45, Jakarta Pusat'
                ],
                4 => [
                    'id' => 4,
                    'no_order' => 'WW004',
                    'tanggal_order' => '2024-12-05 08:20:00',
                    'jenis_layanan' => 'Cuci + Setrika',
                    'berat' => 4.5,
                    'total_harga' => 45000,
                    'status' => 'selesai',
                    'estimasi_selesai' => '2024-12-07 08:20:00',
                    'catatan' => '',
                    'metode_pembayaran' => 'transfer',
                    'status_pembayaran' => 'sudah_bayar',
                    'jenis_pengambilan' => 'ambil_sendiri',
                    'alamat_pengambilan' => ''
                ],
                5 => [
                    'id' => 5,
                    'no_order' => 'WW005',
                    'tanggal_order' => '2024-12-04 13:10:00',
                    'jenis_layanan' => 'Cuci Kering',
                    'berat' => 6.0,
                    'total_harga' => 48000,
                    'status' => 'menunggu',
                    'estimasi_selesai' => '2024-12-06 13:10:00',
                    'catatan' => '',
                    'metode_pembayaran' => 'cash',
                    'status_pembayaran' => 'belum_bayar',
                    'jenis_pengambilan' => 'diantar',
                    'alamat_pengambilan' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan'
                ],
            ];
        }
    }
    
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 10);
        $currentPage = $request->get('page', 1);
        
        $allData = collect(self::$orders)->map(function($item) {
            return (object)$item;
        });
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->status === $request->status;
            });
        }
        
        // Sort by date descending
        $allData = $allData->sortByDesc('tanggal_order')->values();
        
        $total = $allData->count();
        $items = $allData->forPage($currentPage, $perPage)->values();
        
        $orders = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        
        return view('pelanggan.order.index', compact('orders'));
    }
    
    public function show($id)
    {
        if (!isset(self::$orders[$id])) {
            return redirect()->route('pelanggan.order.index')
                ->with('error', 'Order tidak ditemukan!');
        }
        
        $order = (object)self::$orders[$id];
        
        return view('pelanggan.order.detail', compact('order'));
    }
    
    public function create()
    {
        // Dummy data layanan
        $layanan = collect([
            (object)['id' => 1, 'nama' => 'Cuci + Setrika Express', 'harga' => 10000, 'durasi' => 1],
            (object)['id' => 2, 'nama' => 'Cuci + Setrika Reguler', 'harga' => 7000, 'durasi' => 3],
            (object)['id' => 3, 'nama' => 'Cuci Lipat', 'harga' => 5000, 'durasi' => 2],
            (object)['id' => 4, 'nama' => 'Setrika Saja', 'harga' => 4000, 'durasi' => 1],
        ]);
        
        return view('pelanggan.order.create', compact('layanan'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'jenis_layanan' => 'required',
            'berat' => 'required|numeric|min:0.5',
            'jenis_pengambilan' => 'required|in:diantar,ambil_sendiri',
            'alamat_pengambilan' => 'required_if:jenis_pengambilan,diantar',
            'metode_pembayaran' => 'required|in:cash,transfer'
        ]);
        
        // Simulasi create order
        return redirect()->route('pelanggan.order.index')
            ->with('success', 'Order berhasil dibuat!');
    }
}