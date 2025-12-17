<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = User::where('role', 'staff');
        
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
        
        $staff = $query->orderBy('users_id', 'desc')->paginate($perPage);
        
        return view('admin.staff.index', compact('staff'));
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

        $staff = User::create([
            'role' => 'staff',
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'status' => 'aktif',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('staff', 'public');
            $staff->foto = $path;
            $staff->save();
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil ditambahkan');
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

        $staff = User::findOrFail($id);
        
        $staff->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
        ]);

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
            $staff->save();
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($staff->foto) {
                Storage::disk('public')->delete($staff->foto);
            }
            $path = $request->file('foto')->store('staff', 'public');
            $staff->foto = $path;
            $staff->save();
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil diupdate');
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        
        // Hapus foto jika ada
        if ($staff->foto) {
            Storage::disk('public')->delete($staff->foto);
        }
        
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $staff = User::findOrFail($id);
        $staff->status = $staff->status == 'aktif' ? 'nonaktif' : 'aktif';
        $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Status staff berhasil diubah');
    }
}