<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Cucian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        if (!$pelanggan) {
            return redirect()->route('pelanggan.profile.edit')
                ->with('info', 'Lengkapi data profile Anda terlebih dahulu');
        }
        
        // Statistik pelanggan
        $totalOrder = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)->count();
        $totalSpending = Cucian::where('pelanggan_id', $pelanggan->pelanggan_id)
            ->whereIn('status_cucian', ['selesai', 'diambil'])
            ->sum('total_harga');
        $memberSince = $user->created_at->format('d M Y');
        
        $stats = [
            'total_order' => $totalOrder,
            'total_spending' => $totalSpending,
            'member_since' => $memberSince
        ];
        
        return view('pelanggan.profile.index', compact('user', 'pelanggan', 'stats'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        return view('pelanggan.profile.edit', compact('user', 'pelanggan'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('users_id', $user->users_id)->first();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->users_id . ',users_id',
            'no_telp' => 'required|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'no_telp.required' => 'No. telepon harus diisi',
            'alamat.required' => 'Alamat harus diisi'
        ]);
        
        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];
            
            $user->update($userData);
            
            // Update atau buat pelanggan
            $pelangganData = [
                'nama' => $request->nama,
                'no_telp' => $request->no_telp,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat
            ];
            
            if ($pelanggan) {
                $pelanggan->update($pelangganData);
            } else {
                // Buat pelanggan baru jika belum ada
                $pelangganData['users_id'] = $user->users_id;
                $pelangganData['kategori_pelanggan'] = 'umum';
                $pelangganData['status'] = 'aktif';
                $pelanggan = Pelanggan::create($pelangganData);
            }
            
            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($pelanggan->foto && Storage::disk('public')->exists($pelanggan->foto)) {
                    Storage::disk('public')->delete($pelanggan->foto);
                }
                
                $path = $request->file('foto')->store('pelanggan', 'public');
                $pelanggan->foto = $path;
                $pelanggan->save();
            }
            
            DB::commit();
            
            return redirect()->route('pelanggan.profile.index')
                ->with('success', 'Profile berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Profile Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal update profile: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function changePassword()
    {
        return view('pelanggan.profile.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);
        
        $user = Auth::user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password saat ini tidak sesuai!');
        }
        
        try {
            // Update password baru
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            return redirect()->route('pelanggan.profile.index')
                ->with('success', 'Password berhasil diubah!');
                
        } catch (\Exception $e) {
            \Log::error('Password Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}