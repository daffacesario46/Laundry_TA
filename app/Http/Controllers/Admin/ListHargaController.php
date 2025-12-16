<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHargaController extends Controller
{
    // Simulasi database dengan static property
    private static $data = [];
    
   public function __construct()
{
    // Initialize dummy data kalau masih kosong
    if (empty(self::$data)) {
        self::$data = [
            // === KILOAN ===
            1 => [
                'id' => 1,
                'jenis_cucian' => 'Kiloan',
                'layanan' => 'Cuci + Setrika Express (1 Hari)',
                'harga' => 10000,
                'satuan' => 'kg',
                'deskripsi' => 'Layanan cuci setrika kiloan dengan pengerjaan 1 hari',
                'created_at' => '2024-01-01 10:00:00',
                'updated_at' => '2024-01-01 10:00:00'
            ],
            2 => [
                'id' => 2,
                'jenis_cucian' => 'Kiloan',
                'layanan' => 'Cuci + Setrika Reguler (3 Hari)',
                'harga' => 7000,
                'satuan' => 'kg',
                'deskripsi' => 'Layanan cuci setrika kiloan dengan pengerjaan 3 hari',
                'created_at' => '2024-01-02 10:00:00',
                'updated_at' => '2024-01-02 10:00:00'
            ],
            3 => [
                'id' => 3,
                'jenis_cucian' => 'Kiloan',
                'layanan' => 'Cuci Lipat (2 Hari)',
                'harga' => 5000,
                'satuan' => 'kg',
                'deskripsi' => 'Layanan cuci dan lipat tanpa setrika',
                'created_at' => '2024-01-03 10:00:00',
                'updated_at' => '2024-01-03 10:00:00'
            ],
            4 => [
                'id' => 4,
                'jenis_cucian' => 'Kiloan',
                'layanan' => 'Setrika Saja',
                'harga' => 4000,
                'satuan' => 'kg',
                'deskripsi' => 'Layanan setrika saja untuk pakaian yang sudah dicuci',
                'created_at' => '2024-01-04 10:00:00',
                'updated_at' => '2024-01-04 10:00:00'
            ],
            
            // === SATUAN - PAKAIAN ===
            5 => [
                'id' => 5,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Kemeja/Blouse',
                'harga' => 8000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk kemeja atau blouse',
                'created_at' => '2024-01-05 10:00:00',
                'updated_at' => '2024-01-05 10:00:00'
            ],
            6 => [
                'id' => 6,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Celana Panjang',
                'harga' => 7000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk celana panjang',
                'created_at' => '2024-01-06 10:00:00',
                'updated_at' => '2024-01-06 10:00:00'
            ],
            7 => [
                'id' => 7,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Celana Pendek',
                'harga' => 5000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk celana pendek',
                'created_at' => '2024-01-07 10:00:00',
                'updated_at' => '2024-01-07 10:00:00'
            ],
            8 => [
                'id' => 8,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Kaos/T-Shirt',
                'harga' => 5000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk kaos atau t-shirt',
                'created_at' => '2024-01-08 10:00:00',
                'updated_at' => '2024-01-08 10:00:00'
            ],
            9 => [
                'id' => 9,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Jaket Tipis',
                'harga' => 12000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci untuk jaket bahan tipis',
                'created_at' => '2024-01-09 10:00:00',
                'updated_at' => '2024-01-09 10:00:00'
            ],
            10 => [
                'id' => 10,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Jaket Tebal/Parka',
                'harga' => 25000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci untuk jaket bahan tebal atau parka',
                'created_at' => '2024-01-10 10:00:00',
                'updated_at' => '2024-01-10 10:00:00'
            ],
            11 => [
                'id' => 11,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Rok',
                'harga' => 6000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk rok',
                'created_at' => '2024-01-11 10:00:00',
                'updated_at' => '2024-01-11 10:00:00'
            ],
            12 => [
                'id' => 12,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Dress/Gaun',
                'harga' => 15000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk dress atau gaun',
                'created_at' => '2024-01-12 10:00:00',
                'updated_at' => '2024-01-12 10:00:00'
            ],
            13 => [
                'id' => 13,
                'jenis_cucian' => 'Satuan',
                'layanan' => 'Jas/Blazer',
                'harga' => 20000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci setrika untuk jas atau blazer',
                'created_at' => '2024-01-13 10:00:00',
                'updated_at' => '2024-01-13 10:00:00'
            ],
            
            // === SEPATU ===
            14 => [
                'id' => 14,
                'jenis_cucian' => 'Sepatu',
                'layanan' => 'Sepatu Sneakers',
                'harga' => 25000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci sepatu sneakers dengan deep cleaning',
                'created_at' => '2024-01-14 10:00:00',
                'updated_at' => '2024-01-14 10:00:00'
            ],
            15 => [
                'id' => 15,
                'jenis_cucian' => 'Sepatu',
                'layanan' => 'Sepatu Kulit',
                'harga' => 35000,
                'satuan' => 'pcs',
                'deskripsi' => 'Cuci dan perawatan sepatu kulit',
                'created_at' => '2024-01-15 10:00:00',
                'updated_at' => '2024-01-15 10:00:00'
            ],
            
        ];
    }
}
    
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        $currentPage = $request->get('page', 1);
        
        // Convert array to collection
        $allData = collect(self::$data)->map(function($item) {
            return (object)$item;
        });
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allData = $allData->filter(function($item) use ($search) {
                return str_contains(strtolower($item->layanan), $search) ||
                       str_contains(strtolower($item->jenis_cucian), $search);
            });
        }
        
        // Sort by ID descending (terbaru dulu)
        $allData = $allData->sortByDesc('id')->values();
        
        // Buat paginator manual
        $total = $allData->count();
        $items = $allData->forPage($currentPage, $perPage)->values();
        
        $listHarga = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        
        return view('admin.list-harga.index', compact('listHarga'));
    }

    public function create()
    {
        return view('admin.list-harga.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_cucian' => 'required|string',
            'layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_cucian.required' => 'Jenis cucian harus diisi',
            'layanan.required' => 'Nama layanan harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'satuan.required' => 'Satuan harus diisi'
        ]);
        
        // Generate ID baru
        $newId = empty(self::$data) ? 1 : max(array_keys(self::$data)) + 1;
        
        // Simpan data baru
        self::$data[$newId] = [
            'id' => $newId,
            'jenis_cucian' => $request->jenis_cucian,
            'layanan' => $request->layanan,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
            'deskripsi' => $request->deskripsi,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Cari data berdasarkan ID
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.list-harga.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        $listHarga = (object)self::$data[$id];
        
        return view('admin.list-harga.edit', compact('listHarga'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jenis_cucian' => 'required|string',
            'layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_cucian.required' => 'Jenis cucian harus diisi',
            'layanan.required' => 'Nama layanan harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'satuan.required' => 'Satuan harus diisi'
        ]);
        
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.list-harga.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Update data
        self::$data[$id] = [
            'id' => $id,
            'jenis_cucian' => $request->jenis_cucian,
            'layanan' => $request->layanan,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
            'deskripsi' => $request->deskripsi,
            'created_at' => self::$data[$id]['created_at'],
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.list-harga.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Hapus data
        unset(self::$data[$id]);
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil dihapus!');
    }
}