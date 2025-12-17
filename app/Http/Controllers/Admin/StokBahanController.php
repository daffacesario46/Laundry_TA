<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokBahan;
use Illuminate\Http\Request;

class StokBahanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = StokBahan::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis_bahan', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan jenis bahan
        if ($request->filled('jenis_bahan')) {
            $query->where('jenis_bahan', $request->jenis_bahan);
        }
        
        $stokBahan = $query->orderBy('stok_bahan_id', 'desc')->paginate($perPage);
        
        return view('admin.stok-bahan.index', compact('stokBahan'));
    }

    public function create()
    {
        return view('admin.stok-bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_bahan' => 'required|string|max:100',
            'merk' => 'required|string|max:255',
            'stok_tersedia' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_bahan.required' => 'Jenis bahan harus diisi',
            'merk.required' => 'Merk harus diisi',
            'stok_tersedia.required' => 'Stok tersedia harus diisi',
            'stok_tersedia.numeric' => 'Stok tersedia harus berupa angka',
            'stok_tersedia.min' => 'Stok tersedia tidak boleh negatif',
            'satuan.required' => 'Satuan harus diisi',
            'stok_minimum.required' => 'Stok minimum harus diisi',
            'stok_minimum.numeric' => 'Stok minimum harus berupa angka',
            'harga_beli.numeric' => 'Harga beli harus berupa angka'
        ]);
        
        StokBahan::create([
            'jenis_bahan' => $request->jenis_bahan,
            'merk' => $request->merk,
            'stok_tersedia' => $request->stok_tersedia,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $request->harga_beli,
            'deskripsi' => $request->deskripsi
        ]);
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $stokBahan = StokBahan::with('pemakaian.pembelian')->findOrFail($id);
        return view('admin.stok-bahan.detail', compact('stokBahan'));
    }

    public function edit($id)
    {
        $stokBahan = StokBahan::findOrFail($id);
        return view('admin.stok-bahan.edit', compact('stokBahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_bahan' => 'required|string|max:100',
            'merk' => 'required|string|max:255',
            'stok_tersedia' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ], [
            'jenis_bahan.required' => 'Jenis bahan harus diisi',
            'merk.required' => 'Merk harus diisi',
            'stok_tersedia.required' => 'Stok tersedia harus diisi',
            'satuan.required' => 'Satuan harus diisi',
            'stok_minimum.required' => 'Stok minimum harus diisi'
        ]);
        
        $stokBahan = StokBahan::findOrFail($id);
        $stokBahan->update([
            'jenis_bahan' => $request->jenis_bahan,
            'merk' => $request->merk,
            'stok_tersedia' => $request->stok_tersedia,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => $request->harga_beli,
            'deskripsi' => $request->deskripsi
        ]);
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $stokBahan = StokBahan::findOrFail($id);
        
        // Cek apakah stok bahan sedang digunakan
        if ($stokBahan->pemakaian()->count() > 0) {
            return redirect()->route('admin.stok-bahan.index')
                ->with('error', 'Stok bahan tidak dapat dihapus karena sudah memiliki riwayat pemakaian!');
        }
        
        $stokBahan->delete();
        
        return redirect()->route('admin.stok-bahan.index')
            ->with('success', 'Stok bahan berhasil dihapus!');
    }
}