<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pelanggan;
use App\Models\Penjemputan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik cucian
        $totalCucian = Cucian::count();
        $cucianMenunggu = Cucian::where('status_cucian', 'menunggu')->count();
        $cucianProses = Cucian::where('status_cucian', 'diproses')->count();
        $cucianSelesai = Cucian::where('status_cucian', 'selesai')->count();
        
        // Total pelanggan
        $totalPelanggan = Pelanggan::where('status', 'aktif')->count();
        
        // Cucian terbaru (10 terakhir)
        $cucianTerbaru = Cucian::with(['pelanggan', 'layanan'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Cucian yang perlu dikonfirmasi (status menunggu)
        $cucianMenungguKonfirmasi = Cucian::with(['pelanggan', 'layanan'])
            ->where('status_cucian', 'menunggu')
            ->orderBy('tgl_order', 'desc')
            ->get();
        
        // Penjemputan yang perlu diproses
        $penjemputanMenunggu = Penjemputan::with(['cucian.pelanggan'])
            ->where('status', 'menunggu')
            ->orderBy('tgl_order', 'desc')
            ->take(5)
            ->get();
        
        // Statistik hari ini
        $today = now()->format('Y-m-d');
        $cucianHariIni = Cucian::whereDate('tgl_order', $today)->count();
        $pendapatanHariIni = Cucian::whereDate('tgl_order', $today)
            ->sum('total_harga');
        
        return view('staff.dashboard', compact(
            'totalCucian',
            'cucianMenunggu',
            'cucianProses',
            'cucianSelesai',
            'totalPelanggan',
            'cucianTerbaru',
            'cucianMenungguKonfirmasi',
            'penjemputanMenunggu',
            'cucianHariIni',
            'pendapatanHariIni'
        ));
    }
}