<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = Layanan::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan jenis cucian
        if ($request->filled('jenis_cucian')) {
            $query->where('jenis_cucian', $request->jenis_cucian);
        }
        
        $layanan = $query->orderBy('layanan_id', 'desc')->paginate($perPage);
        
        return view('admin.layanan.index', compact('layanan'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'jenis_cucian' => 'required|in:kiloan,satuan',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'nama_layanan.required' => 'Nama layanan harus diisi',
            'jenis_cucian.required' => 'Jenis cucian harus dipilih',
            'jenis_cucian.in' => 'Jenis cucian tidak valid',
            'durasi_hari.required' => 'Durasi hari harus diisi',
            'durasi_hari.integer' => 'Durasi hari harus berupa angka',
            'durasi_hari.min' => 'Durasi hari minimal 1 hari',
            'harga.required' => 'Harga harus diisi', // ← TAMBAH INI
            'harga.numeric' => 'Harga harus berupa angka', // ← TAMBAH INI
            'harga.min' => 'Harga tidak boleh kurang dari 0' // ← TAMBAH INI
        ]);
        
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'jenis_cucian' => $request->jenis_cucian,
            'durasi_hari' => $request->durasi_hari,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi
        ]);
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'jenis_cucian' => 'required|in:kiloan,satuan',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'nama_layanan.required' => 'Nama layanan harus diisi',
            'jenis_cucian.required' => 'Jenis cucian harus dipilih',
            'jenis_cucian.in' => 'Jenis cucian tidak valid',
            'durasi_hari.required' => 'Durasi hari harus diisi',
            'durasi_hari.integer' => 'Durasi hari harus berupa angka',
            'durasi_hari.min' => 'Durasi hari minimal 1 hari',
            'harga.required' => 'Harga harus diisi', // ← TAMBAH INI
            'harga.numeric' => 'Harga harus berupa angka', // ← TAMBAH INI
            'harga.min' => 'Harga tidak boleh kurang dari 0' // ← TAMBAH INI
        ]);
        
        $layanan = Layanan::findOrFail($id);
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'jenis_cucian' => $request->jenis_cucian,
            'durasi_hari' => $request->durasi_hari,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi
        ]);
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        
        // Cek apakah layanan sedang digunakan
        if ($layanan->cucian()->count() > 0) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Layanan tidak dapat dihapus karena sedang digunakan!');
        }
        
        $layanan->delete();
        
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }
}