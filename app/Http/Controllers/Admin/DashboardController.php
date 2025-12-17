<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        // Query cucian yang sudah selesai
        $query = Cucian::with(['pelanggan', 'layanan', 'detail.listHarga'])
            ->where('status_cucian', 'selesai')
            ->orWhere('status_cucian', 'diambil');
        
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
        
        // Filter berdasarkan jenis order
        if ($request->filled('jenis_order')) {
            $query->where('jenis_order', $request->jenis_order);
        }
        
        // Filter berdasarkan jenis ambil
        if ($request->filled('jenis_ambil')) {
            $query->where('jenis_ambil', $request->jenis_ambil);
        }
        
        // Order by tgl_selesai descending (terbaru dulu)
        $cucian = $query->orderBy('tgl_selesai', 'desc')
                       ->orderBy('cucian_id', 'desc')
                       ->paginate($perPage);
        
        $jenis_order = $request->query('jenis_order', 'Selesai');
        
        return view('admin.dashboard.index', compact('cucian', 'jenis_order'));
    }
    
    public function detail($cucian_id)
    {
        $cucian = Cucian::with([
            'pelanggan.user',
            'layanan',
            'detail.listHarga',
            'pembayaran'
        ])->findOrFail($cucian_id);
        
        return view('admin.dashboard.detail', compact('cucian'));
    }
}