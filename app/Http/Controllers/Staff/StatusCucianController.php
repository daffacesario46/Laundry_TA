<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use Illuminate\Http\Request;

class StatusCucianController extends Controller
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
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_cucian', $request->status);
        }
        
        $cucian = $query->orderBy('tgl_order', 'desc')->paginate($perPage);
        
        // Hitung statistik
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

    public function show($id)
    {
        $cucian = Cucian::with([
            'pelanggan',
            'layanan',
            'detail.listHarga',
            'pembayaran'
        ])->findOrFail($id);
        
        return view('staff.status-cucian.detail', compact('cucian'));
    }

    public function updateStatus(Request $request, $id)
    {
        $cucian = Cucian::findOrFail($id);
        
        $request->validate([
            'status_cucian' => 'required|in:menunggu,diproses,selesai,diambil',
            'catatan' => 'nullable|string'
        ]);
        
        $oldStatus = $cucian->status_cucian;
        $newStatus = $request->status_cucian;
        
        $cucian->status_cucian = $newStatus;
        
        // Update tanggal berdasarkan status
        if ($newStatus === 'selesai' && !$cucian->tgl_selesai) {
            $cucian->tgl_selesai = now();
        }
        
        if ($newStatus === 'diambil' && !$cucian->tgl_diambil) {
            $cucian->tgl_diambil = now();
            
            // Jika belum ada tgl_selesai, set juga
            if (!$cucian->tgl_selesai) {
                $cucian->tgl_selesai = now();
            }
        }
        
        // Update catatan jika ada
        if ($request->filled('catatan')) {
            $cucian->catatan = $request->catatan;
        }
        
        $cucian->save();
        
        $statusLabel = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil'
        ];
        
        return redirect()->back()
            ->with('success', "Status cucian berhasil diubah dari {$statusLabel[$oldStatus]} menjadi {$statusLabel[$newStatus]}!");
    }
    
    public function konfirmasi(Request $request, $id)
    {
        $cucian = Cucian::findOrFail($id);
        
        // Validasi status yang bisa dikonfirmasi
        if ($cucian->status_cucian !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Hanya cucian dengan status menunggu yang bisa dikonfirmasi!');
        }
        
        // Ubah status menjadi diproses
        $cucian->status_cucian = 'diproses';
        $cucian->save();
        
        return redirect()->back()
            ->with('success', 'Cucian berhasil dikonfirmasi dan akan segera diproses!');
    }
    
    public function selesai($id)
    {
        $cucian = Cucian::findOrFail($id);
        
        // Validasi status
        if ($cucian->status_cucian !== 'diproses') {
            return redirect()->back()
                ->with('error', 'Hanya cucian yang sedang diproses yang bisa diselesaikan!');
        }
        
        // Ubah status menjadi selesai
        $cucian->status_cucian = 'selesai';
        $cucian->tgl_selesai = now();
        $cucian->save();
        
        return redirect()->back()
            ->with('success', 'Cucian berhasil diselesaikan!');
    }
    
    public function diambil($id)
    {
        $cucian = Cucian::findOrFail($id);
        
        // Validasi status
        if ($cucian->status_cucian !== 'selesai') {
            return redirect()->back()
                ->with('error', 'Hanya cucian yang sudah selesai yang bisa diambil!');
        }
        
        // Cek pembayaran
        if ($cucian->pembayaran && $cucian->pembayaran->status_bayar !== 'lunas') {
            return redirect()->back()
                ->with('error', 'Pelanggan harus melunasi pembayaran terlebih dahulu!');
        }
        
        // Ubah status menjadi diambil
        $cucian->status_cucian = 'diambil';
        $cucian->tgl_diambil = now();
        $cucian->save();
        
        return redirect()->back()
            ->with('success', 'Cucian berhasil diambil oleh pelanggan!');
    }
}