<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LayananController extends Controller
{
    // Simulasi database dengan static property
    private static $data = [];
    
    public function __construct()
    {
        // Initialize dummy data kalau masih kosong
        if (empty(self::$data)) {
            self::$data = [
                // LAYANAN KILOAN
                1 => [
                    'layanan_id' => 1,
                    'nama_layanan' => 'Cuci + Setrika Express',
                    'jenis_cucian' => 'kiloan',
                    'durasi_hari' => 1,
                    'deskripsi' => 'Layanan cuci dan setrika kiloan dengan pengerjaan super cepat hanya 1 hari. Cocok untuk kebutuhan mendesak.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-01 10:00:00',
                    'updated_at' => '2024-01-01 10:00:00'
                ],
                2 => [
                    'layanan_id' => 2,
                    'nama_layanan' => 'Cuci + Setrika Reguler',
                    'jenis_cucian' => 'kiloan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci dan setrika kiloan standar dengan waktu pengerjaan 3 hari kerja.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-02 10:00:00',
                    'updated_at' => '2024-01-02 10:00:00'
                ],
                3 => [
                    'layanan_id' => 3,
                    'nama_layanan' => 'Cuci + Setrika Hemat',
                    'jenis_cucian' => 'kiloan',
                    'durasi_hari' => 5,
                    'deskripsi' => 'Layanan cuci dan setrika kiloan dengan harga hemat, pengerjaan 5 hari kerja.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-03 10:00:00',
                    'updated_at' => '2024-01-03 10:00:00'
                ],
                4 => [
                    'layanan_id' => 4,
                    'nama_layanan' => 'Cuci Lipat',
                    'jenis_cucian' => 'kiloan',
                    'durasi_hari' => 2,
                    'deskripsi' => 'Layanan cuci dan lipat rapi tanpa setrika, cocok untuk pakaian casual.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-04 10:00:00',
                    'updated_at' => '2024-01-04 10:00:00'
                ],
                5 => [
                    'layanan_id' => 5,
                    'nama_layanan' => 'Setrika Saja',
                    'jenis_cucian' => 'kiloan',
                    'durasi_hari' => 1,
                    'deskripsi' => 'Layanan setrika saja untuk pakaian yang sudah bersih. Pengerjaan 1 hari.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-05 10:00:00',
                    'updated_at' => '2024-01-05 10:00:00'
                ],
                
                // LAYANAN SATUAN
                6 => [
                    'layanan_id' => 6,
                    'nama_layanan' => 'Cuci Satuan Express',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 1,
                    'deskripsi' => 'Layanan cuci dan setrika satuan dengan pengerjaan 1 hari. Tersedia untuk kemeja, celana, kaos, dll.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-06 10:00:00',
                    'updated_at' => '2024-01-06 10:00:00'
                ],
                7 => [
                    'layanan_id' => 7,
                    'nama_layanan' => 'Cuci Satuan Reguler',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 2,
                    'deskripsi' => 'Layanan cuci dan setrika satuan standar dengan waktu pengerjaan 2 hari.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-07 10:00:00',
                    'updated_at' => '2024-01-07 10:00:00'
                ],
                8 => [
                    'layanan_id' => 8,
                    'nama_layanan' => 'Dry Clean Premium',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan dry cleaning untuk pakaian premium seperti jas, gaun, dress, dan pakaian berbahan khusus.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-08 10:00:00',
                    'updated_at' => '2024-01-08 10:00:00'
                ],
                
                // LAYANAN SEPATU
                9 => [
                    'layanan_id' => 9,
                    'nama_layanan' => 'Cuci Sepatu Deep Clean',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 2,
                    'deskripsi' => 'Layanan deep cleaning untuk sepatu sneakers, canvas, dan sport shoes dengan treatment khusus.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-09 10:00:00',
                    'updated_at' => '2024-01-09 10:00:00'
                ],
                10 => [
                    'layanan_id' => 10,
                    'nama_layanan' => 'Cuci Sepatu Kulit',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci dan perawatan khusus untuk sepatu kulit dengan conditioning dan polish.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-10 10:00:00',
                    'updated_at' => '2024-01-10 10:00:00'
                ],
                11 => [
                    'layanan_id' => 11,
                    'nama_layanan' => 'Repaint Sepatu',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 5,
                    'deskripsi' => 'Layanan repaint atau pengecatan ulang sepatu untuk mengembalikan warna seperti baru.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-11 10:00:00',
                    'updated_at' => '2024-01-11 10:00:00'
                ],
                
                // LAYANAN KARPET
                12 => [
                    'layanan_id' => 12,
                    'nama_layanan' => 'Cuci Karpet Standar',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci karpet standar untuk karpet bulu dan karpet tipis dengan sistem vakum dan deep wash.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-12 10:00:00',
                    'updated_at' => '2024-01-12 10:00:00'
                ],
                13 => [
                    'layanan_id' => 13,
                    'nama_layanan' => 'Cuci Karpet Premium',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 5,
                    'deskripsi' => 'Layanan cuci karpet premium dengan treatment khusus, cocok untuk karpet mahal dan berbulu tebal.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-13 10:00:00',
                    'updated_at' => '2024-01-13 10:00:00'
                ],
                
                // LAYANAN BED COVER
                14 => [
                    'layanan_id' => 14,
                    'nama_layanan' => 'Cuci Bed Cover',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 2,
                    'deskripsi' => 'Layanan cuci bed cover, sprei, dan selimut dengan pewangi khusus.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-14 10:00:00',
                    'updated_at' => '2024-01-14 10:00:00'
                ],
                15 => [
                    'layanan_id' => 15,
                    'nama_layanan' => 'Cuci Selimut Tebal',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci untuk selimut tebal, comforter, atau duvet dengan mesin khusus.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-15 10:00:00',
                    'updated_at' => '2024-01-15 10:00:00'
                ],
                
                // LAYANAN BONEKA
                16 => [
                    'layanan_id' => 16,
                    'nama_layanan' => 'Cuci Boneka',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 2,
                    'deskripsi' => 'Layanan cuci boneka dengan treatment khusus agar boneka tetap empuk dan tidak rusak.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-16 10:00:00',
                    'updated_at' => '2024-01-16 10:00:00'
                ],
                
                // LAYANAN GORDEN
                17 => [
                    'layanan_id' => 17,
                    'nama_layanan' => 'Cuci Gorden',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 4,
                    'deskripsi' => 'Layanan cuci gorden dan vitrase untuk berbagai jenis bahan dengan hasil bersih maksimal.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-17 10:00:00',
                    'updated_at' => '2024-01-17 10:00:00'
                ],
                
                // LAYANAN TAS
                18 => [
                    'layanan_id' => 18,
                    'nama_layanan' => 'Cuci Tas Reguler',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci tas ransel, tas sekolah, dan tas canvas dengan deep cleaning.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-18 10:00:00',
                    'updated_at' => '2024-01-18 10:00:00'
                ],
                19 => [
                    'layanan_id' => 19,
                    'nama_layanan' => 'Spa Tas Premium',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 7,
                    'deskripsi' => 'Layanan spa lengkap untuk tas kulit atau branded dengan cleaning, conditioning, dan repaint.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-19 10:00:00',
                    'updated_at' => '2024-01-19 10:00:00'
                ],
                20 => [
                    'layanan_id' => 20,
                    'nama_layanan' => 'Cuci Koper',
                    'jenis_cucian' => 'satuan',
                    'durasi_hari' => 3,
                    'deskripsi' => 'Layanan cuci dan sanitasi koper untuk persiapan traveling yang higienis.',
                    'status' => 'aktif',
                    'created_at' => '2024-01-20 10:00:00',
                    'updated_at' => '2024-01-20 10:00:00'
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
                return str_contains(strtolower($item->nama_layanan), $search) ||
                       str_contains(strtolower($item->deskripsi), $search);
            });
        }
        
        // Filter berdasarkan jenis cucian
        if ($request->filled('jenis_cucian')) {
            $allData = $allData->filter(function($item) use ($request) {
                return $item->jenis_cucian === $request->jenis_cucian;
            });
        }
        
        // Sort by ID descending (terbaru dulu)
        $allData = $allData->sortByDesc('layanan_id')->values();
        
        // Buat paginator manual
        $total = $allData->count();
        $items = $allData->forPage($currentPage, $perPage)->values();
        
        $layanan = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        
        return view('admin.layanan.index', compact('layanan'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'jenis_cucian' => 'required|in:kiloan,satuan',
            'durasi_hari' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string'
        ], [
            'nama_layanan.required' => 'Nama layanan harus diisi',
            'jenis_cucian.required' => 'Jenis cucian harus dipilih',
            'jenis_cucian.in' => 'Jenis cucian tidak valid',
            'durasi_hari.required' => 'Durasi hari harus diisi',
            'durasi_hari.integer' => 'Durasi hari harus berupa angka',
            'durasi_hari.min' => 'Durasi hari minimal 1 hari',
            'status.required' => 'Status harus dipilih'
        ]);
        
        // Generate ID baru
        $newId = empty(self::$data) ? 1 : max(array_keys(self::$data)) + 1;
        
        // Simpan data baru
        self::$data[$newId] = [
            'layanan_id' => $newId,
            'nama_layanan' => $request->nama_layanan,
            'jenis_cucian' => $request->jenis_cucian,
            'durasi_hari' => $request->durasi_hari,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Cari data berdasarkan ID
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        $layanan = (object)self::$data[$id];
        
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'jenis_cucian' => 'required|in:kiloan,satuan',
            'durasi_hari' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string'
        ], [
            'nama_layanan.required' => 'Nama layanan harus diisi',
            'jenis_cucian.required' => 'Jenis cucian harus dipilih',
            'jenis_cucian.in' => 'Jenis cucian tidak valid',
            'durasi_hari.required' => 'Durasi hari harus diisi',
            'durasi_hari.integer' => 'Durasi hari harus berupa angka',
            'durasi_hari.min' => 'Durasi hari minimal 1 hari',
            'status.required' => 'Status harus dipilih'
        ]);
        
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Update data
        self::$data[$id] = [
            'layanan_id' => $id,
            'nama_layanan' => $request->nama_layanan,
            'jenis_cucian' => $request->jenis_cucian,
            'durasi_hari' => $request->durasi_hari,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'created_at' => self::$data[$id]['created_at'],
            'updated_at' => now()->format('Y-m-d H:i:s')
        ];
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Cek apakah data ada
        if (!isset(self::$data[$id])) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Data tidak ditemukan!');
        }
        
        // Hapus data
        unset(self::$data[$id]);
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }
}