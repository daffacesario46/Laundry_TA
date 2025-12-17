<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\CucianDetail;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\ListHarga;
use App\Models\Pembayaran;
use App\Models\Penjemputan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        if (!$pelanggan) {
            return redirect()->route('home')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $perPage = $request->get('paginate', 10);
        
        $query = Cucian::with(['layanan', 'pembayaran', 'detail.listHarga'])
            ->where('pelanggan_id', $pelanggan->pelanggan_id);
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_cucian', $request->status);
        }
        
        $orders = $query->orderBy('tgl_order', 'desc')->paginate($perPage);
        
        return view('pelanggan.order.index', compact('orders'));
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        $order = Cucian::with([
            'layanan',
            'detail.listHarga',
            'pembayaran',
            'pelanggan'
        ])
        ->where('cucian_id', $id)
        ->where('pelanggan_id', $pelanggan->pelanggan_id)
        ->firstOrFail();
        
        return view('pelanggan.order.detail', compact('order'));
    }
    
    public function create()
    {
        // Ambil layanan yang tersedia
        $layanan = Layanan::orderBy('nama_layanan')->get();
        
        // Ambil list harga (untuk pilihan item jika diperlukan)
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        // Ambil data pelanggan untuk alamat default
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        return view('pelanggan.order.create', compact('layanan', 'listHarga', 'pelanggan'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        if (!$pelanggan) {
            return redirect()->back()
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $request->validate([
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'alamat_jemput' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.list_harga_id' => 'required|exists:list_harga,list_harga_id',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.berat_kg' => 'nullable|numeric|min:0',
            'metode_bayar' => 'required|in:cash,transfer',
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
            
            // Buat cucian (order online)
            $cucian = Cucian::create([
                'pelanggan_id' => $pelanggan->pelanggan_id,
                'layanan_id' => $request->layanan_id,
                'jenis_order' => 'online',
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
            
            // Buat pembayaran
            Pembayaran::create([
                'cucian_id' => $cucian->cucian_id,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => 'belum',
                'jumlah_bayar' => $totalHarga
            ]);
            
            // Buat penjemputan (karena order online perlu dijemput)
            Penjemputan::create([
                'cucian_id' => $cucian->cucian_id,
                'alamat_jemput' => $request->alamat_jemput,
                'status' => 'menunggu',
                'tgl_order' => now(),
                'catatan' => 'Order online - Menunggu penjemputan'
            ]);
            
            DB::commit();
            
            return redirect()->route('pelanggan.order.index')
                ->with('success', 'Order berhasil dibuat! Silakan tunggu konfirmasi penjemputan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Upload bukti pembayaran
    public function uploadBukti(Request $request, $id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        $cucian = Cucian::where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();
        
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        DB::beginTransaction();
        try {
            $pembayaran = $cucian->pembayaran;
            
            if (!$pembayaran) {
                return redirect()->back()
                    ->with('error', 'Data pembayaran tidak ditemukan!');
            }
            
            // Hapus bukti lama jika ada
            if ($pembayaran->bukti_bayar) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }
            
            // Upload bukti baru
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            
            $pembayaran->bukti_bayar = $path;
            $pembayaran->tgl_bayar = now();
            $pembayaran->save();
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal upload bukti: ' . $e->getMessage());
        }
    }
    
    // Cancel order (hanya jika status masih menunggu)
    public function cancel($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        $cucian = Cucian::where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();
        
        if ($cucian->status_cucian !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Order hanya bisa dibatalkan jika status masih menunggu!');
        }
        
        DB::beginTransaction();
        try {
            $cucian->delete();
            
            DB::commit();
            
            return redirect()->route('pelanggan.order.index')
                ->with('success', 'Order berhasil dibatalkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }
}