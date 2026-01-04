<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik cucian berdasarkan status_cucian
            $totalCucian = Cucian::count();
            $cucianMenunggu = Cucian::where('status_cucian', 'menunggu')->count();
            $cucianProses = Cucian::where('status_cucian', 'diproses')->count();
            $cucianSelesai = Cucian::where('status_cucian', 'selesai')->count();
            
            // Total pelanggan aktif
            $totalPelanggan = Pelanggan::where('status', 'aktif')->count();
            
            // Cucian terbaru (10 terakhir) dengan relasi
            $cucianTerbaru = Cucian::with(['pelanggan:pelanggan_id,nama,no_telp', 'layanan:layanan_id,nama_layanan'])
                ->select('cucian_id', 'pelanggan_id', 'layanan_id', 'total_berat', 'total_harga', 'status_cucian', 'tgl_order', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Cucian yang perlu dikonfirmasi (status menunggu)
            $cucianMenungguKonfirmasi = Cucian::with(['pelanggan:pelanggan_id,nama,no_telp', 'layanan:layanan_id,nama_layanan'])
                ->select('cucian_id', 'pelanggan_id', 'layanan_id', 'total_berat', 'total_harga', 'status_cucian', 'tgl_order', 'created_at')
                ->where('status_cucian', 'menunggu')
                ->orderBy('tgl_order', 'desc')
                ->limit(5)
                ->get();
            
            // Statistik hari ini
            $today = Carbon::today();
            $cucianHariIni = Cucian::whereDate('tgl_order', $today)->count();
            
            // Pendapatan hari ini (hanya yang sudah selesai/diambil)
            $pendapatanHariIni = Cucian::whereDate('tgl_order', $today)
                ->whereIn('status_cucian', ['selesai', 'diambil'])
                ->sum('total_harga');
            
            // Grafik cucian per minggu (future use)
            $grafikCucian = [];
            
            return view('staff.dashboard', compact(
                'totalCucian',
                'cucianMenunggu',
                'cucianProses',
                'cucianSelesai',
                'totalPelanggan',
                'cucianTerbaru',
                'cucianMenungguKonfirmasi',
                'cucianHariIni',
                'pendapatanHariIni',
                'grafikCucian'
            ));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Staff Dashboard Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Return view dengan data kosong jika error
            return view('staff.dashboard', [
                'totalCucian' => 0,
                'cucianMenunggu' => 0,
                'cucianProses' => 0,
                'cucianSelesai' => 0,
                'totalPelanggan' => 0,
                'cucianTerbaru' => collect([]),
                'cucianMenungguKonfirmasi' => collect([]),
                'cucianHariIni' => 0,
                'pendapatanHariIni' => 0,
                'grafikCucian' => []
            ])->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }
}