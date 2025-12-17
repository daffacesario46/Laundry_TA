<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\CucianDetail;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\ListHarga;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CucianController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = Cucian::with(['pelanggan', 'layanan', 'pembayaran']);
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cucian_id', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%")
                         ->orWhere('no_telp', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_cucian', $request->status);
        }
        
        // Filter berdasarkan jenis order
        if ($request->filled('jenis_order')) {
            $query->where('jenis_order', $request->jenis_order);
        }
        
        $cucian = $query->orderBy('cucian_id', 'desc')->paginate($perPage);
        
        return view('staff.cucian.index', compact('cucian'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status', 'aktif')->orderBy('nama')->get();
        $layanan = Layanan::orderBy('nama_layanan')->get();
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        return view('staff.cucian.create', compact('pelanggan', 'layanan', 'listHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_order' => 'required|in:online,offline',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'items' => 'required|array|min:1',
            'items.*.list_harga_id' => 'required|exists:list_harga,list_harga_id',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.berat_kg' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            // Hitung total harga dan item
            $totalHarga = 0;
            $totalItem = count($request->items);
            $totalBerat = 0;
            
            foreach ($request->items as $item) {
                $listHarga = ListHarga::find($item['list_harga_id']);
                
                if (isset($item['berat_kg']) && $item['berat_kg'] > 0) {
                    $totalBerat += $item['berat_kg'];
                    $totalHarga += $item['berat_kg'] * $listHarga->harga_kiloan;
                } else {
                    $jumlah = $item['jumlah'] ?? 1;
                    $totalHarga += $jumlah * $listHarga->harga_satuan;
                }
            }
            
            // Ambil layanan untuk hitung estimasi
            $layanan = Layanan::find($request->layanan_id);
            $estimasi = now()->addDays($layanan->durasi_hari);
            
            // Buat cucian
            $cucian = Cucian::create([
                'pelanggan_id' => $request->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_order' => $request->jenis_order,
                'jenis_ambil' => $request->jenis_ambil,
                'tgl_order' => now(),
                'estimasi' => $estimasi,
                'total_item' => $totalItem,
                'total_berat' => $totalBerat > 0 ? $totalBerat : null,
                'total_harga' => $totalHarga,
                'status_cucian' => 'menunggu',
                'catatan' => $request->catatan
            ]);
            
            // Buat detail cucian
            foreach ($request->items as $item) {
                CucianDetail::create([
                    'cucian_id' => $cucian->cucian_id,
                    'list_harga_id' => $item['list_harga_id'],
                    'jumlah' => $item['jumlah'] ?? 1,
                    'berat_kg' => $item['berat_kg'] ?? null,
                    'deskripsi' => $item['deskripsi'] ?? null
                ]);
            }
            
            // Buat pembayaran (belum lunas)
            Pembayaran::create([
                'cucian_id' => $cucian->cucian_id,
                'metode_bayar' => $request->metode_bayar ?? 'cash',
                'status_bayar' => 'belum',
                'jumlah_bayar' => $totalHarga
            ]);
            
            DB::commit();
            
            return redirect()->route('staff.cucian.index')
                ->with('success', 'Data cucian berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan cucian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $cucian = Cucian::with([
            'pelanggan',
            'layanan',
            'detail.listHarga',
            'pembayaran'
        ])->findOrFail($id);
        
        return view('staff.cucian.detail', compact('cucian'));
    }

    public function edit($id)
    {
        $cucian = Cucian::with('detail')->findOrFail($id);
        $pelanggan = Pelanggan::where('status', 'aktif')->orderBy('nama')->get();
        $layanan = Layanan::orderBy('nama_layanan')->get();
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        return view('staff.cucian.edit', compact('cucian', 'pelanggan', 'layanan', 'listHarga'));
    }

    public function update(Request $request, $id)
    {
        $cucian = Cucian::findOrFail($id);
        
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'catatan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $cucian->update([
                'pelanggan_id' => $request->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_ambil' => $request->jenis_ambil,
                'catatan' => $request->catatan
            ]);
            
            DB::commit();
            
            return redirect()->route('staff.cucian.index')
                ->with('success', 'Data cucian berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal update cucian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $cucian = Cucian::findOrFail($id);
        
        // Hanya bisa hapus jika status masih menunggu
        if ($cucian->status_cucian !== 'menunggu') {
            return redirect()->route('staff.cucian.index')
                ->with('error', 'Cucian hanya bisa dihapus jika status masih menunggu!');
        }
        
        DB::beginTransaction();
        try {
            // Detail dan pembayaran akan terhapus otomatis karena cascade
            $cucian->delete();
            
            DB::commit();
            
            return redirect()->route('staff.cucian.index')
                ->with('success', 'Data cucian berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus cucian: ' . $e->getMessage());
        }
    }
    
    // Update status cucian
    public function updateStatus(Request $request, $id)
    {
        $cucian = Cucian::findOrFail($id);
        
        $request->validate([
            'status_cucian' => 'required|in:menunggu,diproses,selesai,diambil'
        ]);
        
        $cucian->status_cucian = $request->status_cucian;
        
        // Set tanggal selesai jika status = selesai
        if ($request->status_cucian === 'selesai' && !$cucian->tgl_selesai) {
            $cucian->tgl_selesai = now();
        }
        
        // Set tanggal diambil jika status = diambil
        if ($request->status_cucian === 'diambil' && !$cucian->tgl_diambil) {
            $cucian->tgl_diambil = now();
        }
        
        $cucian->save();
        
        return redirect()->back()
            ->with('success', 'Status cucian berhasil diupdate!');
    }
}