<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private static $user = null;
    
    public function __construct()
    {
        // Initialize dummy user data
        if (is_null(self::$user)) {
            self::$user = [
                'id' => 1,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'tanggal_lahir' => '1995-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'img' => null,
                'created_at' => '2024-01-01 10:00:00',
                'updated_at' => '2024-01-01 10:00:00'
            ];
        }
    }
    
    public function index()
    {
        $user = (object)self::$user;
        
        // Hitung statistik pelanggan
        $stats = [
            'total_order' => 15,
            'total_spending' => 525000,
            'member_since' => \Carbon\Carbon::parse(self::$user['created_at'])->diffForHumans()
        ];
        
        return view('pelanggan.profile.index', compact('user', 'stats'));
    }
    
    public function edit()
    {
        $user = (object)self::$user;
        return view('pelanggan.profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'no_telp.required' => 'No. telepon harus diisi',
            'alamat.required' => 'Alamat harus diisi'
        ]);
        
        // Update data (simulasi)
        self::$user['nama'] = $request->nama;
        self::$user['email'] = $request->email;
        self::$user['no_telp'] = $request->no_telp;
        self::$user['alamat'] = $request->alamat;
        self::$user['tanggal_lahir'] = $request->tanggal_lahir;
        self::$user['jenis_kelamin'] = $request->jenis_kelamin;
        self::$user['updated_at'] = now()->format('Y-m-d H:i:s');
        
        return redirect()->route('pelanggan.profile.index')
            ->with('success', 'Profile berhasil diupdate!');
    }
    
    public function changePassword()
    {
        return view('pelanggan.profile.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);
        
        // Simulasi update password
        return redirect()->route('pelanggan.profile.index')
            ->with('success', 'Password berhasil diubah!');
    }
}