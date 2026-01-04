<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
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

    // ✅ ADDED - Show create form
    public function create()
    {
        return view('admin.kurir.create');
    }

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

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kurir', 'public');
            $kurir->foto = $path;
            $kurir->save();
        }

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan');
    }

    // ✅ ADDED - Show edit form
    public function edit($id)
    {
        $kurir = User::where('role', 'kurir')->findOrFail($id);
        return view('admin.kurir.edit', compact('kurir'));
    }

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
        ]);

        $kurir = User::findOrFail($id);
        
        $kurir->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        if ($request->filled('password')) {
            $kurir->password = Hash::make($request->password);
            $kurir->save();
        }

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

    public function toggleStatus($id)
    {
        $kurir = User::findOrFail($id);
        $kurir->status = $kurir->status == 'aktif' ? 'nonaktif' : 'aktif';
        $kurir->save();

        return redirect()->route('admin.kurir.index', ['action' => 'toggle'])
            ->with('success', 'Status kurir berhasil diubah');
    }
}