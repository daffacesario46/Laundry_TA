<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Pengantaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KurirPengantaranController extends Controller
{
    /**
     * Display list of pengantaran tasks
     */
    public function index(Request $request)
    {
        $kurir_id = Auth::id();
        
        $query = Pengantaran::with(['cucian.pelanggan'])
                            ->where('kurir_id', $kurir_id);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show active tasks
            $query->whereIn('status', ['menunggu', 'diproses']);
        }
        
        $pengantaran = $query->orderBy('created_at', 'asc')->paginate(15);
        
        return view('kurir.pengantaran.index', compact('pengantaran'));
    }

    /**
     * Show detail pengantaran
     */
    public function show($id)
    {
        $kurir_id = Auth::id();
        
        $pengantaran = Pengantaran::with(['cucian.pelanggan', 'cucian.layanan'])
                                  ->where('kurir_id', $kurir_id)
                                  ->findOrFail($id);
        
        return view('kurir.pengantaran.detail', compact('pengantaran'));
    }

    /**
     * Start pengantaran (update status to diproses)
     */
    public function start($id)
    {
        $kurir_id = Auth::id();
        
        DB::beginTransaction();
        try {
            $pengantaran = Pengantaran::where('kurir_id', $kurir_id)
                                      ->where('status', 'menunggu')
                                      ->findOrFail($id);
            
            $pengantaran->update([
                'status' => 'diproses',
                'tgl_berangkat' => Carbon::now()
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Pengantaran dimulai! Selamat bekerja.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Start Pengantaran Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memulai pengantaran: ' . $e->getMessage());
        }
    }

    /**
     * Complete pengantaran (update status to selesai)
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
            $pengantaran = Pengantaran::with('cucian')
                                      ->where('kurir_id', $kurir_id)
                                      ->where('status', 'diproses')
                                      ->findOrFail($id);
            
            $updateData = [
                'status' => 'selesai',
                'catatan' => $request->catatan
            ];
            
            // Upload foto bukti
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($pengantaran->foto && Storage::disk('public')->exists($pengantaran->foto)) {
                    Storage::disk('public')->delete($pengantaran->foto);
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('pengantaran', $filename, 'public');
                $updateData['foto'] = $path;
            }
            
            $pengantaran->update($updateData);
            
            // Auto-update status cucian to 'diambil'
            $pengantaran->cucian->update([
                'status_cucian' => 'diambil',
                'tgl_diambil' => Carbon::now()
            ]);
            
            DB::commit();
            
            return redirect()->route('kurir.pengantaran.index')
                ->with('success', 'Pengantaran selesai! Cucian telah diterima pelanggan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Complete Pengantaran Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan pengantaran: ' . $e->getMessage())
                ->withInput();
        }
    }
}