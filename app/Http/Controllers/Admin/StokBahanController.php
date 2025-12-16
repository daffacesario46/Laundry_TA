<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StokBahanController extends Controller
{
    // Simulasi database dengan static property
    private static $data = [];
    
    public function __construct()
    {
        // Initialize dummy data kalau masih kosong
        if (empty(self::$data)) {
            self::$data = [
                // DETERGEN
                1 => [
                    'stok_bahan_id' => 1,
                    'jenis_bahan' => 'detergen',
                    'merk' => 'Rinso Anti Noda',
                    'stok_tersedia' => 50,
                    'satuan' => 'kg',
                    'stok_minimum' => 20,
                    'harga_beli' => 45000,
                    'deskripsi' => 'Detergen bubuk untuk cucian berat dan menghilangkan noda membandel',
                    'created_at' => '2024-01-01 10:00:00',
                    'updated_at' => '2024-01-01 10:00:00'
                ],
                2 => [
                    'stok_bahan_id' => 2,
                    'jenis_bahan' => 'detergen',
                    'merk' => 'Attack Jaz 1',
                    'stok_tersedia' => 35,
                    'satuan' => 'kg',
                    'stok_minimum' => 15,
                    'harga_beli' => 38000,
                    'deskripsi' => 'Detergen dengan formula pembersih aktif untuk pakaian sehari-hari',
                    'created_at' => '2024-01-02 10:00:00',
                    'updated_at' => '2024-01-02 10:00:00'
                ],
                3 => [
                    'stok_bahan_id' => 3,
                    'jenis_bahan' => 'detergen',
                    'merk' => 'So Klin Liquid',
                    'stok_tersedia' => 25,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 52000,
                    'deskripsi' => 'Detergen cair untuk mesin cuci dan cucian halus',
                    'created_at' => '2024-01-03 10:00:00',
                    'updated_at' => '2024-01-03 10:00:00'
                ],
                4 => [
                    'stok_bahan_id' => 4,
                    'jenis_bahan' => 'detergen',
                    'merk' => 'Daia Putih',
                    'stok_tersedia' => 15,
                    'satuan' => 'kg',
                    'stok_minimum' => 20,
                    'harga_beli' => 32000,
                    'deskripsi' => 'Detergen ekonomis untuk cucian putih',
                    'created_at' => '2024-01-04 10:00:00',
                    'updated_at' => '2024-01-04 10:00:00'
                ],
                5 => [
                    'stok_bahan_id' => 5,
                    'jenis_bahan' => 'detergen',
                    'merk' => 'Sunlight Lemon',
                    'stok_tersedia' => 0,
                    'satuan' => 'liter',
                    'stok_minimum' => 8,
                    'harga_beli' => 28000,
                    'deskripsi' => 'Cairan pencuci piring yang juga bisa untuk pakaian ringan',
                    'created_at' => '2024-01-05 10:00:00',
                    'updated_at' => '2024-01-05 10:00:00'
                ],

                // PEWANGI
                6 => [
                    'stok_bahan_id' => 6,
                    'jenis_bahan' => 'pewangi',
                    'merk' => 'Molto Ultra Sekali Bilas',
                    'stok_tersedia' => 40,
                    'satuan' => 'liter',
                    'stok_minimum' => 15,
                    'harga_beli' => 48000,
                    'deskripsi' => 'Pewangi pakaian dengan wangi tahan lama hingga 30 hari',
                    'created_at' => '2024-01-06 10:00:00',
                    'updated_at' => '2024-01-06 10:00:00'
                ],
                7 => [
                    'stok_bahan_id' => 7,
                    'jenis_bahan' => 'pewangi',
                    'merk' => 'Downy Parfum Collection',
                    'stok_tersedia' => 30,
                    'satuan' => 'liter',
                    'stok_minimum' => 12,
                    'harga_beli' => 55000,
                    'deskripsi' => 'Pewangi premium dengan aroma parfum mewah',
                    'created_at' => '2024-01-07 10:00:00',
                    'updated_at' => '2024-01-07 10:00:00'
                ],
                8 => [
                    'stok_bahan_id' => 8,
                    'jenis_bahan' => 'pewangi',
                    'merk' => 'Softener Lavender',
                    'stok_tersedia' => 20,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 42000,
                    'deskripsi' => 'Pewangi dengan aroma lavender yang menenangkan',
                    'created_at' => '2024-01-08 10:00:00',
                    'updated_at' => '2024-01-08 10:00:00'
                ],
                9 => [
                    'stok_bahan_id' => 9,
                    'jenis_bahan' => 'pewangi',
                    'merk' => 'Soklin Softener',
                    'stok_tersedia' => 8,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 38000,
                    'deskripsi' => 'Pelembut dan pewangi pakaian dengan harga terjangkau',
                    'created_at' => '2024-01-09 10:00:00',
                    'updated_at' => '2024-01-09 10:00:00'
                ],
                10 => [
                    'stok_bahan_id' => 10,
                    'jenis_bahan' => 'pewangi',
                    'merk' => 'Stella Parfum Laundry',
                    'stok_tersedia' => 18,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 65000,
                    'deskripsi' => 'Pewangi laundry profesional dengan aroma premium',
                    'created_at' => '2024-01-10 10:00:00',
                    'updated_at' => '2024-01-10 10:00:00'
                ],

                // PELEMBUT
                11 => [
                    'stok_bahan_id' => 11,
                    'jenis_bahan' => 'pelembut',
                    'merk' => 'Downy Antibac',
                    'stok_tersedia' => 28,
                    'satuan' => 'liter',
                    'stok_minimum' => 12,
                    'harga_beli' => 58000,
                    'deskripsi' => 'Pelembut dengan formula antibakteri',
                    'created_at' => '2024-01-11 10:00:00',
                    'updated_at' => '2024-01-11 10:00:00'
                ],
                12 => [
                    'stok_bahan_id' => 12,
                    'jenis_bahan' => 'pelembut',
                    'merk' => 'Molto Ultra Concentrate',
                    'stok_tersedia' => 35,
                    'satuan' => 'liter',
                    'stok_minimum' => 15,
                    'harga_beli' => 52000,
                    'deskripsi' => 'Pelembut konsentrat yang hemat dan ekonomis',
                    'created_at' => '2024-01-12 10:00:00',
                    'updated_at' => '2024-01-12 10:00:00'
                ],
                13 => [
                    'stok_bahan_id' => 13,
                    'jenis_bahan' => 'pelembut',
                    'merk' => 'Comfort Concentrated',
                    'stok_tersedia' => 12,
                    'satuan' => 'liter',
                    'stok_minimum' => 15,
                    'harga_beli' => 46000,
                    'deskripsi' => 'Pelembut pakaian dengan formula terkonsentrasi',
                    'created_at' => '2024-01-13 10:00:00',
                    'updated_at' => '2024-01-13 10:00:00'
                ],
                14 => [
                    'stok_bahan_id' => 14,
                    'jenis_bahan' => 'pelembut',
                    'merk' => 'Softex Fabric Care',
                    'stok_tersedia' => 6,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 44000,
                    'deskripsi' => 'Pelembut kain profesional untuk laundry',
                    'created_at' => '2024-01-14 10:00:00',
                    'updated_at' => '2024-01-14 10:00:00'
                ],

                // PEMUTIH
                15 => [
                    'stok_bahan_id' => 15,
                    'jenis_bahan' => 'pemutih',
                    'merk' => 'Bayclin Regular',
                    'stok_tersedia' => 45,
                    'satuan' => 'liter',
                    'stok_minimum' => 20,
                    'harga_beli' => 18000,
                    'deskripsi' => 'Pemutih untuk pakaian putih dan disinfektan',
                    'created_at' => '2024-01-15 10:00:00',
                    'updated_at' => '2024-01-15 10:00:00'
                ],
                16 => [
                    'stok_bahan_id' => 16,
                    'jenis_bahan' => 'pemutih',
                    'merk' => 'Vanish Oxi Action',
                    'stok_tersedia' => 22,
                    'satuan' => 'kg',
                    'stok_minimum' => 10,
                    'harga_beli' => 68000,
                    'deskripsi' => 'Pemutih oksigen untuk menghilangkan noda membandel',
                    'created_at' => '2024-01-16 10:00:00',
                    'updated_at' => '2024-01-16 10:00:00'
                ],
                17 => [
                    'stok_bahan_id' => 17,
                    'jenis_bahan' => 'pemutih',
                    'merk' => 'Wipol Karbol',
                    'stok_tersedia' => 18,
                    'satuan' => 'liter',
                    'stok_minimum' => 15,
                    'harga_beli' => 22000,
                    'deskripsi' => 'Cairan pembersih dan disinfektan serbaguna',
                    'created_at' => '2024-01-17 10:00:00',
                    'updated_at' => '2024-01-17 10:00:00'
                ],
                18 => [
                    'stok_bahan_id' => 18,
                    'jenis_bahan' => 'pemutih',
                    'merk' => 'Clorox Bleach',
                    'stok_tersedia' => 5,
                    'satuan' => 'liter',
                    'stok_minimum' => 10,
                    'harga_beli' => 35000,
                    'deskripsi' => 'Pemutih klorin untuk pakaian putih dan sanitasi',
                    'created_at' => '2024-01-18 10:00:00',
                    'updated_at' => '2024-01-18 10:00:00'
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
                return str_contains(strtolower($item->jenis_bahan), $search) ||
                       str_contains(strtolower($item->merk), $search) ||
                       str_contains(strtolower($item->deskripsi ?? ''), $search);
            });
        }
        
        // Filter berdasarkan jenis bahan
        if ($request->filled('jenis_bahan')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->jenis_bahan === $request->jenis_bahan;
            });
        }
        
        // Sort by ID descending (terbaru dulu)
        $allData = $allData->sortByDesc('stok_bahan_id')->values();
        
        // Buat paginator manual
        $total = $allData->count();
        $items = $allData->forPage($currentPage, $perPage)->values();
        
        $stokBahan = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        
        return view('admin.stok-bahan.index', compact('stokBahan'));
    }

    public function create()
    {
        return view('admin.stok-bahan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_bahan' => 'required|in:detergen,pewangi,pelembut,pemutih',
            'merk' => 'required|string|max:255',
            'stok_tersedia' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_bahan.required' => 'Jenis bahan harus dipilih',
            'jenis_bahan.in' => 'Jenis bahan tidak valid',
            'merk.required' => 'Merk harus diisi',
            'stok_tersedia.required' => 'Stok tersedia harus diisi',
            'stok_tersedia.numeric' => 'Stok tersedia harus berupa angka',
            'stok_tersedia.min' => 'Stok tersedia tidak boleh negatif',
            'satuan.required' => 'Satuan harus diisi',
            'stok_minimum.required' => 'Stok minimum harus diisi',
            'stok_minimum.numeric' => 'Stok minimum harus berupa angka',
            'harga_beli.numeric' => 'Harga beli harus berupa angka'
        ]);
        
        // Generate ID baru
        $newId = empty(self::$data) ? 1 : max(array_keys(self::$data)) + 1;
        
        // Simpan data baru
        self::$data[$newId] = [
            'stok_bahan_id' => $newId,
            'jenis_bahan' => $request->jenis_bahan,
            'merk' => $request->merk,
            'stok_tersedia' => $request->stok_tersedia,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $request->harga_beli,
            'deskripsi' => $request->deskripsi,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil ditambahkan!');
    }

    public function show($id)
    {
        // Cari data berdasarkan ID
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.stok-bahan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        $stokBahan = (object)self::$data[$id];
        
        return view('admin.stok-bahan.detail', compact('stokBahan'));
    }

    public function edit($id)
    {
        // Cari data berdasarkan ID
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.stok-bahan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        $stokBahan = (object)self::$data[$id];
        
        return view('admin.stok-bahan.edit', compact('stokBahan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jenis_bahan' => 'required|in:detergen,pewangi,pelembut,pemutih',
            'merk' => 'required|string|max:255',
            'stok_tersedia' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_bahan.required' => 'Jenis bahan harus dipilih',
            'merk.required' => 'Merk harus diisi',
            'stok_tersedia.required' => 'Stok tersedia harus diisi',
            'satuan.required' => 'Satuan harus diisi',
            'stok_minimum.required' => 'Stok minimum harus diisi'
        ]);
        
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.stok-bahan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Update data
        self::$data[$id] = [
            'stok_bahan_id' => $id,
            'jenis_bahan' => $request->jenis_bahan,
            'merk' => $request->merk,
            'stok_tersedia' => $request->stok_tersedia,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $request->harga_beli,
            'deskripsi' => $request->deskripsi,
            'created_at' => self::$data[$id]['created_at'],
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.stok-bahan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Hapus data
        unset(self::$data[$id]);
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil dihapus!');
    }
}