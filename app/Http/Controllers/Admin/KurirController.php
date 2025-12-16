<?php

// ============================================
// FILE 2: app/Http/Controllers/Admin/KurirController.php
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class KurirController extends Controller
{
    public function index(Request $request)
    {
        // Dummy data untuk testing
        $dummyData = collect([
            (object)[
                'users_id' => 4,
                'nama' => 'Rudi Hermawan',
                'email' => 'rudi.kurir@washwes.com',
                'no_telp' => '081234567893',
                'no_wa' => '081234567893',
                'alamat' => 'Jl. Gatot Subroto No. 11, Jakarta',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now()->subDays(25),
            ],
            (object)[
                'users_id' => 5,
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.kurir@washwes.com',
                'no_telp' => '081234567894',
                'no_wa' => '081234567894',
                'alamat' => 'Jl. Kuningan No. 22, Jakarta',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now()->subDays(15),
            ],
            (object)[
                'users_id' => 6,
                'nama' => 'Dedi Supriadi',
                'email' => 'dedi.kurir@washwes.com',
                'no_telp' => '081234567895',
                'no_wa' => '081234567895',
                'alamat' => 'Jl. Casablanca No. 33, Jakarta',
                'foto' => null,
                'status' => 'aktif',
                'created_at' => now()->subDays(5),
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
        
        $kurir = new LengthAwarePaginator(
            $items,
            $dummyData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.kurir.index', compact('kurir'));
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
        // $kurir = User::create([
        //     'role' => 'kurir',
        //     'nama' => $request->nama,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'no_telp' => $request->no_telp,
        //     'no_wa' => $request->no_wa,
        //     'alamat' => $request->alamat,
        //     'status' => 'aktif',
        // ]);

        // if ($request->hasFile('foto')) {
        //     $path = $request->file('foto')->store('kurir', 'public');
        //     $kurir->foto = $path;
        //     $kurir->save();
        // }

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan');
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
        // $kurir = User::findOrFail($id);
        // $kurir->update([...]);

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil diupdate');
    }

    public function destroy($id)
    {
        // TODO: Delete from database
        // $kurir = User::findOrFail($id);
        // if ($kurir->foto) {
        //     Storage::disk('public')->delete($kurir->foto);
        // }
        // $kurir->delete();

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        // TODO: Toggle status
        // $kurir = User::findOrFail($id);
        // $kurir->status = $kurir->status == 'aktif' ? 'nonaktif' : 'aktif';
        // $kurir->save();

        return redirect()->route('admin.kurir.index')
            ->with('success', 'Status kurir berhasil diubah');
    }
}