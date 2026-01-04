<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Cucian;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: bulan ini
        $filterType = $request->get('filter_type', 'bulan');
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $bulan = $request->get('bulan', now()->format('Y-m'));
        
        // Parse dates
        if ($filterType === 'hari') {
            $startDate = Carbon::parse($tanggal)->startOfDay();
            $endDate = Carbon::parse($tanggal)->endOfDay();
            $periodeText = $startDate->isoFormat('D MMMM YYYY');
        } else {
            $startDate = Carbon::parse($bulan . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulan . '-01')->endOfMonth();
            $periodeText = $startDate->isoFormat('MMMM YYYY');
        }
        
        // Query pembayaran lunas dalam periode
        $pembayaran = Pembayaran::with(['cucian.pelanggan', 'cucian.layanan'])
            ->where('status_bayar', 'lunas')
            ->whereBetween('tgl_bayar', [$startDate, $endDate])
            ->orderBy('tgl_bayar', 'desc')
            ->get();
        
        // Total Penghasilan
        $totalPenghasilan = $pembayaran->sum('jumlah_bayar');
        
        // Breakdown per Metode Pembayaran
        $breakdownMetode = $pembayaran->groupBy('metode_bayar')->map(function($items, $metode) {
            return [
                'metode' => $metode,
                'jumlah' => $items->count(),
                'total' => $items->sum('jumlah_bayar')
            ];
        })->values();
        
        // Breakdown per Layanan
        $breakdownLayanan = $pembayaran->groupBy('cucian.layanan_id')->map(function($items) {
            $layanan = $items->first()->cucian->layanan;
            return [
                'layanan' => $layanan ? $layanan->nama_layanan : 'Tanpa Layanan',
                'jumlah' => $items->count(),
                'total' => $items->sum('jumlah_bayar')
            ];
        })->values()->sortByDesc('total');
        
        // Breakdown per Jenis Order (Online/Offline)
        $breakdownJenisOrder = $pembayaran->groupBy('cucian.jenis_order')->map(function($items, $jenis) {
            return [
                'jenis' => ucfirst($jenis),
                'jumlah' => $items->count(),
                'total' => $items->sum('jumlah_bayar')
            ];
        })->values();
        
        // Data untuk Chart (per hari dalam periode)
        if ($filterType === 'bulan') {
            $chartData = $this->getChartDataPerHari($startDate, $endDate);
        } else {
            $chartData = null; // Tidak perlu chart untuk filter per hari
        }
        
        // Statistik Tambahan
        $stats = [
            'total_transaksi' => $pembayaran->count(),
            'rata_rata_transaksi' => $pembayaran->count() > 0 ? $totalPenghasilan / $pembayaran->count() : 0,
            'transaksi_cash' => $pembayaran->where('metode_bayar', 'cash')->count(),
            'transaksi_transfer' => $pembayaran->where('metode_bayar', 'transfer')->count(),
            'transaksi_midtrans' => $pembayaran->where('metode_bayar', 'midtrans')->count(),
        ];
        
        return view('admin.laporan.index', compact(
            'pembayaran',
            'totalPenghasilan',
            'breakdownMetode',
            'breakdownLayanan',
            'breakdownJenisOrder',
            'chartData',
            'stats',
            'filterType',
            'tanggal',
            'bulan',
            'periodeText',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Get chart data per hari
     */
    private function getChartDataPerHari($startDate, $endDate)
    {
        $dates = [];
        $totals = [];
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dayTotal = Pembayaran::where('status_bayar', 'lunas')
                ->whereDate('tgl_bayar', $currentDate->format('Y-m-d'))
                ->sum('jumlah_bayar');
            
            $dates[] = $currentDate->format('d M');
            $totals[] = $dayTotal;
            
            $currentDate->addDay();
        }
        
        return [
            'labels' => $dates,
            'data' => $totals
        ];
    }
    
    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $filterType = $request->get('filter_type', 'bulan');
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $bulan = $request->get('bulan', now()->format('Y-m'));
        
        if ($filterType === 'hari') {
            $startDate = Carbon::parse($tanggal)->startOfDay();
            $endDate = Carbon::parse($tanggal)->endOfDay();
            $periodeText = $startDate->isoFormat('D MMMM YYYY');
        } else {
            $startDate = Carbon::parse($bulan . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulan . '-01')->endOfMonth();
            $periodeText = $startDate->isoFormat('MMMM YYYY');
        }
        
        $pembayaran = Pembayaran::with(['cucian.pelanggan', 'cucian.layanan'])
            ->where('status_bayar', 'lunas')
            ->whereBetween('tgl_bayar', [$startDate, $endDate])
            ->orderBy('tgl_bayar', 'desc')
            ->get();
        
        $totalPenghasilan = $pembayaran->sum('jumlah_bayar');
        
        $breakdownLayanan = $pembayaran->groupBy('cucian.layanan_id')->map(function($items) {
            $layanan = $items->first()->cucian->layanan;
            return [
                'layanan' => $layanan ? $layanan->nama_layanan : 'Tanpa Layanan',
                'jumlah' => $items->count(),
                'total' => $items->sum('jumlah_bayar')
            ];
        })->values()->sortByDesc('total');
        
        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'pembayaran',
            'totalPenghasilan',
            'breakdownLayanan',
            'periodeText',
            'startDate',
            'endDate'
        ));
        
        $filename = 'laporan-keuangan-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $filterType = $request->get('filter_type', 'bulan');
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $bulan = $request->get('bulan', now()->format('Y-m'));
        
        if ($filterType === 'hari') {
            $startDate = Carbon::parse($tanggal)->startOfDay();
            $endDate = Carbon::parse($tanggal)->endOfDay();
        } else {
            $startDate = Carbon::parse($bulan . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulan . '-01')->endOfMonth();
        }
        
        $filename = 'laporan-keuangan-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanKeuanganExport($startDate, $endDate), $filename);
    }
}