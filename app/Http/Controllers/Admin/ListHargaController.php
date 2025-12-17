<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListHarga;
use Illuminate\Http\Request;

class ListHargaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('paginate', 15);
        
        $query = ListHarga::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_item', 'like', "%{$search}%");
        }
        
        $listHarga = $query->orderBy('list_harga_id', 'desc')->paginate($perPage);
        
        return view('admin.list-harga.index', compact('listHarga'));
    }

    public function create()
    {
        return view('admin.list-harga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'harga_kiloan' => 'nullable|numeric|min:0'
        ], [
            'nama_item.required' => 'Nama item harus diisi',
            'harga_satuan.required' => 'Harga satuan harus diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka',
            'harga_satuan.min' => 'Harga satuan tidak boleh kurang dari 0',
            'harga_kiloan.numeric' => 'Harga kiloan harus berupa angka',
            'harga_kiloan.min' => 'Harga kiloan tidak boleh kurang dari 0'
        ]);
        
        ListHarga::create([
            'nama_item' => $request->nama_item,
            'harga_satuan' => $request->harga_satuan,
            'harga_kiloan' => $request->harga_kiloan
        ]);
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $listHarga = ListHarga::findOrFail($id);
        return view('admin.list-harga.edit', compact('listHarga'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'harga_kiloan' => 'nullable|numeric|min:0'
        ], [
            'nama_item.required' => 'Nama item harus diisi',
            'harga_satuan.required' => 'Harga satuan harus diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka',
            'harga_satuan.min' => 'Harga satuan tidak boleh kurang dari 0',
            'harga_kiloan.numeric' => 'Harga kiloan harus berupa angka',
            'harga_kiloan.min' => 'Harga kiloan tidak boleh kurang dari 0'
        ]);
        
        $listHarga = ListHarga::findOrFail($id);
        $listHarga->update([
            'nama_item' => $request->nama_item,
            'harga_satuan' => $request->harga_satuan,
            'harga_kiloan' => $request->harga_kiloan
        ]);
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil diupdate!');
    }

    public function destroy($id)
    {
        $listHarga = ListHarga::findOrFail($id);
        
        // Cek apakah list harga sedang digunakan
        if ($listHarga->cucianDetail()->count() > 0) {
            return redirect()->route('admin.list-harga.index')
                ->with('error', 'List Harga tidak dapat dihapus karena sedang digunakan!');
        }
        
        $listHarga->delete();
        
        return redirect()->route('admin.list-harga.index')
            ->with('success', 'List Harga berhasil dihapus!');
    }
}