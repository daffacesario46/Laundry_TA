<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganStaffController extends Controller
{
    /**
     * Display listing of pelanggan
     */
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
        if ($request->filled('kategori')) {
            $query->where('kategori_pelanggan', $request->kategori);
        }
        
        $pelanggan = $query->orderBy('created_at', 'desc')
                           ->paginate($perPage)
                           ->appends($request->except('page'));
        
        return view('staff.pelanggan.index', compact('pelanggan'));
    }

    /**
     * Show form for creating new pelanggan
     */
    public function create()
    {
        return view('staff.pelanggan.create');
    }

    /**
     * Store new pelanggan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'kategori_pelanggan' => 'required|in:umum,member',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'kategori_pelanggan.required' => 'Kategori pelanggan harus dipilih',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);
        
        DB::beginTransaction();
        try {
            $userData = null;
            
            // Buat user jika email & password diisi
            if ($request->filled('email') && $request->filled('password')) {
                $userData = [
                    'role' => 'pelanggan',
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => 'aktif',
                ];
                
                $user = User::create($userData);
                $usersId = $user->users_id;
            } else {
                $usersId = null;
            }
            
            // Buat pelanggan
            $pelangganData = [
                'users_id' => $usersId,
                'kategori_pelanggan' => $request->kategori_pelanggan,
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'status' => 'aktif',
            ];
            
            // ✅ IMPROVED: Handle foto dengan nama file yang lebih baik
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('pelanggan', $filename, 'public');
                $pelangganData['foto'] = $path;
            }
            
            $pelanggan = Pelanggan::create($pelangganData);
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Pelanggan Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);
        
        return view('staff.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update pelanggan
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'kategori_pelanggan' => 'required|in:umum,member',
            'status' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|unique:users,email,' . ($pelanggan->users_id ?? 'NULL') . ',users_id',
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            // Update pelanggan data
            $pelangganData = [
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'kategori_pelanggan' => $request->kategori_pelanggan,
                'status' => $request->status,
            ];
            
            // ✅ IMPROVED: Handle foto dengan nama file yang lebih baik
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                    Storage::disk('public')->delete($pelanggan->foto);
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('pelanggan', $filename, 'public');
                $pelangganData['foto'] = $path;
            }
            
            $pelanggan->update($pelangganData);
            
            // Update user jika ada
            if ($pelanggan->user) {
                $userData = [
                    'nama' => $request->nama,
                    'status' => $request->status,
                ];
                
                if ($request->filled('email')) {
                    $userData['email'] = $request->email;
                }
                
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                
                $pelanggan->user->update($userData);
            }
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Pelanggan Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ✅ NEW: Delete foto pelanggan
     */
    public function deleteFoto($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        if (!$pelanggan->foto) {
            return redirect()->back()
                ->with('info', 'Pelanggan tidak memiliki foto');
        }
        
        DB::beginTransaction();
        try {
            // Hapus file foto dari storage
            if (Storage::disk('public')->exists($pelanggan->foto)) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            
            // Update database
            $pelanggan->update(['foto' => null]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Foto pelanggan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Delete Foto Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete pelanggan
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Cek apakah pelanggan punya cucian
        if ($pelanggan->cucian()->exists()) {
            return redirect()->route('staff.pelanggan.index')
                ->with('error', 'Pelanggan tidak bisa dihapus karena masih memiliki data cucian!');
        }
        
        DB::beginTransaction();
        try {
            // Hapus foto
            if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            
            // Hapus user jika ada
            if ($pelanggan->user) {
                $pelanggan->user->delete();
            }
            
            $pelanggan->delete();
            
            DB::commit();
            
            return redirect()->route('staff.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Pelanggan Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
}