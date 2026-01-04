<?php

namespace App\Http\Controllers;

use App\Models\Cucian;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Tampilkan halaman tracking (form input No Order)
     */
    public function index()
    {
        return view('tracking.index');
    }
    
    /**
     * Proses tracking berdasarkan No Order
     */
    public function track(Request $request)
    {
        $request->validate([
            'no_order' => 'required|string'
        ], [
            'no_order.required' => 'Nomor order wajib diisi!'
        ]);
        
        // Normalisasi input (hapus spasi, uppercase)
        $noOrder = strtoupper(trim($request->no_order));
        
        // Extract angka dari input (misalnya: WW001 atau 001 atau WW00001)
        preg_match('/(\d+)/', $noOrder, $matches);
        
        if (empty($matches)) {
            return redirect()->route('tracking.index')
                ->with('error', 'Format nomor order tidak valid! Contoh: WW001 atau 1');
        }
        
        $orderId = (int) $matches[1];
        
        // Cari cucian dengan eager loading
        $cucian = Cucian::with([
            'pelanggan',
            'layanan',
            'detail.listHarga',
            'pembayaran',
            'penjemputan.staff',
            'pengantaran.kurir'
        ])->find($orderId);
        
        // Jika tidak ditemukan
        if (!$cucian) {
            return redirect()->route('tracking.index')
                ->with('error', 'Nomor order tidak ditemukan! Pastikan nomor order sudah benar.');
        }
        
        // ✅ KIRIM MODEL CUCIAN LANGSUNG (bukan stdClass)
        return view('tracking.result', ['cucian' => $cucian]);
    }
    
    /**
     * API endpoint untuk real-time tracking (AJAX)
     */
    public function api($id)
    {
        $cucian = Cucian::with([
            'pelanggan',
            'layanan',
            'pembayaran',
            'penjemputan.staff',
            'pengantaran.kurir'
        ])->find($id);
        
        if (!$cucian) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'no_order' => $cucian->getNoOrder(),
                'status' => $cucian->status_cucian,
                'status_label' => $cucian->getStatusLabel(),
                'jenis_order' => $cucian->jenis_order,
                'jenis_ambil' => $cucian->jenis_ambil,
                'jenis_cucian' => $cucian->jenis_cucian,
                'layanan' => $cucian->layanan->nama_layanan ?? '-',
                'total_harga' => $cucian->total_harga,
                'total_berat' => $cucian->total_berat,
                'tgl_order' => $cucian->tgl_order->format('d M Y H:i'),
                'estimasi' => $cucian->estimasi ? $cucian->estimasi->format('d M Y') : '-',
                'tgl_selesai' => $cucian->tgl_selesai ? $cucian->tgl_selesai->format('d M Y H:i') : null,
                'tgl_diambil' => $cucian->tgl_diambil ? $cucian->tgl_diambil->format('d M Y H:i') : null,
                
                // Pelanggan
                'pelanggan' => [
                    'nama' => $cucian->pelanggan->nama ?? 'N/A',
                    'no_telp' => $cucian->pelanggan->no_telp ?? '-',
                    'alamat' => $cucian->pelanggan->alamat ?? '-'
                ],
                
                // Status Pembayaran
                'pembayaran' => [
                    'status' => $cucian->pembayaran->status_bayar ?? 'belum',
                    'metode' => $cucian->pembayaran->metode_bayar ?? '-',
                    'jumlah' => $cucian->pembayaran->jumlah_bayar ?? 0
                ],
                
                // Info Penjemputan
                'penjemputan' => $cucian->penjemputan ? [
                    'status' => $cucian->penjemputan->status,
                    'staff' => $cucian->penjemputan->staff->nama ?? '-',
                    'alamat' => $cucian->penjemputan->alamat_jemput,
                    'tgl_order' => $cucian->penjemputan->tgl_order ?? null
                ] : null,
                
                // Info Pengantaran
                'pengantaran' => $cucian->pengantaran ? [
                    'status' => $cucian->pengantaran->status,
                    'kurir' => $cucian->pengantaran->kurir->nama ?? '-',
                    'alamat' => $cucian->pengantaran->alamat_antar,
                    'tgl_berangkat' => $cucian->pengantaran->tgl_berangkat ?? null
                ] : null,
                
                // Timeline
                'timeline' => [
                    'diterima' => [
                        'status' => 'completed',
                        'tanggal' => $cucian->tgl_order->format('d M Y'),
                        'waktu' => $cucian->tgl_order->format('H:i')
                    ],
                    'dijemput' => $cucian->penjemputan && $cucian->penjemputan->status === 'selesai' ? [
                        'status' => 'completed',
                        'tanggal' => $cucian->penjemputan->updated_at->format('d M Y'),
                        'waktu' => $cucian->penjemputan->updated_at->format('H:i')
                    ] : [
                        'status' => $cucian->penjemputan ? 'active' : 'pending',
                        'tanggal' => '-',
                        'waktu' => '-'
                    ],
                    'diproses' => in_array($cucian->status_cucian, ['diproses', 'selesai', 'diambil']) ? [
                        'status' => 'completed',
                        'tanggal' => $cucian->updated_at->format('d M Y'),
                        'waktu' => $cucian->updated_at->format('H:i')
                    ] : [
                        'status' => 'pending',
                        'tanggal' => '-',
                        'waktu' => '-'
                    ],
                    'selesai' => in_array($cucian->status_cucian, ['selesai', 'diambil']) ? [
                        'status' => 'completed',
                        'tanggal' => $cucian->tgl_selesai ? $cucian->tgl_selesai->format('d M Y') : '-',
                        'waktu' => $cucian->tgl_selesai ? $cucian->tgl_selesai->format('H:i') : '-'
                    ] : [
                        'status' => 'pending',
                        'tanggal' => '-',
                        'waktu' => '-'
                    ],
                    'diantar' => $cucian->pengantaran && $cucian->pengantaran->status === 'diproses' ? [
                        'status' => 'active',
                        'tanggal' => $cucian->pengantaran->tgl_berangkat ? $cucian->pengantaran->tgl_berangkat->format('d M Y') : '-',
                        'waktu' => $cucian->pengantaran->tgl_berangkat ? $cucian->pengantaran->tgl_berangkat->format('H:i') : '-'
                    ] : ($cucian->pengantaran && $cucian->pengantaran->status === 'selesai' ? [
                        'status' => 'completed',
                        'tanggal' => $cucian->pengantaran->updated_at->format('d M Y'),
                        'waktu' => $cucian->pengantaran->updated_at->format('H:i')
                    ] : [
                        'status' => 'pending',
                        'tanggal' => '-',
                        'waktu' => '-'
                    ]),
                    'diambil' => $cucian->status_cucian === 'diambil' ? [
                        'status' => 'completed',
                        'tanggal' => $cucian->tgl_diambil ? $cucian->tgl_diambil->format('d M Y') : '-',
                        'waktu' => $cucian->tgl_diambil ? $cucian->tgl_diambil->format('H:i') : '-'
                    ] : [
                        'status' => 'pending',
                        'tanggal' => '-',
                        'waktu' => '-'
                    ]
                ]
            ]
        ]);
    }
}