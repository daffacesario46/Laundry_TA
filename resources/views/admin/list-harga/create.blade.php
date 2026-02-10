@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah List Harga</h2>
            <p>Tambahkan daftar harga baru</p>
        </div>
        <div>
            <a href="{{ route('admin.list-harga.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.list-harga.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_item') is-invalid @enderror" 
                                   name="nama_item" 
                                   placeholder="Contoh: Kemeja, Celana Panjang, Jaket, dll" 
                                   value="{{ old('nama_item') }}"
                                   required />
                            @error('nama_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan nama item pakaian atau barang yang akan dicuci</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga_satuan') is-invalid @enderror" 
                                           name="harga_satuan" 
                                           placeholder="0" 
                                           value="{{ old('harga_satuan', 0) }}"
                                           min="0"
                                           required />
                                    @error('harga_satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga per pcs/item (wajib diisi, minimal 0)</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan
                            </button>
                            <a href="{{ route('admin.list-harga.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi</h5>
                    <p class="text-muted small">
                        <i class="material-icons md-info text-info"></i>
                        List harga digunakan untuk menentukan harga cucian per item atau per kilogram.
                    </p>
                    <hr>
                    <p class="text-muted small mb-2"><strong>Panduan:</strong></p>
                    <ul class="text-muted small">
                        <li><strong>Harga Satuan:</strong> Harga per item/pcs (wajib diisi)</li>
                        <li><strong>Harga Kiloan:</strong> Harga per kg (opsional, untuk layanan kiloan)</li>
                        <li>Contoh: Kemeja bisa punya harga satuan Rp 7.000/pcs</li>
                        <li>Atau: Cuci Kiloan Regular Rp 7.000/kg</li>
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Contoh Item</h5>
                    <ul class="text-muted small">
                        <li>Kemeja / Blouse</li>
                        <li>Celana Panjang</li>
                        <li>Celana Pendek</li>
                        <li>Kaos / T-Shirt</li>
                        <li>Jaket Tipis</li>
                        <li>Jaket Tebal</li>
                        <li>Rok</li>
                        <li>Dress / Gaun</li>
                        <li>Jas / Blazer</li>
                        <li>Selimut</li>
                        <li>Bed Cover</li>
                        <li>Boneka</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection