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
use Carbon\Carbon;

class CucianController extends Controller
{
    /**
     * Display listing of cucian
     */
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

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_bayar')) {
            $query->whereHas('pembayaran', function($q) use ($request) {
                $q->where('status_bayar', $request->status_bayar);
            });
        }
        
        $cucian = $query->orderBy('cucian_id', 'desc')
                        ->paginate($perPage)
                        ->appends($request->except('page'));
        
        return view('staff.cucian.index', compact('cucian'));
    }

    /**
     * Show form for creating new cucian
     */
    public function create()
    {
        $pelanggan = Pelanggan::where('status', 'aktif')
                              ->orderBy('nama')
                              ->get();
        $layanan = Layanan::orderBy('nama_layanan')->get();
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        return view('staff.cucian.create', compact('pelanggan', 'layanan', 'listHarga'));
    }

    /**
     * ✅ UPDATED: Store new cucian - OFFLINE ONLY
     */
    public function store(Request $request)
{
    // Staff hanya buat order OFFLINE
    $jenis_order = 'offline';
    
    // ✅ Validasi jenis_cucian
    $request->validate([
        'jenis_cucian' => 'required|in:kiloan,satuan',
    ], [
        'jenis_cucian.required' => 'Jenis cucian wajib dipilih',
    ]);
    
    $jenis_cucian = $request->jenis_cucian;
    
    // Validation berbeda untuk kiloan vs satuan
    if ($jenis_cucian === 'kiloan') {
        // KILOAN OFFLINE: wajib ada berat
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'metode_bayar' => 'required|in:cash,transfer,e-wallet',
            'berat_kiloan' => 'required|numeric|min:0.1',
            'catatan' => 'nullable|string'
        ], [
            'berat_kiloan.required' => 'Berat cucian wajib diisi untuk layanan kiloan',
            'berat_kiloan.min' => 'Berat minimal 0.1 Kg',
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih',
        ]);
    } else {
        // SATUAN OFFLINE
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'metode_bayar' => 'required|in:cash,transfer,e-wallet',
            'items' => 'required|array|min:1',
            'items.*.list_harga_id' => 'required|exists:list_harga,list_harga_id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ], [
            'items.required' => 'Minimal harus ada 1 item cucian',
            'items.min' => 'Minimal harus ada 1 item cucian',
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih',
        ]);
    }
    
    DB::beginTransaction();
    try {
        $layanan = Layanan::find($request->layanan_id);
        $estimasi = Carbon::now()->addDays($layanan->durasi_hari ?? 3);
        
        // ✅ HANDLE KILOAN OFFLINE
        if ($jenis_cucian === 'kiloan') {
            $beratKg = $request->berat_kiloan;
            
            $listHargaKiloan = ListHarga::where('harga_kiloan', '>', 0)->first();
            if (!$listHargaKiloan) {
                throw new \Exception('Tidak ada harga kiloan yang tersedia');
            }
            
            $totalHarga = $beratKg * $listHargaKiloan->harga_kiloan;
            
            // Buat cucian
            $cucian = Cucian::create([
                'pelanggan_id' => $request->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_cucian' => 'kiloan', // ✅ TAMBAHAN
                'jenis_order' => $jenis_order,
                'jenis_ambil' => $request->jenis_ambil,
                'tgl_order' => Carbon::now(),
                'estimasi' => $estimasi,
                'total_item' => 1,
                'total_berat' => $beratKg,
                'total_harga' => $totalHarga,
                'status_cucian' => 'menunggu',
                'catatan' => $request->catatan
            ]);
            
            CucianDetail::create([
                'cucian_id' => $cucian->cucian_id,
                'list_harga_id' => $listHargaKiloan->list_harga_id,
                'jumlah' => null,
                'berat_kg' => $beratKg,
                'harga_satuan' => null,
                'harga_kiloan' => $listHargaKiloan->harga_kiloan,
                'deskripsi' => 'Cucian kiloan'
            ]);
            
        } else {
            // ✅ HANDLE SATUAN OFFLINE
            $totalHarga = 0;
            $totalItem = count($request->items);
            
            foreach ($request->items as $item) {
                $listHarga = ListHarga::find($item['list_harga_id']);
                $jumlah = $item['jumlah'] ?? 1;
                $totalHarga += $jumlah * $listHarga->harga_satuan;
            }
            
            // Buat cucian
            $cucian = Cucian::create([
                'pelanggan_id' => $request->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_cucian' => 'satuan', // ✅ TAMBAHAN
                'jenis_order' => $jenis_order,
                'jenis_ambil' => $request->jenis_ambil,
                'tgl_order' => Carbon::now(),
                'estimasi' => $estimasi,
                'total_item' => $totalItem,
                'total_berat' => null,
                'total_harga' => $totalHarga,
                'status_cucian' => 'menunggu',
                'catatan' => $request->catatan
            ]);
            
            // Buat detail cucian
            foreach ($request->items as $item) {
                $listHarga = ListHarga::find($item['list_harga_id']);
                $jumlah = $item['jumlah'] ?? 1;
                
                CucianDetail::create([
                    'cucian_id' => $cucian->cucian_id,
                    'list_harga_id' => $item['list_harga_id'],
                    'jumlah' => $jumlah,
                    'berat_kg' => null,
                    'harga_satuan' => $listHarga->harga_satuan,
                    'harga_kiloan' => null,
                    'deskripsi' => $item['deskripsi'] ?? null
                ]);
            }
        }
        
        // Buat pembayaran
        Pembayaran::create([
            'cucian_id' => $cucian->cucian_id,
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => 'belum',
            'jumlah_bayar' => $totalHarga
        ]);
        
        DB::commit();
        
        return redirect()->route('staff.cucian.show', $cucian->cucian_id)
            ->with('success', 'Data cucian berhasil ditambahkan! No Order: ' . $cucian->getNoOrder());
            
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Cucian Store Error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Gagal menambahkan cucian: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Show cucian detail
     */
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

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $cucian = Cucian::with('detail.listHarga', 'layanan')->findOrFail($id);
        $pelanggan = Pelanggan::where('status', 'aktif')->orderBy('nama')->get();
        $layanan = Layanan::orderBy('nama_layanan')->get();
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        return view('staff.cucian.edit', compact('cucian', 'pelanggan', 'layanan', 'listHarga'));
    }

    /**
     * ✅ Show form untuk input berat (khusus kiloan ONLINE)
     */
    public function showInputBerat($id)
    {
        $cucian = Cucian::with(['pelanggan', 'layanan', 'detail.listHarga'])->findOrFail($id);
        
        // Check if layanan is kiloan
        if ($cucian->layanan->jenis_cucian !== 'kiloan') {
            return redirect()->back()
                ->with('error', 'Input berat hanya untuk layanan kiloan!');
        }
        
        // Check if already has weight
        if ($cucian->total_berat && $cucian->total_berat > 0) {
            return redirect()->route('staff.cucian.show', $id)
                ->with('info', 'Berat sudah diinput sebelumnya: ' . number_format($cucian->total_berat, 1) . ' Kg');
        }
        
        return view('staff.cucian.input-berat', compact('cucian'));
    }
    
    /**
     * ✅ Store input berat untuk kiloan ONLINE
     */
    public function storeInputBerat(Request $request, $id)
    {
        $validated = $request->validate([
            'berat_kg' => 'required|numeric|min:0.1|max:1000',
            'catatan_berat' => 'nullable|string|max:255',
        ], [
            'berat_kg.required' => 'Berat harus diisi',
            'berat_kg.min' => 'Berat minimal 0.1 Kg',
            'berat_kg.max' => 'Berat maksimal 1000 Kg',
        ]);
        
        DB::beginTransaction();
        try {
            $cucian = Cucian::with(['layanan', 'detail', 'pembayaran'])->findOrFail($id);
            
            // Validasi
            if ($cucian->layanan->jenis_cucian !== 'kiloan') {
                throw new \Exception('Order ini bukan cucian kiloan');
            }
            
            if ($cucian->total_berat && $cucian->total_berat > 0) {
                throw new \Exception('Berat sudah diinput sebelumnya');
            }
            
            $beratKg = $validated['berat_kg'];
            
            // Ambil harga kiloan dari detail atau list harga
            $detail = $cucian->detail->first();
            if (!$detail) {
                $listHarga = ListHarga::where('harga_kiloan', '>', 0)->first();
                if (!$listHarga) {
                    throw new \Exception('Harga kiloan tidak ditemukan');
                }
                
                // Buat detail baru
                $detail = CucianDetail::create([
                    'cucian_id' => $cucian->cucian_id,
                    'list_harga_id' => $listHarga->list_harga_id,
                    'jumlah' => null,
                    'berat_kg' => $beratKg,
                    'harga_satuan' => null,
                    'harga_kiloan' => $listHarga->harga_kiloan,
                    'deskripsi' => $validated['catatan_berat'] ?? 'Cucian kiloan',
                ]);
                
                $hargaPerKg = $listHarga->harga_kiloan;
            } else {
                // Update detail existing
                $hargaPerKg = $detail->harga_kiloan ?? $detail->listHarga->harga_kiloan;
                $detail->update([
                    'berat_kg' => $beratKg,
                    'deskripsi' => $validated['catatan_berat'] ?? $detail->deskripsi,
                ]);
            }
            
            $totalHarga = $beratKg * $hargaPerKg;
            
            // Update Order
            $cucian->update([
                'total_berat' => $beratKg,
                'total_harga' => $totalHarga,
                'total_item' => 1,
            ]);
            
            // Update Pembayaran
            if ($cucian->pembayaran) {
                $cucian->pembayaran->update([
                    'jumlah_bayar' => $totalHarga,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('staff.cucian.show', $cucian->cucian_id)
                ->with('success', "Berat berhasil diinput: {$beratKg} Kg. Total harga: Rp " . number_format($totalHarga, 0, ',', '.'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Store Input Berat Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal input berat: ' . $e->getMessage());
        }
    }

    /**
     * Update cucian
     */
    public function update(Request $request, $id)
    {
        $cucian = Cucian::with('detail.listHarga', 'layanan')->findOrFail($id);
        
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'catatan' => 'nullable|string',
            'status_cucian' => 'nullable|in:menunggu,diproses,selesai,diambil',
            'items' => 'nullable|array',
            'items.*.cucian_detail_id' => 'nullable|exists:cucian_detail,cucian_detail_id',
            'items.*.berat_kg' => 'nullable|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        try {
            $updateData = [
                'pelanggan_id' => $request->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_ambil' => $request->jenis_ambil,
                'catatan' => $request->catatan
            ];
            
            // Update berat kiloan jika ada
            if ($request->filled('items') && $cucian->layanan->jenis_cucian === 'kiloan') {
                $totalBerat = 0;
                $totalHarga = 0;
                
                foreach ($request->items as $item) {
                    if (isset($item['cucian_detail_id']) && isset($item['berat_kg'])) {
                        $detail = CucianDetail::find($item['cucian_detail_id']);
                        if ($detail && $detail->cucian_id == $cucian->cucian_id) {
                            $detail->update(['berat_kg' => $item['berat_kg']]);
                            
                            $totalBerat += $item['berat_kg'];
                            $totalHarga += $item['berat_kg'] * $detail->listHarga->harga_kiloan;
                        }
                    }
                }
                
                $updateData['total_berat'] = $totalBerat;
                $updateData['total_harga'] = $totalHarga;
                
                if ($cucian->pembayaran) {
                    $cucian->pembayaran->update([
                        'jumlah_bayar' => $totalHarga,
                        'catatan' => 'Berat sudah ditimbang: ' . $totalBerat . ' kg'
                    ]);
                }
            }
            
            // Update status jika ada
            if ($request->filled('status_cucian')) {
                $updateData['status_cucian'] = $request->status_cucian;
                
                if ($request->status_cucian === 'selesai' && !$cucian->tgl_selesai) {
                    $updateData['tgl_selesai'] = Carbon::now();
                }
                
                if ($request->status_cucian === 'diambil' && !$cucian->tgl_diambil) {
                    $updateData['tgl_diambil'] = Carbon::now();
                }
            }
            
            $cucian->update($updateData);
            
            DB::commit();
            
            return redirect()->route('staff.cucian.show', $id)
                ->with('success', 'Data cucian berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Cucian Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal update cucian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Quick update berat untuk layanan kiloan
     */
    public function updateBerat(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.cucian_detail_id' => 'required|exists:cucian_detail,cucian_detail_id',
            'items.*.berat_kg' => 'required|numeric|min:0.1',
        ], [
            'items.*.berat_kg.required' => 'Berat harus diisi',
            'items.*.berat_kg.min' => 'Berat minimal 0.1 Kg',
        ]);
        
        DB::beginTransaction();
        try {
            $cucian = Cucian::findOrFail($id);
            
            $totalBerat = 0;
            $totalHarga = 0;
            
            foreach ($request->items as $item) {
                $detail = CucianDetail::findOrFail($item['cucian_detail_id']);
                $beratBaru = $item['berat_kg'];
                
                $detail->berat_kg = $beratBaru;
                $detail->jumlah = null;
                $detail->save();
                
                $hargaKiloan = $detail->harga_kiloan ?? $detail->listHarga->harga_kiloan;
                $totalBerat += $beratBaru;
                $totalHarga += $beratBaru * $hargaKiloan;
            }
            
            $cucian->update([
                'total_berat' => $totalBerat,
                'total_harga' => $totalHarga,
                'status_cucian' => 'diproses'
            ]);
            
            if ($cucian->pembayaran && $cucian->pembayaran->status_bayar === 'belum') {
                $cucian->pembayaran->update([
                    'jumlah_bayar' => $totalHarga
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('staff.cucian.show', $id)
                ->with('success', 'Berat cucian berhasil diupdate! Total: ' . number_format($totalBerat, 1) . ' Kg - ' . $cucian->getFormattedTotalHarga());
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Berat Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal update berat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete cucian
     */
    public function destroy($id)
    {
        $cucian = Cucian::findOrFail($id);
        
        if ($cucian->status_cucian !== 'menunggu') {
            return redirect()->route('staff.cucian.index')
                ->with('error', 'Cucian hanya bisa dihapus jika status masih menunggu!');
        }
        
        DB::beginTransaction();
        try {
            $noOrder = $cucian->getNoOrder();
            $cucian->delete();
            
            DB::commit();
            
            return redirect()->route('staff.cucian.index')
                ->with('success', "Cucian {$noOrder} berhasil dihapus!");
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Cucian Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus cucian: ' . $e->getMessage());
        }
    }
}