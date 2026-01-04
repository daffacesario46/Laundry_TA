<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        $user = Auth::user();

        // Cek apakah user aktif
        if ($user->status !== 'aktif') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak aktif!');
        }

        // Cek apakah role user sesuai
        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard sesuai role user
            return $this->redirectToDashboard($user->role)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return $next($request);
    }

    /**
     * Redirect to dashboard based on role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'kurir':
                return redirect()->route('kurir.dashboard');
            case 'pelanggan':
                return redirect()->route('pelanggan.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}