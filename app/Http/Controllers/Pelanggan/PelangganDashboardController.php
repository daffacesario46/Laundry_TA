<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        try {
            // Development Mode: Jika belum ada auth, ambil pelanggan pertama
            // Production Mode: Menggunakan Auth::user()
            
            $pelanggan = null;
            
            // Coba ambil dari Auth
            if (Auth::check()) {
                $user = Auth::user();
                $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
            } else {
                // Development mode: ambil pelanggan pertama yang punya user
                $pelanggan = Pelanggan::whereNotNull('users_id')->first();
                
                // Jika tidak ada, ambil pelanggan pertama
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::first();
                }
            }
            
            // Jika tidak ada pelanggan sama sekali
            if (!$pelanggan) {
                return view('pelanggan.dashboard', [
                    'stats' => [
                        'total_order' => 0,
                        'sedang_proses' => 0,
                        'selesai' => 0,
                        'menunggu_diambil' => 0,
                        'total_spending' => 0
                    ],
                    'recentOrders' => collect([]),
                    'pelanggan' => null,
                    'needsAttention' => collect([])
                ])->with('info', 'Belum ada data pelanggan');
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
                ->whereIn('status_cucian', ['selesai', 'diambil'])
                ->sum('total_harga');
            
            // Order terbaru (5 terakhir)
            $recentOrders = Cucian::with(['layanan', 'pembayaran'])
                ->where('pelanggan_id', $pelanggan->pelanggan_id)
                ->orderBy('tgl_order', 'desc')
                ->take(5)
                ->get();
            
            // Order yang perlu perhatian (menunggu pembayaran atau siap diambil)
            $needsAttention = Cucian::with('pembayaran')
                ->where('pelanggan_id', $pelanggan->pelanggan_id)
                ->where(function($q) {
                    $q->where('status_cucian', 'selesai')
                      ->orWhereHas('pembayaran', function($q2) {
                          $q2->where('status_bayar', 'belum');
                      });
                })
                ->take(3)
                ->get();
            
            $stats = [
                'total_order' => $totalOrder,
                'sedang_proses' => $sedangProses,
                'selesai' => $selesai,
                'menunggu_diambil' => $menungguDiambil,
                'total_spending' => $totalSpending
            ];
            
            return view('pelanggan.dashboard', compact('stats', 'recentOrders', 'pelanggan', 'needsAttention'));
            
        } catch (\Exception $e) {
            \Log::error('Pelanggan Dashboard Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return view('pelanggan.dashboard', [
                'stats' => [
                    'total_order' => 0,
                    'sedang_proses' => 0,
                    'selesai' => 0,
                    'menunggu_diambil' => 0,
                    'total_spending' => 0
                ],
                'recentOrders' => collect([]),
                'pelanggan' => null,
                'needsAttention' => collect([])
            ])->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }
}