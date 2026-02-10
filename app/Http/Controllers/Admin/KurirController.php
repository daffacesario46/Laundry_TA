<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
    /**
     * Display kurir index with modal
     */
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = User::where('role', 'kurir');
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $kurir = $query->orderBy('users_id', 'desc')->paginate($perPage);
        
        return view('admin.kurir.index', compact('kurir'));
    }

    /**
     * Store new kurir (dari modal)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telp' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        $kurir = User::create([
            'role' => 'kurir',
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'status' => 'aktif',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kurir', 'public');
            $kurir->foto = $path;
            $kurir->save();
        }

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan');
    }

    /**
     * Update kurir (dari modal)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',users_id',
            'password' => 'nullable|string|min:6|confirmed',
            'no_telp' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        $kurir = User::findOrFail($id);
        
        // Update data
        $kurir->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $kurir->password = Hash::make($request->password);
            $kurir->save();
        }

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($kurir->foto) {
                Storage::disk('public')->delete($kurir->foto);
            }
            
            $path = $request->file('foto')->store('kurir', 'public');
            $kurir->foto = $path;
            $kurir->save();
        }

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil diupdate');
    }

    /**
     * Delete kurir
     */
    public function destroy($id)
    {
        $kurir = User::findOrFail($id);
        
        // Hapus foto jika ada
        if ($kurir->foto) {
            Storage::disk('public')->delete($kurir->foto);
        }
        
        $kurir->delete();

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil dihapus');
    }

    /**
     * Toggle status kurir
     */
    public function toggleStatus($id)
    {
        $kurir = User::findOrFail($id);
        $kurir->status = $kurir->status == 'aktif' ? 'nonaktif' : 'aktif';
        $kurir->save();

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Status kurir berhasil diubah menjadi ' . ucfirst($kurir->status));
    }
}