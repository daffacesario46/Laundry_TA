<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Penjemputan;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjemputanController extends Controller
{
    /**
     * Display listing of penjemputan
     */
    public function index(Request $request)
    {
        $query = Penjemputan::with(['cucian.pelanggan', 'staff']);

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

        $penjemputan = $query->orderBy('created_at', 'desc')
                             ->paginate(15)
                             ->appends($request->all());

        return view('staff.penjemputan.index', compact('penjemputan'));
    }

    /**
     * Show assign kurir form
     */
    public function assignForm($cucian_id)
    {
        $cucian = Cucian::with(['pelanggan', 'penjemputan.staff'])->findOrFail($cucian_id);

        // Check if cucian is online order
        if ($cucian->jenis_order !== 'online') {
            return redirect()->back()
                ->with('error', 'Penjemputan hanya untuk order online!');
        }

        // Get available staff (role: kurir or staff)
        $kurirs = User::whereIn('role', ['kurir', 'staff'])
                     ->where('status', 'aktif')
                     ->orderBy('nama')
                     ->get();

        return view('staff.penjemputan.assign', compact('cucian', 'kurirs'));
    }
    
    /**
     * Show edit penjemputan form (alternative route)
     */
    public function edit($id)
    {
        $penjemputan = Penjemputan::with(['cucian.pelanggan', 'staff'])->findOrFail($id);
        
        $kurirs = User::whereIn('role', ['kurir', 'staff'])
                     ->where('status', 'aktif')
                     ->orderBy('nama')
                     ->get();
        
        // Reuse assign view
        $cucian = $penjemputan->cucian;
        
        return view('staff.penjemputan.assign', compact('cucian', 'kurirs'));
    }

    /**
     * Assign kurir to penjemputan
     */
    public function assign(Request $request, $cucian_id)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,users_id',
            'alamat_jemput' => 'required|string',
            'catatan' => 'nullable|string'
        ], [
            'staff_id.required' => 'Kurir harus dipilih',
            'alamat_jemput.required' => 'Alamat jemput harus diisi'
        ]);

        DB::beginTransaction();
        try {
            $cucian = Cucian::findOrFail($cucian_id);

            // Check if penjemputan already exists
            if ($cucian->penjemputan()->exists()) {
                $penjemputan = $cucian->penjemputan;
                $penjemputan->update([
                    'staff_id' => $request->staff_id,
                    'alamat_jemput' => $request->alamat_jemput,
                    'status' => 'diproses',
                    'catatan' => $request->catatan
                ]);

                $message = 'Kurir penjemputan berhasil diupdate!';
            } else {
                // Create new penjemputan
                $penjemputan = Penjemputan::create([
                    'cucian_id' => $cucian_id,
                    'staff_id' => $request->staff_id,
                    'alamat_jemput' => $request->alamat_jemput,
                    'status' => 'diproses',
                    'tgl_order' => now(),
                    'catatan' => $request->catatan
                ]);

                $message = 'Kurir penjemputan berhasil ditugaskan!';
            }

            DB::commit();

            return redirect()->route('staff.penjemputan.show', $penjemputan->penjemputan_id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assign Penjemputan Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menugaskan kurir: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show penjemputan detail
     */
    public function show($id)
    {
        $penjemputan = Penjemputan::with(['cucian.pelanggan', 'cucian.layanan', 'staff'])
            ->findOrFail($id);

        return view('staff.penjemputan.detail', compact('penjemputan'));
    }

    /**
     * Update penjemputan status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $penjemputan = Penjemputan::findOrFail($id);

            $updateData = [
                'status' => $request->status,
                'catatan' => $request->catatan
            ];

            $penjemputan->update($updateData);

            // If status selesai, update cucian status to diproses
            if ($request->status === 'selesai') {
                $penjemputan->cucian->update([
                    'status_cucian' => 'diproses'
                ]);
            }

            DB::commit();

            return redirect()->route('staff.penjemputan.show', $id)
                ->with('success', 'Status penjemputan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Penjemputan Status Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Upload foto bukti penjemputan
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
            $penjemputan = Penjemputan::findOrFail($id);

            // Delete old foto if exists
            if ($penjemputan->foto && \Storage::disk('public')->exists($penjemputan->foto)) {
                \Storage::disk('public')->delete($penjemputan->foto);
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('penjemputan', 'public');

            $penjemputan->update([
                'foto' => $fotoPath,
                'status' => 'selesai'
            ]);

            // Update cucian status
            $penjemputan->cucian->update([
                'status_cucian' => 'diproses'
            ]);

            DB::commit();

            return redirect()->route('staff.penjemputan.show', $id)
                ->with('success', 'Foto bukti penjemputan berhasil diupload!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Upload Foto Penjemputan Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete penjemputan
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjemputan = Penjemputan::findOrFail($id);
            $cucian_id = $penjemputan->cucian_id;

            // Delete foto if exists
            if ($penjemputan->foto && \Storage::disk('public')->exists($penjemputan->foto)) {
                \Storage::disk('public')->delete($penjemputan->foto);
            }

            $penjemputan->delete();

            DB::commit();

            return redirect()->route('staff.cucian.show', $cucian_id)
                ->with('success', 'Data penjemputan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete Penjemputan Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}