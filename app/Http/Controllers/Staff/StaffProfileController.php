<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class StaffProfileController extends Controller
{
    /**
     * Display staff profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('staff.profile.index', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('staff.profile.edit', compact('user'));
    }

    /**
     * Update staff profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->users_id . ',users_id',
            'no_telp' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, jpg, atau png',
            'foto.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Upload foto baru
            $fotoPath = $request->file('foto')->store('staff_photos', 'public');
            $validated['foto'] = $fotoPath;
        }

        // Update user data
        $user->update($validated);

        return redirect()
            ->route('staff.profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('staff.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai!');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()
            ->route('staff.profile.index')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
            
            $user->update(['foto' => null]);

            return back()->with('success', 'Foto profile berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada foto untuk dihapus!');
    }
}