@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Stok Bahan</h2>
            <p>Form untuk menambah stok bahan baru</p>
        </div>
        <div>
            <a href="{{ route('admin.stok-bahan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Stok Bahan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stok-bahan.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Jenis Bahan -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Jenis Bahan <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="jenis_bahan" 
                                    class="form-control @error('jenis_bahan') is-invalid @enderror" 
                                    placeholder="Contoh: Detergen, Pewangi, Plastik Laundry, dll"
                                    value="{{ old('jenis_bahan') }}"
                                    required>
                                @error('jenis_bahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Ketik jenis bahan yang ingin ditambahkan</small>
                            </div>

                            <!-- Merk -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Merk/Brand <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="merk" 
                                       class="form-control @error('merk') is-invalid @enderror" 
                                       placeholder="Contoh: Rinso, Molto, Downy" 
                                       value="{{ old('merk') }}"
                                       required>
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stok Tersedia -->
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Stok Tersedia <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="stok_tersedia" 
                                       class="form-control @error('stok_tersedia') is-invalid @enderror" 
                                       placeholder="0" 
                                       value="{{ old('stok_tersedia', 0) }}"
                                       min="0"
                                       step="0.01"
                                       required>
                                @error('stok_tersedia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                    <option value="botol" {{ old('satuan') == 'botol' ? 'selected' : '' }}>Botol</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stok Minimum -->
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="stok_minimum" 
                                       class="form-control @error('stok_minimum') is-invalid @enderror" 
                                       placeholder="0" 
                                       value="{{ old('stok_minimum', 10) }}"
                                       min="0"
                                       step="0.01"
                                       required>
                                @error('stok_minimum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Batas minimal stok sebelum restock</small>
                            </div>

                            <!-- Harga Beli -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Harga Beli per Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           name="harga_beli" 
                                           class="form-control @error('harga_beli') is-invalid @enderror" 
                                           placeholder="0" 
                                           value="{{ old('harga_beli', 0) }}"
                                           min="0">
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" 
                                          class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Deskripsi tambahan tentang bahan ini...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.stok-bahan.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="material-icons text-warning">info</i>
                        <strong>Tips:</strong>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="material-icons md-check text-success"></i>
                            Pastikan nama merk sesuai dengan produk asli
                        </li>
                        <li class="mb-2">
                            <i class="material-icons md-check text-success"></i>
                            Stok minimum digunakan untuk notifikasi restock
                        </li>
                        <li class="mb-2">
                            <i class="material-icons md-check text-success"></i>
                            Harga beli opsional, untuk tracking pengeluaran
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection