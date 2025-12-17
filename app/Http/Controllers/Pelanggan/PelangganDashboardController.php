<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil pelanggan berdasarkan user yang login
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        if (!$pelanggan) {
            return redirect()->route('home')
                ->with('error', 'Data pelanggan tidak ditemukan!');
        }
        
        // Statistik order pelanggan
        $totalOrder = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)->count();
        $sedangProses = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)
            ->whereIn('status_cucian', ['menunggu', 'diproses'])
            ->count();
        $selesai = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)
            ->where('status_cucian', 'selesai')
            ->count();
        $menungguDiambil = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)
            ->where('status_cucian', 'selesai')
            ->count();
        
        // Total spending
        $totalSpending = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)
            ->sum('total_harga');
        
        // Order terbaru (5 terakhir)
        $recentOrders = Cucian::with(['layanan', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->pelanggan_id)
            ->orderBy('tgl_order', 'desc')
            ->take(5)
            ->get();
        
        $stats = [
            'total_order' => $totalOrder,
            'sedang_proses' => $sedangProses,
            'selesai' => $selesai,
            'menunggu_diambil' => $menungguDiambil,
            'total_spending' => $totalSpending
        ];
        
        return view('pelanggan.dashboard', compact('stats', 'recentOrders', 'pelanggan'));
    }
}