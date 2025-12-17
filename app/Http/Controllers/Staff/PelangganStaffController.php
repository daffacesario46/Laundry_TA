<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = Pelanggan::with('user');
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%")
                  ->orWhere('no_wa', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_pelanggan')) {
            $query->where('kategori_pelanggan', $request->kategori_pelanggan);
        }
        
        $pelanggan = $query->orderBy('pelanggan_id', 'desc')->paginate($perPage);
        
        return view('staff.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('staff.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kategori_pelanggan' => 'required|in:member,reguler',
            'no_telp' => 'required|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Jika member, perlu email & password
            'email' => 'required_if:kategori_pelanggan,member|nullable|email|unique:users,email',
            'password' => 'required_if:kategori_pelanggan,member|nullable|string|min:6|confirmed',
        ]);
        
        DB::beginTransaction();
        try {
            $userId = null;
            
            // Jika kategori member, buat user account
            if ($request->kategori_pelanggan === 'member') {
                $user = User::create([
                    'role' => 'pelanggan',
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'no_telp' => $request->no_telp,
                    'no_wa' => $request->no_wa,
                    'alamat' => $request->alamat,
                    'status' => 'aktif'
                ]);
                
                $userId = $user->users_id;
            }
            
            // Buat pelanggan
            $pelanggan = Pelanggan::create([
                'users_id' => $userId,
                'kategori_pelanggan' => $request->kategori_pelanggan,
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'status' => 'aktif'
            ]);
            
            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('pelanggan', 'public');
                $pelanggan->foto = $path;
                $pelanggan->save();
                
                // Update foto di user juga jika member
                if ($userId) {
                    $user->foto = $path;
                    $user->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['user', 'cucian' => function($q) {
            $q->orderBy('tgl_order', 'desc')->take(10);
        }])->findOrFail($id);
        
        // Statistik pelanggan
        $totalOrder = $pelanggan->cucian()->count();
        $totalSpending = $pelanggan->cucian()->sum('total_harga');
        $orderSelesai = $pelanggan->cucian()->where('status_cucian', 'selesai')->count();
        
        return view('staff.pelanggan.detail', compact('pelanggan', 'totalOrder', 'totalSpending', 'orderSelesai'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);
        return view('staff.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Jika member dan ada user
            'email' => $pelanggan->users_id ? 'required|email|unique:users,email,' . $pelanggan->users_id . ',users_id' : 'nullable',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        
        DB::beginTransaction();
        try {
            // Update pelanggan
            $pelanggan->update([
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat
            ]);
            
            // Update user jika ada
            if ($pelanggan->users_id && $pelanggan->user) {
                $pelanggan->user->update([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_telp' => $request->no_telp,
                    'no_wa' => $request->no_wa,
                    'alamat' => $request->alamat
                ]);
                
                // Update password jika diisi
                if ($request->filled('password')) {
                    $pelanggan->user->password = Hash::make($request->password);
                    $pelanggan->user->save();
                }
            }
            
            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($pelanggan->foto) {
                    Storage::disk('public')->delete($pelanggan->foto);
                }
                
                $path = $request->file('foto')->store('pelanggan', 'public');
                $pelanggan->foto = $path;
                $pelanggan->save();
                
                // Update foto di user juga
                if ($pelanggan->user) {
                    $pelanggan->user->foto = $path;
                    $pelanggan->user->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal update pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Cek apakah pelanggan punya cucian
        if ($pelanggan->cucian()->count() > 0) {
            return redirect()->route('staff.pelanggan.index')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat cucian!');
        }
        
        DB::beginTransaction();
        try {
            // Hapus foto jika ada
            if ($pelanggan->foto) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            
            // Hapus user jika ada
            if ($pelanggan->users_id && $pelanggan->user) {
                $pelanggan->user->delete();
            }
            
            // Hapus pelanggan
            $pelanggan->delete();
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
    
    public function toggleStatus($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->status = $pelanggan->status == 'aktif' ? 'nonaktif' : 'aktif';
        $pelanggan->save();
        
        // Update status di user juga jika ada
        if ($pelanggan->user) {
            $pelanggan->user->status = $pelanggan->status;
            $pelanggan->user->save();
        }
        
        return redirect()->route('staff.pelanggan.index')
            ->with('success', 'Status pelanggan berhasil diubah!');
    }
}