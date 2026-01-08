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
use Carbon\Carbon;
use App\Services\Midtrans\Midtrans;


class OrderController extends Controller
{
    /**
     * Get authenticated pelanggan (with fallback for development)
     */
    private function getPelanggan()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return Pelanggan::where('users_id', $user->users_id)->first();
        } else {
            $pelanggan = Pelanggan::whereNotNull('users_id')->first();
            if (!$pelanggan) {
                $pelanggan = Pelanggan::first();
            }
            return $pelanggan;
        }
    }

    /**
     * Display listing of orders
     */
    public function index(Request $request)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $perPage = $request->get('paginate', 10);
        
        $query = Cucian::with(['layanan', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->pelanggan_id);
        
        if ($request->filled('status')) {
            $query->where('status_cucian', $request->status);
        }
        
        $orders = $query->orderBy('tgl_order', 'desc')
                       ->paginate($perPage)
                       ->appends($request->except('page'));
        
        return view('pelanggan.order.index', compact('orders'));
    }

    /**
     * Show form for creating new order
     */
    public function create()
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('info', 'Lengkapi profile Anda terlebih dahulu');
        }
        
        $layanan = Layanan::orderBy('nama_layanan')->get();
        $listHarga = ListHarga::orderBy('nama_item')->get();
        
        return view('pelanggan.order.create', compact('pelanggan', 'layanan', 'listHarga'));
    }

    /**
     * Store new order
     * ✅ UPDATED: Support jenis_cucian (kiloan/satuan)
     */
    public function store(Request $request)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        // ✅ VALIDASI BERBEDA UNTUK KILOAN VS SATUAN
        $rules = [
            'layanan_id' => 'required|exists:layanan,layanan_id',
            'jenis_cucian' => 'required|in:kiloan,satuan',
            'jenis_ambil' => 'required|in:diantar,ambil_sendiri',
            'metode_bayar' => 'required|in:cash,transfer,e-wallet',
            'catatan' => 'nullable|string|max:500',
        ];
        
        // Jika SATUAN, items wajib ada
        if ($request->jenis_cucian === 'satuan') {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.list_harga_id'] = 'required|exists:list_harga,list_harga_id';
            $rules['items.*.jumlah'] = 'required|integer|min:1';
            $rules['items.*.deskripsi'] = 'nullable|string|max:255';
        }
        
        $validated = $request->validate($rules, [
            'layanan_id.required' => 'Layanan harus dipilih',
            'jenis_cucian.required' => 'Jenis cucian harus dipilih',
            'jenis_ambil.required' => 'Jenis pengambilan harus dipilih',
            'items.required' => 'Minimal harus ada 1 item untuk cucian satuan',
            'metode_bayar.required' => 'Metode pembayaran harus dipilih'
        ]);
        
        DB::beginTransaction();
        try {
            $layanan = Layanan::findOrFail($request->layanan_id);
            $estimasi = Carbon::now()->addDays($layanan->durasi_hari ?? 3);
            
            // ✅ LOGIC BERBEDA UNTUK KILOAN VS SATUAN
            if ($request->jenis_cucian === 'kiloan') {
                // KILOAN: Berat belum ada, harga = 0, akan diinput staff nanti
                $cucian = Cucian::create([
                    'pelanggan_id' => $pelanggan->pelanggan_id,
                    'layanan_id' => $request->layanan_id,
                    'jenis_order' => 'online',
                    'jenis_ambil' => $request->jenis_ambil,
                    'tgl_order' => Carbon::now(),
                    'estimasi' => $estimasi,
                    'total_item' => 0,
                    'total_berat' => null, // ✅ Akan diisi staff
                    'total_harga' => 0,    // ✅ Akan dihitung setelah berat diinput
                    'status_cucian' => 'menunggu',
                    'catatan' => $request->catatan,
                ]);
                
                // Buat placeholder detail untuk input berat nanti
                $listHargaKiloan = ListHarga::where('harga_kiloan', '>', 0)->first();
                if (!$listHargaKiloan) {
                    throw new \Exception('Tidak ada harga kiloan yang tersedia di sistem');
                }
                
                CucianDetail::create([
                    'cucian_id' => $cucian->cucian_id,
                    'list_harga_id' => $listHargaKiloan->list_harga_id,
                    'jumlah' => null,
                    'berat_kg' => null, // ✅ Akan diisi staff
                    'harga_satuan' => null,
                    'harga_kiloan' => $listHargaKiloan->harga_kiloan,
                    'deskripsi' => 'Cucian kiloan (berat akan diinput setelah penjemputan)',
                ]);
                
                $totalHarga = 0; // Akan dihitung setelah berat diinput
                
            } else {
                // SATUAN: Hitung langsung dari items
                $totalHarga = 0;
                $totalItem = count($request->items);
                
                // Buat order
                $cucian = Cucian::create([
                    'pelanggan_id' => $pelanggan->pelanggan_id,
                    'layanan_id' => $request->layanan_id,
                    'jenis_order' => 'online',
                    'jenis_ambil' => $request->jenis_ambil,
                    'tgl_order' => Carbon::now(),
                    'estimasi' => $estimasi,
                    'total_item' => $totalItem,
                    'total_berat' => null,
                    'total_harga' => 0, // Akan diupdate setelah loop
                    'status_cucian' => 'menunggu',
                    'catatan' => $request->catatan,
                ]);
                
                // Buat detail items
                foreach ($request->items as $item) {
                    $listHarga = ListHarga::findOrFail($item['list_harga_id']);
                    
                    $detail = CucianDetail::create([
                        'cucian_id' => $cucian->cucian_id,
                        'list_harga_id' => $item['list_harga_id'],
                        'jumlah' => $item['jumlah'],
                        'berat_kg' => null,
                        'harga_satuan' => $listHarga->harga_satuan,
                        'harga_kiloan' => null,
                        'deskripsi' => $item['deskripsi'] ?? null,
                    ]);
                    
                    $subtotal = $detail->jumlah * $listHarga->harga_satuan;
                    $totalHarga += $subtotal;
                }
                
                // Update total harga
                $cucian->update(['total_harga' => $totalHarga]);
            }
            
            // Buat Pembayaran
            Pembayaran::create([
                'cucian_id' => $cucian->cucian_id,
                'metode_bayar' => $request->metode_bayar,
                'jumlah_bayar' => $totalHarga,
                'status_bayar' => 'belum',
                'tgl_bayar' => null,
            ]);
            
           // Buat Penjemputan (karena online)
                Penjemputan::create([
                'cucian_id' => $cucian->cucian_id,
                'tgl_order' => Carbon::now()->addDay(), // ✅ FIXED: tgl_order (bukan tgl_jemput)
                'alamat_jemput' => $pelanggan->alamat,
                'status' => 'menunggu', // ✅ FIXED: menunggu (bukan pending)
            ]);
            
            DB::commit();
            
            $message = 'Order berhasil dibuat! No Order: ' . $cucian->getNoOrder();
            if ($request->jenis_cucian === 'kiloan') {
                $message .= ' - Berat cucian akan diinput oleh staff setelah penjemputan.';
            }
            
            return redirect()
                ->route('pelanggan.order.detail', $cucian->cucian_id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order Store Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show order detail
     */
    public function detail($id)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $order = Cucian::with([
            'pelanggan',
            'layanan',
            'detail.listHarga',
            'pembayaran'
        ])
        ->where('cucian_id', $id)
        ->where('pelanggan_id', $pelanggan->pelanggan_id)
        ->firstOrFail();
        
        return view('pelanggan.order.detail', compact('order', 'pelanggan'));
    }

        /**
     * Show order detail (alias untuk detail)
     */
    public function show($id)
    {
        return $this->detail($id);
    }

    
    /**
     * ✅ NEW: Show form upload bukti pembayaran
     */
    public function showUploadBukti($id)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $order = Cucian::with(['pembayaran', 'layanan'])
            ->where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();
        
        // ✅ VALIDASI: Untuk kiloan, berat harus sudah diinput
        if ($order->layanan->jenis_cucian === 'kiloan') {
            if (!$order->total_berat || $order->total_berat <= 0) {
                return redirect()
                    ->route('pelanggan.order.detail', $id)
                    ->with('error', 'Cucian kiloan harus diinput beratnya terlebih dahulu oleh staff sebelum melakukan pembayaran.');
            }
        }
        
        if (!$order->pembayaran) {
            return redirect()->back()
                ->with('error', 'Data pembayaran tidak ditemukan!');
        }
        
        if ($order->pembayaran->metode_bayar !== 'transfer') {
            return redirect()->back()
                ->with('error', 'Upload bukti hanya untuk metode transfer!');
        }
        
        return view('pelanggan.order.upload-bukti', compact('order'));
    }
    
    /**
     * Upload bukti pembayaran
     */
    public function uploadBukti(Request $request, $id)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $cucian = Cucian::with('layanan')
            ->where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();
        
        // ✅ VALIDASI: Untuk kiloan, berat harus sudah diinput
        if ($cucian->layanan->jenis_cucian === 'kiloan') {
            if (!$cucian->total_berat || $cucian->total_berat <= 0) {
                return redirect()->back()
                    ->with('error', 'Berat cucian belum diinput oleh staff. Pembayaran tidak dapat diproses.');
            }
        }
        
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
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }
            
            // Upload bukti baru
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            
            $pembayaran->bukti_bayar = $path;
            $pembayaran->tgl_bayar = Carbon::now();
            $pembayaran->save();
            
            DB::commit();
            
            return redirect()
                ->route('pelanggan.order.detail', $id)
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi staff.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Upload Bukti Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal upload bukti: ' . $e->getMessage());
        }
    }
    
    /**
     * Cancel order (hanya jika status masih menunggu)
     */
    public function cancel($id)
    {
        $pelanggan = $this->getPelanggan();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        $cucian = Cucian::where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();
        
        if ($cucian->status_cucian !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Order hanya bisa dibatalkan jika status masih menunggu!');
        }
        
        DB::beginTransaction();
        try {
            $noOrder = $cucian->getNoOrder();
            
            // Hapus bukti bayar jika ada
            if ($cucian->pembayaran && $cucian->pembayaran->bukti_bayar) {
                Storage::disk('public')->delete($cucian->pembayaran->bukti_bayar);
            }
            
            $cucian->delete();
            
            DB::commit();
            
            return redirect()->route('pelanggan.order.index')
                ->with('success', "Order {$noOrder} berhasil dibatalkan!");
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Cancel Order Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membatalkan order: ' . $e->getMessage());
        }
    }
    public function createMidtransPayment($id)
    {
        $pelanggan = $this->getPelanggan();
        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }

        $order = Cucian::with(['detail.listHarga', 'pembayaran', 'layanan'])
            ->where('cucian_id', $id)
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->firstOrFail();

        // Validasi: total harga harus ada
        if (!$order->total_harga || $order->total_harga <= 0) {
            return redirect()->back()
                ->with('error', 'Total harga belum tersedia. Untuk kiloan, tunggu staff input berat terlebih dahulu.');
        }

        // Validasi: pembayaran belum lunas
        if ($order->pembayaran && $order->pembayaran->status_bayar === 'lunas') {
            return redirect()->back()
                ->with('info', 'Pembayaran sudah lunas!');
        }

        DB::beginTransaction();
        try {
            $midtrans = new Midtrans();
            $snapToken = $midtrans->createSnapToken($order, $pelanggan);

            // Update pembayaran dengan snap token
            $order->pembayaran->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            return view('pelanggan.order.payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

/**
 * Handle Midtrans callback/notification
 */
public function handleMidtransCallback(Request $request)
    {
        $midtrans = new Midtrans();
        
        try {
            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Extract cucian_id from order_id
            preg_match('/ORDER-(\d+)-/', $orderId, $matches);
            $cucianId = $matches[1] ?? null;

            if (!$cucianId) {
                \Log::error('Invalid order_id format: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID']);
            }

            $pembayaran = Pembayaran::whereHas('cucian', function($q) use ($cucianId) {
                $q->where('cucian_id', $cucianId);
            })->first();

            if (!$pembayaran) {
                \Log::error('Payment not found for cucian_id: ' . $cucianId);
                return response()->json(['status' => 'error', 'message' => 'Payment not found']);
            }

            // Update payment data
            $pembayaran->transaction_id = $notif->transaction_id;
            $pembayaran->payment_type = $type;

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $pembayaran->status_bayar = 'pending';
                    } else {
                        $pembayaran->status_bayar = 'lunas';
                        $pembayaran->tgl_bayar = now();
                    }
                }
            } elseif ($transaction == 'settlement') {
                $pembayaran->status_bayar = 'lunas';
                $pembayaran->tgl_bayar = now();
            } elseif ($transaction == 'pending') {
                $pembayaran->status_bayar = 'pending';
            } elseif ($transaction == 'deny') {
                $pembayaran->status_bayar = 'gagal';
            } elseif ($transaction == 'expire') {
                $pembayaran->status_bayar = 'expired';
            } elseif ($transaction == 'cancel') {
                $pembayaran->status_bayar = 'dibatalkan';
            }

            $pembayaran->save();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}