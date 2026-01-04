<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusCucianController extends Controller
{
    /**
     * Display listing of cucian untuk update status
     * Hanya tampilkan cucian yang belum diambil
     */
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        // Query hanya cucian yang belum diambil
        $query = Cucian::with(['pelanggan', 'layanan', 'pembayaran', 'penjemputan', 'pengantaran'])
                       ->whereIn('status_cucian', ['menunggu', 'diproses', 'selesai']);
        
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
        
        $cucian = $query->orderBy('tgl_order', 'desc')
                       ->paginate($perPage)
                       ->appends($request->except('page'));
        
        // Hitung statistik (hanya yang belum diambil)
        $totalMenunggu = Cucian::where('status_cucian', 'menunggu')->count();
        $totalProses = Cucian::where('status_cucian', 'diproses')->count();
        $totalSelesai = Cucian::where('status_cucian', 'selesai')->count();
        $totalDiambil = Cucian::where('status_cucian', 'diambil')->count();
        
        return view('staff.status-cucian.index', compact(
            'cucian',
            'totalMenunggu',
            'totalProses',
            'totalSelesai',
            'totalDiambil'
        ));
    }

    /**
     * Update status cucian dengan validasi flow
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,diambil',
        ]);

        DB::beginTransaction();
        try {
            $cucian = Cucian::with(['pembayaran', 'penjemputan', 'pengantaran'])->findOrFail($id);
            
            $currentStatus = $cucian->status_cucian;
            $newStatus = $request->status;
            
            // ✅ VALIDASI: Tidak bisa update ke status yang sama
            if ($currentStatus === $newStatus) {
                return redirect()->back()
                    ->with('info', 'Status sudah ' . ucfirst($newStatus));
            }
            
            // ✅ VALIDASI TRANSISI STATUS (harus berurutan)
            $allowedTransitions = [
                'menunggu' => ['diproses'],
                'diproses' => ['selesai'],
                'selesai' => ['diambil'],
            ];
            
            // Cek apakah transisi status valid
            if (isset($allowedTransitions[$currentStatus]) && 
                !in_array($newStatus, $allowedTransitions[$currentStatus])) {
                return redirect()->back()
                    ->with('error', 'Transisi status tidak valid! Status saat ini: ' . ucfirst($currentStatus) . ', tidak bisa langsung ke: ' . ucfirst($newStatus));
            }
            
            // ✅ VALIDASI KHUSUS: MENUNGGU → DIPROSES
            if ($currentStatus === 'menunggu' && $newStatus === 'diproses') {
                if (!$cucian->canBeProcessed()) {
                    $reason = $cucian->getCannotProcessReason();
                    
                    // Berikan pesan yang lebih informatif
                    $additionalInfo = '';
                    if ($cucian->isOnline()) {
                        $additionalInfo = ' (Order Online: Butuh penjemputan selesai + pembayaran lunas)';
                    } else {
                        $additionalInfo = ' (Order Offline: Butuh pembayaran lunas)';
                    }
                    
                    return redirect()->back()
                        ->with('error', 'Cucian tidak bisa diproses! Alasan: ' . $reason . $additionalInfo);
                }
            }
            
            // ✅ VALIDASI KHUSUS: DIPROSES → SELESAI
            if ($currentStatus === 'diproses' && $newStatus === 'selesai') {
                // Bisa tambahkan validasi tambahan jika perlu
                // Misalnya: sudah ada foto hasil cucian, dll
            }
            
            // ✅ VALIDASI KHUSUS: SELESAI → DIAMBIL
            if ($currentStatus === 'selesai' && $newStatus === 'diambil') {
                // Untuk online dengan pengantaran, pastikan pengantaran selesai
                if ($cucian->isOnline() && $cucian->needsDelivery()) {
                    if (!$cucian->pengantaran) {
                        return redirect()->back()
                            ->with('error', 'Cucian belum bisa ditandai diambil! Belum ada pengantaran.');
                    }
                    if ($cucian->pengantaran->status !== 'selesai') {
                        return redirect()->back()
                            ->with('error', 'Cucian belum bisa ditandai diambil! Pengantaran belum selesai (Status: ' . ucfirst($cucian->pengantaran->status) . ')');
                    }
                }
            }
            
            // Update status
            $cucian->status_cucian = $newStatus;
            
            // Update tanggal berdasarkan status
            if ($newStatus == 'selesai' && !$cucian->tgl_selesai) {
                $cucian->tgl_selesai = now();
            } elseif ($newStatus == 'diambil' && !$cucian->tgl_diambil) {
                $cucian->tgl_diambil = now();
                // Set tgl_selesai jika belum ada
                if (!$cucian->tgl_selesai) {
                    $cucian->tgl_selesai = now();
                }
            }
            
            $cucian->save();
            
            DB::commit();
            
            $statusLabel = [
                'diproses' => 'Cucian berhasil diproses!',
                'selesai' => 'Cucian berhasil diselesaikan!',
                'diambil' => 'Cucian berhasil ditandai sudah diambil!',
            ];
            
            return redirect()->back()
                ->with('success', $statusLabel[$newStatus] ?? 'Status berhasil diupdate');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Status Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }
    
    /**
     * Konfirmasi cucian menunggu menjadi diproses
     */
    public function konfirmasi(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai,diambil',
        ]);

        DB::beginTransaction();
        try {
            $cucian = Cucian::with(['pembayaran', 'penjemputan', 'pengantaran'])->findOrFail($id);
            $newStatus = $request->status;
            
            // ✅ VALIDASI: Hanya bisa konfirmasi dari menunggu ke diproses
            if ($cucian->status_cucian === 'menunggu' && $newStatus === 'diproses') {
                if (!$cucian->canBeProcessed()) {
                    $reason = $cucian->getCannotProcessReason();
                    
                    // Berikan pesan yang lebih informatif
                    $additionalInfo = '';
                    if ($cucian->isOnline()) {
                        $additionalInfo = ' (Order Online: Butuh penjemputan selesai + pembayaran lunas)';
                    } else {
                        $additionalInfo = ' (Order Offline: Butuh pembayaran lunas)';
                    }
                    
                    return redirect()->back()
                        ->with('error', 'Cucian tidak bisa diproses! Alasan: ' . $reason . $additionalInfo);
                }
            }
            
            // Update status
            $cucian->status_cucian = $newStatus;
            
            // Update tanggal sesuai status
            if ($newStatus == 'selesai' && !$cucian->tgl_selesai) {
                $cucian->tgl_selesai = now();
            } elseif ($newStatus == 'diambil' && !$cucian->tgl_diambil) {
                $cucian->tgl_diambil = now();
                if (!$cucian->tgl_selesai) {
                    $cucian->tgl_selesai = now();
                }
            }
            
            $cucian->save();
            
            DB::commit();
            
            $messages = [
                'diproses' => 'Cucian berhasil dikonfirmasi dan akan segera diproses!',
                'selesai' => 'Cucian berhasil diselesaikan!',
                'diambil' => 'Cucian berhasil ditandai sudah diambil!',
            ];
            
            return redirect()->back()
                ->with('success', $messages[$newStatus] ?? 'Status berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Konfirmasi Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengkonfirmasi status: ' . $e->getMessage());
        }
    }
    
    /**
     * Show detail cucian
     */
    public function show($id)
    {
        $cucian = Cucian::with([
            'pelanggan',
            'layanan',
            'detail.listHarga',
            'pembayaran',
            'penjemputan',
            'pengantaran'
        ])->findOrFail($id);
        
        return view('staff.status-cucian.detail', compact('cucian'));
    }
}