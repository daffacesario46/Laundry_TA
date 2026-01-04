<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Penjemputan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KurirPenjemputanController extends Controller
{
    /**
     * Display list of penjemputan tasks
     */
    public function index(Request $request)
    {
        $kurir_id = Auth::id();
        
        $query = Penjemputan::with(['cucian.pelanggan'])
                            ->where('staff_id', $kurir_id);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show active tasks
            $query->whereIn('status', ['menunggu', 'diproses']);
        }
        
        $penjemputan = $query->orderBy('tgl_order', 'asc')->paginate(15);
        
        return view('kurir.penjemputan.index', compact('penjemputan'));
    }

    /**
     * Show detail penjemputan
     */
    public function show($id)
    {
        $kurir_id = Auth::id();
        
        $penjemputan = Penjemputan::with(['cucian.pelanggan', 'cucian.layanan'])
                                  ->where('staff_id', $kurir_id)
                                  ->findOrFail($id);
        
        return view('kurir.penjemputan.detail', compact('penjemputan'));
    }

    /**
     * Start penjemputan (update status to diproses)
     */
    public function start($id)
    {
        $kurir_id = Auth::id();
        
        DB::beginTransaction();
        try {
            $penjemputan = Penjemputan::where('staff_id', $kurir_id)
                                      ->where('status', 'menunggu')
                                      ->findOrFail($id);
            
            $penjemputan->update([
                'status' => 'diproses'
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Penjemputan dimulai! Selamat bekerja.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Start Penjemputan Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memulai penjemputan: ' . $e->getMessage());
        }
    }

    /**
     * Complete penjemputan (update status to selesai)
     */
    public function complete(Request $request, $id)
    {
        $kurir_id = Auth::id();
        
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string'
        ], [
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);
        
        DB::beginTransaction();
        try {
            $penjemputan = Penjemputan::with('cucian')
                                      ->where('staff_id', $kurir_id)
                                      ->where('status', 'diproses')
                                      ->findOrFail($id);
            
            $updateData = [
                'status' => 'selesai',
                'catatan' => $request->catatan
            ];
            
            // Upload foto bukti
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($penjemputan->foto && Storage::disk('public')->exists($penjemputan->foto)) {
                    Storage::disk('public')->delete($penjemputan->foto);
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('penjemputan', $filename, 'public');
                $updateData['foto'] = $path;
            }
            
            $penjemputan->update($updateData);
            
            // Auto-update status cucian to 'diproses'
            $penjemputan->cucian->update([
                'status_cucian' => 'diproses'
            ]);
            
            DB::commit();
            
            return redirect()->route('kurir.penjemputan.index')
                ->with('success', 'Penjemputan selesai! Cucian telah dijemput.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Complete Penjemputan Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan penjemputan: ' . $e->getMessage())
                ->withInput();
        }
    }
}