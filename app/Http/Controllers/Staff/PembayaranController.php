<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\CallbackService;

class PembayaranController extends Controller
{

    /**
     * Display a listing of pembayaran
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['cucian.pelanggan']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status_bayar', $request->status);
        }

        // Filter by metode
        if ($request->has('metode') && $request->metode !== 'all') {
            $query->where('metode_bayar', $request->metode);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('cucian.pelanggan', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('staff.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show detail pembayaran
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['cucian.pelanggan', 'cucian.detail.listHarga'])
            ->findOrFail($id);

        return view('staff.pembayaran.detail', compact('pembayaran'));
    }

    /**
     * Show payment form for offline customer
     */
    public function showPaymentForm($cucian_id)
    {
        $cucian = Cucian::with(['pelanggan', 'pembayaran', 'detail.listHarga'])
            ->findOrFail($cucian_id);

        // Check if already paid
        if ($cucian->isPaid()) {
            return redirect()->route('staff.pembayaran.show', $cucian->pembayaran->pembayaran_id)
                ->with('info', 'Cucian ini sudah dibayar lunas');
        }

        return view('staff.pembayaran.form', compact('cucian'));
    }

    /**
     * Process payment for offline customer
     */
    public function processPayment(Request $request, $cucian_id)
{
    $request->validate([
        'metode_bayar' => 'required|in:cash,transfer',
        'jumlah_bayar' => 'required|numeric|min:0',
        'catatan' => 'nullable|string'
    ], [
        'metode_bayar.required' => 'Metode pembayaran wajib dipilih',
        'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
        'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka',
        'jumlah_bayar.min' => 'Jumlah bayar minimal 0'
    ]);

    DB::beginTransaction();
    try {
        $cucian = Cucian::findOrFail($cucian_id);
        
        // ✅ HITUNG KEMBALIAN
        $totalHarga = $cucian->total_harga;
        $jumlahBayar = $request->jumlah_bayar;
        $kembalian = $jumlahBayar - $totalHarga;
        
        // ✅ VALIDASI: Jika cash, jumlah bayar harus >= total harga
        if ($request->metode_bayar == 'cash' && $jumlahBayar < $totalHarga) {
            return back()->withErrors(['jumlah_bayar' => 'Jumlah bayar tidak boleh kurang dari total harga!'])
                ->withInput();
        }
        
        // ✅ BUILD CATATAN (termasuk info kembalian)
        $catatanPembayaran = $request->catatan ?? '';
        if ($request->metode_bayar == 'cash') {
            $catatanInfo = "Jumlah Bayar: Rp " . number_format($jumlahBayar, 0, ',', '.');
            $catatanInfo .= "\nKembalian: Rp " . number_format($kembalian, 0, ',', '.');
            
            if (!empty($catatanPembayaran)) {
                $catatanInfo .= "\nCatatan: " . $catatanPembayaran;
            }
            $catatanPembayaran = $catatanInfo;
        }

        // Check if payment already exists
        if ($cucian->hasPembayaran()) {
            $pembayaran = $cucian->pembayaran;
            
            // Update existing payment
            $pembayaran->update([
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => 'lunas',
                'jumlah_bayar' => $totalHarga, // ✅ Simpan total harga asli
                'tgl_bayar' => now(),
                'catatan' => $catatanPembayaran
            ]);
        } else {
            // Create new payment
            $pembayaran = Pembayaran::create([
                'cucian_id' => $cucian_id,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => 'lunas',
                'jumlah_bayar' => $totalHarga, // ✅ Simpan total harga asli
                'tgl_bayar' => now(),
                'catatan' => $catatanPembayaran
            ]);
        }

        DB::commit();
        
        // ✅ SUCCESS MESSAGE DENGAN INFO KEMBALIAN
        $successMessage = 'Pembayaran berhasil diproses!';
        if ($request->metode_bayar == 'cash' && $kembalian > 0) {
            $successMessage .= ' Kembalian: Rp ' . number_format($kembalian, 0, ',', '.');
        }
        
        return redirect()->route('staff.pembayaran.show', $pembayaran->pembayaran_id)
            ->with('success', $successMessage);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}

    /**
     * Create Midtrans payment
     */
    public function createMidtransPayment($cucian_id)
    {
        DB::beginTransaction();
        try {
            $cucian = Cucian::with(['pelanggan'])->findOrFail($cucian_id);
            
            // Check if payment already exists
            if ($cucian->hasPembayaran()) {
                $pembayaran = $cucian->pembayaran;
                
                // Generate snap token if not exists
                if (empty($pembayaran->snap_token)) {
                    $midtrans = new CreateSnapTokenService($pembayaran);
                    $snapToken = $midtrans->getSnapToken();
                    
                    $pembayaran->update([
                        'snap_token' => $snapToken,
                        'metode_bayar' => 'midtrans'
                    ]);
                }
            } else {
                // Create new payment
                $pembayaran = Pembayaran::create([
                    'cucian_id' => $cucian_id,
                    'metode_bayar' => 'midtrans',
                    'status_bayar' => 'belum',
                    'jumlah_bayar' => $cucian->total_harga,
                ]);
                
                // Generate snap token
                $midtrans = new CreateSnapTokenService($pembayaran);
                $snapToken = $midtrans->getSnapToken();
                
                $pembayaran->update([
                    'snap_token' => $snapToken
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('staff.pembayaran.midtrans', $pembayaran->pembayaran_id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleMidtransCallback(Request $request)
    {
        try {
            // Log incoming request untuk debugging
            \Log::info('Midtrans Callback', [
                'body' => $request->all()
            ]);

            $callback = new CallbackService;

            if ($callback->isSignatureKeyVerified()) {
                $notification = $callback->getNotification();
                $pembayaran = $callback->getPembayaran();
                
                // PERBAIKAN: Check jika pembayaran null
                if (!$pembayaran) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembayaran not found'
                    ], 404);
                }

                if ($callback->isSuccess()) {
                    $pembayaran->update([
                        'status_bayar' => 'lunas',
                        'tgl_bayar' => now(),
                        'catatan' => 'Pembayaran sukses melalui Midtrans'
                    ]);
                    
                    \Log::info('Payment Success', ['pembayaran_id' => $pembayaran->pembayaran_id]);
                }

                if ($callback->isExpire()) {
                    $pembayaran->update([
                        'status_bayar' => 'expired',
                        'catatan' => 'Pembayaran expired'
                    ]);
                    
                    \Log::info('Payment Expired', ['pembayaran_id' => $pembayaran->pembayaran_id]);
                }

                if ($callback->isCancelled()) {
                    $pembayaran->update([
                        'status_bayar' => 'batal',
                        'catatan' => 'Pembayaran dibatalkan'
                    ]);
                    
                    \Log::info('Payment Cancelled', ['pembayaran_id' => $pembayaran->pembayaran_id]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil diproses'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Signature key tidak valid'
            ], 403);

        } catch (\Exception $e) {
            \Log::error('Midtrans Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Midtrans payment page
     */
    public function showMidtransPayment($id)
    {
        $pembayaran = Pembayaran::with(['cucian.pelanggan', 'cucian.layanan'])->findOrFail($id);
        $cucian = $pembayaran->cucian;
        
        // Check if already paid
        if ($pembayaran->status_bayar === 'lunas') {
            return redirect()->route('staff.pembayaran.index')
                ->with('info', 'Pembayaran sudah lunas!');
        }
        
        // PERBAIKAN: Generate snap token jika belum ada
        if (empty($pembayaran->snap_token)) {
            try {
                $midtrans = new CreateSnapTokenService($pembayaran);
                $snapToken = $midtrans->getSnapToken();
                
                $pembayaran->update([
                    'snap_token' => $snapToken
                ]);
                
                // Refresh model
                $pembayaran->refresh();
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Gagal membuat snap token: ' . $e->getMessage()]);
            }
        }
        
        return view('staff.pembayaran.midtrans-payment', compact('cucian', 'pembayaran'));
    }

    /**
     * Show form to validate transfer payment (online)
     */
    public function showValidateForm($id)
    {
        $pembayaran = Pembayaran::with(['cucian.pelanggan'])
            ->findOrFail($id);

        // Only for transfer and belum lunas
        if ($pembayaran->metode_bayar !== 'transfer' || $pembayaran->status_bayar === 'lunas') {
            return redirect()->route('staff.pembayaran.show', $id)
                ->with('info', 'Pembayaran ini tidak perlu divalidasi');
        }

        return view('staff.pembayaran.validate', compact('pembayaran'));
    }

    /**
     * Validate transfer payment (approve/reject)
     */
    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);

            if ($request->action === 'approve') {
                $pembayaran->update([
                    'status_bayar' => 'lunas',
                    'tgl_bayar' => now(),
                    'catatan' => $request->catatan ?? 'Pembayaran transfer divalidasi'
                ]);

                $message = 'Pembayaran berhasil divalidasi dan disetujui!';
            } else {
                $pembayaran->update([
                    'status_bayar' => 'belum',
                    'bukti_bayar' => null,
                    'catatan' => $request->catatan ?? 'Bukti pembayaran ditolak'
                ]);

                $message = 'Bukti pembayaran ditolak. Pelanggan perlu upload ulang.';
            }

            DB::commit();

            return redirect()->route('staff.pembayaran.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete pembayaran (cancel)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $cucian_id = $pembayaran->cucian_id;
            
            $pembayaran->delete();

            DB::commit();

            return redirect()->route('staff.cucian.show', $cucian_id)
                ->with('success', 'Pembayaran berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}