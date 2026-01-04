<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Penjemputan;
use App\Models\Pengantaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KurirDashboardController extends Controller
{
    public function index()
    {
        $kurir_id = Auth::id();
        
        // Statistik Penjemputan
        $penjemputanMenunggu = Penjemputan::where('staff_id', $kurir_id)
                                          ->where('status', 'menunggu')
                                          ->count();
        
        $penjemputanDiproses = Penjemputan::where('staff_id', $kurir_id)
                                          ->where('status', 'diproses')
                                          ->count();
        
        $penjemputanSelesai = Penjemputan::where('staff_id', $kurir_id)
                                         ->where('status', 'selesai')
                                         ->count();
        
        // Statistik Pengantaran
        $pengantaranMenunggu = Pengantaran::where('kurir_id', $kurir_id)
                                          ->where('status', 'menunggu')
                                          ->count();
        
        $pengantaranDiproses = Pengantaran::where('kurir_id', $kurir_id)
                                          ->where('status', 'diproses')
                                          ->count();
        
        $pengantaranSelesai = Pengantaran::where('kurir_id', $kurir_id)
                                         ->where('status', 'selesai')
                                         ->count();
        
        // Tugas Aktif (pending & in progress)
        $tugasJemputAktif = Penjemputan::with(['cucian.pelanggan'])
                                       ->where('staff_id', $kurir_id)
                                       ->whereIn('status', ['menunggu', 'diproses'])
                                       ->orderBy('tgl_order', 'asc')
                                       ->take(5)
                                       ->get();
        
        $tugasAntarAktif = Pengantaran::with(['cucian.pelanggan'])
                                      ->where('kurir_id', $kurir_id)
                                      ->whereIn('status', ['menunggu', 'diproses'])
                                      ->orderBy('created_at', 'asc')
                                      ->take(5)
                                      ->get();
        
        return view('kurir.dashboard', compact(
            'penjemputanMenunggu',
            'penjemputanDiproses',
            'penjemputanSelesai',
            'pengantaranMenunggu',
            'pengantaranDiproses',
            'pengantaranSelesai',
            'tugasJemputAktif',
            'tugasAntarAktif'
        ));
    }
}