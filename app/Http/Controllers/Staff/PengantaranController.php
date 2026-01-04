<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pengantaran;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengantaranController extends Controller
{
    /**
     * Display listing of pengantaran
     */
    public function index(Request $request)
    {
        $query = Pengantaran::with(['cucian.pelanggan', 'kurir']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('cucian.pelanggan', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('cucian', function($q) use ($search) {
                $q->where('cucian_id', 'like', "%{$search}%");
            });
        }

        $pengantaran = $query->orderBy('created_at', 'desc')
                             ->paginate(15)
                             ->appends($request->all());

        return view('staff.pengantaran.index', compact('pengantaran'));
    }

    /**
     * Show assign kurir form
     */
    public function assignForm($cucian_id)
    {
        $cucian = Cucian::with(['pelanggan', 'pengantaran.kurir'])->findOrFail($cucian_id);

        // Check if cucian status is selesai (ready for delivery)
        if ($cucian->status_cucian !== 'selesai') {
            return redirect()->back()
                ->with('error', 'Cucian harus berstatus "Selesai" untuk bisa diantar!');
        }

        // Check if jenis_ambil is diantar
        if ($cucian->jenis_ambil !== 'diantar') {
            return redirect()->back()
                ->with('error', 'Cucian ini tidak perlu diantar (jenis ambil: ambil sendiri)!');
        }

        // Get available kurirs
        $kurirs = User::whereIn('role', ['kurir', 'staff'])
                     ->where('status', 'aktif')
                     ->orderBy('nama')
                     ->get();

        return view('staff.pengantaran.assign', compact('cucian', 'kurirs'));
    }
    
    /**
     * Show edit pengantaran form (alternative route)
     */
    public function edit($id)
    {
        $pengantaran = Pengantaran::with(['cucian.pelanggan', 'kurir'])->findOrFail($id);
        
        $kurirs = User::whereIn('role', ['kurir', 'staff'])
                     ->where('status', 'aktif')
                     ->orderBy('nama')
                     ->get();
        
        // Reuse assign view
        $cucian = $pengantaran->cucian;
        
        return view('staff.pengantaran.assign', compact('cucian', 'kurirs'));
    }

    /**
     * Assign kurir to pengantaran
     */
    public function assign(Request $request, $cucian_id)
    {
        $validated = $request->validate([
            'kurir_id' => 'required|exists:users,users_id',
            'alamat_antar' => 'required|string',
            'catatan' => 'nullable|string'
        ], [
            'kurir_id.required' => 'Kurir harus dipilih',
            'alamat_antar.required' => 'Alamat antar harus diisi'
        ]);

        DB::beginTransaction();
        try {
            $cucian = Cucian::findOrFail($cucian_id);

            // Check if pengantaran already exists
            if ($cucian->pengantaran()->exists()) {
                $pengantaran = $cucian->pengantaran;
                $pengantaran->update([
                    'kurir_id' => $request->kurir_id,
                    'alamat_antar' => $request->alamat_antar,
                    'status' => 'diproses',
                    'tgl_berangkat' => now(),
                    'catatan' => $request->catatan
                ]);

                $message = 'Kurir pengantaran berhasil diupdate!';
            } else {
                // Create new pengantaran
                $pengantaran = Pengantaran::create([
                    'cucian_id' => $cucian_id,
                    'kurir_id' => $request->kurir_id,
                    'alamat_antar' => $request->alamat_antar,
                    'status' => 'diproses',
                    'tgl_berangkat' => now(),
                    'catatan' => $request->catatan
                ]);

                $message = 'Kurir pengantaran berhasil ditugaskan!';
            }

            DB::commit();

            return redirect()->route('staff.pengantaran.show', $pengantaran->pengantaran_id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assign Pengantaran Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menugaskan kurir: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show pengantaran detail
     */
    public function show($id)
    {
        $pengantaran = Pengantaran::with(['cucian.pelanggan', 'cucian.layanan', 'kurir'])
            ->findOrFail($id);

        return view('staff.pengantaran.detail', compact('pengantaran'));
    }

    /**
     * Update pengantaran status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pengantaran = Pengantaran::findOrFail($id);

            $updateData = [
                'status' => $request->status,
                'catatan' => $request->catatan
            ];

            // Set tgl_berangkat when status changes to diproses
            if ($request->status === 'diproses' && !$pengantaran->tgl_berangkat) {
                $updateData['tgl_berangkat'] = now();
            }

            $pengantaran->update($updateData);

            // If status selesai, update cucian status to diambil
            if ($request->status === 'selesai') {
                $pengantaran->cucian->update([
                    'status_cucian' => 'diambil',
                    'tgl_diambil' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('staff.pengantaran.show', $id)
                ->with('success', 'Status pengantaran berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Pengantaran Status Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Upload foto bukti pengantaran
     */
    public function uploadFoto(Request $request, $id)
    {
        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'foto.required' => 'Foto bukti harus diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        DB::beginTransaction();
        try {
            $pengantaran = Pengantaran::findOrFail($id);

            // Delete old foto if exists
            if ($pengantaran->foto && \Storage::disk('public')->exists($pengantaran->foto)) {
                \Storage::disk('public')->delete($pengantaran->foto);
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('pengantaran', 'public');

            $pengantaran->update([
                'foto' => $fotoPath,
                'status' => 'selesai'
            ]);

            // Update cucian status to diambil
            $pengantaran->cucian->update([
                'status_cucian' => 'diambil',
                'tgl_diambil' => now()
            ]);

            DB::commit();

            return redirect()->route('staff.pengantaran.show', $id)
                ->with('success', 'Foto bukti pengantaran berhasil diupload! Cucian sudah terkirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Upload Foto Pengantaran Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete pengantaran
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pengantaran = Pengantaran::findOrFail($id);
            $cucian_id = $pengantaran->cucian_id;

            // Delete foto if exists
            if ($pengantaran->foto && \Storage::disk('public')->exists($pengantaran->foto)) {
                \Storage::disk('public')->delete($pengantaran->foto);
            }

            $pengantaran->delete();

            DB::commit();

            return redirect()->route('staff.cucian.show', $cucian_id)
                ->with('success', 'Data pengantaran berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete Pengantaran Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}