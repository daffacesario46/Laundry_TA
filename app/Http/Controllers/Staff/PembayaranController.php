<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cucian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of pembayaran
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['cucian.pelanggan']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status_bayar', $request->status);
        }

        // Filter by metode
        if ($request->has('metode') && $request->metode !== 'all') {
            $query->where('metode_bayar', $request->metode);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('cucian.pelanggan', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $pembayaran = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('staff.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show detail pembayaran
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['cucian.pelanggan', 'cucian.detail.listHarga'])
            ->findOrFail($id);

        return view('staff.pembayaran.detail', compact('pembayaran'));
    }

    /**
     * Show payment form for offline customer
     */
    public function showPaymentForm($cucian_id)
    {
        $cucian = Cucian::with(['pelanggan', 'pembayaran', 'detail.listHarga'])
            ->findOrFail($cucian_id);

        // Check if already paid
        if ($cucian->isPaid()) {
            return redirect()->route('staff.pembayaran.show', $cucian->pembayaran->pembayaran_id)
                ->with('info', 'Cucian ini sudah dibayar lunas');
        }

        return view('staff.pembayaran.form', compact('cucian'));
    }

    /**
     * Process payment for offline customer
     */
    public function processPayment(Request $request, $cucian_id)
    {
        $request->validate([
            'metode_bayar' => 'required|in:cash,transfer',
            'jumlah_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ], [
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
            'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka',
            'jumlah_bayar.min' => 'Jumlah bayar minimal 0'
        ]);

        DB::beginTransaction();
        try {
            $cucian = Cucian::findOrFail($cucian_id);

            // Check if payment already exists
            if ($cucian->hasPembayaran()) {
                $pembayaran = $cucian->pembayaran;
                
                // Update existing payment
                $pembayaran->update([
                    'metode_bayar' => $request->metode_bayar,
                    'status_bayar' => 'lunas',
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'tgl_bayar' => now(),
                    'catatan' => $request->catatan
                ]);
            } else {
                // Create new payment
                $pembayaran = Pembayaran::create([
                    'cucian_id' => $cucian_id,
                    'metode_bayar' => $request->metode_bayar,
                    'status_bayar' => 'lunas',
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'tgl_bayar' => now(),
                    'catatan' => $request->catatan
                ]);
            }

            DB::commit();

            return redirect()->route('staff.pembayaran.show', $pembayaran->pembayaran_id)
                ->with('success', 'Pembayaran berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show form to validate transfer payment (online)
     */
    public function showValidateForm($id)
    {
        $pembayaran = Pembayaran::with(['cucian.pelanggan'])
            ->findOrFail($id);

        // Only for transfer and belum lunas
        if ($pembayaran->metode_bayar !== 'transfer' || $pembayaran->status_bayar === 'lunas') {
            return redirect()->route('staff.pembayaran.show', $id)
                ->with('info', 'Pembayaran ini tidak perlu divalidasi');
        }

        return view('staff.pembayaran.validate', compact('pembayaran'));
    }

    /**
     * Validate transfer payment (approve/reject)
     */
    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);

            if ($request->action === 'approve') {
                $pembayaran->update([
                    'status_bayar' => 'lunas',
                    'tgl_bayar' => now(),
                    'catatan' => $request->catatan ?? 'Pembayaran transfer divalidasi'
                ]);

                $message = 'Pembayaran berhasil divalidasi dan disetujui!';
            } else {
                $pembayaran->update([
                    'status_bayar' => 'belum',
                    'bukti_bayar' => null,
                    'catatan' => $request->catatan ?? 'Bukti pembayaran ditolak'
                ]);

                $message = 'Bukti pembayaran ditolak. Pelanggan perlu upload ulang.';
            }

            DB::commit();

            return redirect()->route('staff.pembayaran.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete pembayaran (cancel)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $cucian_id = $pembayaran->cucian_id;
            
            $pembayaran->delete();

            DB::commit();

            return redirect()->route('staff.cucian.show', $cucian_id)
                ->with('success', 'Pembayaran berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}