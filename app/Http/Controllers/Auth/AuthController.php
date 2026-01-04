<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }
        
        return view('auth.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Cek apakah user aktif
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar!')->withInput();
        }
        
        if ($user->status !== 'aktif') {
            return back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin!')->withInput();
        }

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Log activity
            \Log::info('User logged in', [
                'user_id' => $user->users_id,
                'email' => $user->email,
                'role' => $user->role
            ]);
            
            // Redirect based on role
            return $this->redirectToDashboard($user->role);
        }

        return back()->with('error', 'Email atau password salah!')->withInput();
    }

    /**
     * Show register form (only for pelanggan)
     */
    public function showRegister()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }
        
        return view('auth.register');
    }

    /**
     * Process register (only for pelanggan)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'alamat' => 'required|string',
            'kode_telp' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'kode_wa' => 'required|string',
            'no_wa' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'alamat.required' => 'Alamat harus diisi',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_wa.required' => 'Nomor WhatsApp harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Gabungkan kode negara dengan nomor
            $noTelp = $request->kode_telp . $request->no_telp;
            $noWa = $request->kode_wa . $request->no_wa;
            
            // Buat user
            $user = User::create([
                'role' => 'pelanggan',
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'aktif',
            ]);

            // Buat data pelanggan
            Pelanggan::create([
                'users_id' => $user->users_id,
                'kategori_pelanggan' => 'member', // Member karena registrasi online
                'nama' => $request->nama,
                'no_telp' => $noTelp,
                'no_wa' => $noWa,
                'alamat' => $request->alamat,
                'status' => 'aktif',
            ]);

            DB::commit();

            // Auto login after register
            Auth::login($user);

            return redirect()->route('pelanggan.dashboard')
                ->with('success', 'Pendaftaran berhasil! Selamat datang di Washwes Laundry.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Registration Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()
                ->with('error', 'Gagal mendaftar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $role = Auth::user()->role ?? null;
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Anda telah logout');
    }

    /**
     * Redirect to dashboard based on role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, Admin!');
            
            case 'staff':
                return redirect()->route('staff.dashboard')
                    ->with('success', 'Selamat datang, Staff!');
            
            case 'kurir':
                return redirect()->route('kurir.dashboard')
                    ->with('success', 'Selamat datang, Kurir!');
            
            case 'pelanggan':
                return redirect()->route('pelanggan.dashboard')
                    ->with('success', 'Selamat datang kembali!');
            
            default:
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Role tidak valid!');
        }
    }
}