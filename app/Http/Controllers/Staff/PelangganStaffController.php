<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PelangganStaffController extends Controller
{
    // Dummy data pelanggan
    private function getAllPelanggan()
    {
        return collect([
            (object)[
                'id' => 1,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_daftar' => '2024-01-15',
                'total_transaksi' => 15,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 2,
                'nama' => 'Siti Aminah',
                'email' => 'siti.aminah@email.com',
                'no_telp' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_daftar' => '2024-02-10',
                'total_transaksi' => 23,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 3,
                'nama' => 'Ahmad Dahlan',
                'email' => 'ahmad.dahlan@email.com',
                'no_telp' => '083456789012',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_daftar' => '2024-03-05',
                'total_transaksi' => 8,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 4,
                'nama' => 'Rina Wati',
                'email' => 'rina.wati@email.com',
                'no_telp' => '084567890123',
                'alamat' => 'Jl. Thamrin No. 90, Jakarta Pusat',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_daftar' => '2024-04-20',
                'total_transaksi' => 31,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 5,
                'nama' => 'Joko Widodo',
                'email' => 'joko.widodo@email.com',
                'no_telp' => '085678901234',
                'alamat' => 'Jl. Kuningan No. 12, Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_daftar' => '2024-05-12',
                'total_transaksi' => 12,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 6,
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'no_telp' => '086789012345',
                'alamat' => 'Jl. Casablanca No. 56, Jakarta Selatan',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_daftar' => '2024-06-08',
                'total_transaksi' => 5,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 7,
                'nama' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@email.com',
                'no_telp' => '087890123456',
                'alamat' => 'Jl. Rasuna Said No. 34, Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_daftar' => '2024-07-22',
                'total_transaksi' => 19,
                'status' => 'nonaktif'
            ],
            (object)[
                'id' => 8,
                'nama' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'no_telp' => '088901234567',
                'alamat' => 'Jl. TB Simatupang No. 89, Jakarta Selatan',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_daftar' => '2024-08-15',
                'total_transaksi' => 27,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 9,
                'nama' => 'Bambang Suryanto',
                'email' => 'bambang.suryanto@email.com',
                'no_telp' => '089012345678',
                'alamat' => 'Jl. HR Rasuna Said No. 100, Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_daftar' => '2024-09-10',
                'total_transaksi' => 3,
                'status' => 'aktif'
            ],
            (object)[
                'id' => 10,
                'nama' => 'Putri Handayani',
                'email' => 'putri.handayani@email.com',
                'no_telp' => '081123456789',
                'alamat' => 'Jl. Senopati No. 67, Jakarta Selatan',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_daftar' => '2024-10-05',
                'total_transaksi' => 14,
                'status' => 'aktif'
            ],
        ]);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        $currentPage = $request->get('page', 1);
        
        $allData = $this->getAllPelanggan();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allData = $allData->filter(function($item) use ($search) {
                return str_contains(strtolower($item->nama), $search) ||
                       str_contains(strtolower($item->no_telp), $search) ||
                       str_contains(strtolower($item->email), $search);
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
        
        $pelanggan = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
        
        return view('staff.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('staff.pelanggan.create');
    }

    public function store(Request $request)
    {
        // Simulasi simpan data
        return redirect()->route('staff.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pelanggan = $this->getAllPelanggan()->firstWhere('id', $id);
        
        if (!$pelanggan) {
            return redirect()->route('staff.pelanggan.index')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        return view('staff.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        // Simulasi update data
        return redirect()->route('staff.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Simulasi hapus data
        return redirect()->route('staff.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus!');
    }
}