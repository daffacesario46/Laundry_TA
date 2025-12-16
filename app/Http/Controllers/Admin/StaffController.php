<?php
// ============================================
// FILE 1: app/Http/Controllers/Admin/StaffController.php
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        // Dummy data untuk testing
        $dummyData = collect([
            (object)[
                'users_id' => 1,
                'nama' => 'Budi Santoso',
                'email' => 'budi.staff@washwes.com',
                'no_telp' => '081234567890',
                'no_wa' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now()->subDays(30),
            ],
            (object)[
                'users_id' => 2,
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.staff@washwes.com',
                'no_telp' => '081234567891',
                'no_wa' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now()->subDays(20),
            ],
            (object)[
                'users_id' => 3,
                'nama' => 'Ahmad Hidayat',
                'email' => 'ahmad.staff@washwes.com',
                'no_telp' => '081234567892',
                'no_wa' => '081234567892',
                'alamat' => 'Jl. Thamrin No. 78, Jakarta',
                'foto' => null,
                'status' => 'nonaktif',
                'created_at' => now()->subDays(10),
            ],
        ]);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $dummyData = $dummyData->filter(function($item) use ($search) {
                return str_contains(strtolower($item->nama), $search) ||
                       str_contains(strtolower($item->email), $search);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $dummyData = $dummyData->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->get('paginate', 15);
        $currentPage = $request->get('page', 1);
        $items = $dummyData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $staff = new LengthAwarePaginator(
            $items,
            $dummyData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telp' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // TODO: Save to database
        // $staff = User::create([
        //     'role' => 'staff',
        //     'nama' => $request->nama,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'no_telp' => $request->no_telp,
        //     'no_wa' => $request->no_wa,
        //     'alamat' => $request->alamat,
        //     'status' => 'aktif',
        // ]);

        // if ($request->hasFile('foto')) {
        //     $path = $request->file('foto')->store('staff', 'public');
        //     $staff->foto = $path;
        //     $staff->save();
        // }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',users_id',
            'password' => 'nullable|string|min:6|confirmed',
            'no_telp' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // TODO: Update database
        // $staff = User::findOrFail($id);
        // $staff->update([
        //     'nama' => $request->nama,
        //     'email' => $request->email,
        //     'no_telp' => $request->no_telp,
        //     'no_wa' => $request->no_wa,
        //     'alamat' => $request->alamat,
        // ]);

        // if ($request->filled('password')) {
        //     $staff->password = Hash::make($request->password);
        //     $staff->save();
        // }

        // if ($request->hasFile('foto')) {
        //     if ($staff->foto) {
        //         Storage::disk('public')->delete($staff->foto);
        //     }
        //     $path = $request->file('foto')->store('staff', 'public');
        //     $staff->foto = $path;
        //     $staff->save();
        // }

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil diupdate');
    }

    public function destroy($id)
    {
        // TODO: Delete from database
        // $staff = User::findOrFail($id);
        // if ($staff->foto) {
        //     Storage::disk('public')->delete($staff->foto);
        // }
        // $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        // TODO: Toggle status
        // $staff = User::findOrFail($id);
        // $staff->status = $staff->status == 'aktif' ? 'nonaktif' : 'aktif';
        // $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Status staff berhasil diubah');
    }
}