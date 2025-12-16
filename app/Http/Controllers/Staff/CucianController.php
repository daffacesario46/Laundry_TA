<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CucianController extends Controller
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
                'harga_per_kg' => 10000,
                'total_harga' => 35000,
                'status' => 'menunggu',
                'tanggal_masuk' => '2024-12-08 09:00:00',
                'tanggal_selesai' => null,
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
                'harga_per_kg' => 8000,
                'total_harga' => 40000,
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-07 14:30:00',
                'tanggal_selesai' => null,
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
                'harga_per_kg' => 7500,
                'total_harga' => 15000,
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-06 10:15:00',
                'tanggal_selesai' => '2024-12-07 16:00:00',
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
                'harga_per_kg' => 15000,
                'total_harga' => 67500,
                'status' => 'proses',
                'tanggal_masuk' => '2024-12-06 08:20:00',
                'tanggal_selesai' => null,
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
                'harga_per_kg' => 8000,
                'total_harga' => 48000,
                'status' => 'selesai',
                'tanggal_masuk' => '2024-12-05 13:10:00',
                'tanggal_selesai' => '2024-12-06 10:00:00',
                'catatan' => ''
            ],
        ]);
    }

    // Dummy data pelanggan untuk dropdown
    private function getAllPelanggan()
    {
        return collect([
            (object)['id' => 1, 'nama' => 'Budi Santoso', 'no_telp' => '081234567890', 'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan'],
            (object)['id' => 2, 'nama' => 'Siti Aminah', 'no_telp' => '082345678901', 'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat'],
            (object)['id' => 3, 'nama' => 'Ahmad Dahlan', 'no_telp' => '083456789012', 'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan'],
            (object)['id' => 4, 'nama' => 'Rina Wati', 'no_telp' => '084567890123', 'alamat' => 'Jl. Thamrin No. 90, Jakarta Pusat'],
            (object)['id' => 5, 'nama' => 'Joko Widodo', 'no_telp' => '085678901234', 'alamat' => 'Jl. Kuningan No. 12, Jakarta Selatan'],
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
        
        return view('staff.cucian.index', compact('cucian'));
    }

    public function create()
    {
        $pelanggan = $this->getAllPelanggan();
        return view('staff.cucian.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        // Validasi (untuk production nanti bisa aktifkan)
        // $request->validate([...]);
        
        // Simulasi simpan data
        return redirect()->route('staff.cucian.index')
            ->with('success', 'Data cucian berhasil ditambahkan!');
    }

    public function show($id)
    {
        $cucian = $this->getAllCucian()->firstWhere('id', $id);
        
        if (!$cucian) {
            return redirect()->route('staff.cucian.index')
                ->with('error', 'Data cucian tidak ditemukan!');
        }
        
        return view('staff.cucian.detail', compact('cucian'));
    }

    public function edit($id)
    {
        $cucian = $this->getAllCucian()->firstWhere('id', $id);
        $pelanggan = $this->getAllPelanggan();
        
        if (!$cucian) {
            return redirect()->route('staff.cucian.index')
                ->with('error', 'Data cucian tidak ditemukan!');
        }
        
        return view('staff.cucian.edit', compact('cucian', 'pelanggan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi (untuk production nanti bisa aktifkan)
        // $request->validate([...]);
        
        // Simulasi update data
        return redirect()->route('staff.cucian.index')
            ->with('success', 'Data cucian berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Simulasi hapus data
        return redirect()->route('staff.cucian.index')
            ->with('success', 'Data cucian berhasil dihapus!');
    }
}