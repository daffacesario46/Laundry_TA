@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Stok Bahan</h2>
            <p>Form untuk mengubah data stok bahan</p>
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
                    <form action="{{ route('admin.stok-bahan.update', $stokBahan->stok_bahan_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Jenis Bahan <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="jenis_bahan" 
                                    class="form-control @error('jenis_bahan') is-invalid @enderror" 
                                    placeholder="Contoh: Detergen, Pewangi, Plastik Laundry, dll"
                                    value="{{ old('jenis_bahan', $stokBahan->jenis_bahan) }}"
                                    required>
                                @error('jenis_bahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Ketik jenis bahan</small>
                            </div>

                            <!-- Merk -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Merk/Brand <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="merk" 
                                       class="form-control @error('merk') is-invalid @enderror" 
                                       placeholder="Contoh: Rinso, Molto, Downy" 
                                       value="{{ old('merk', $stokBahan->merk) }}"
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
                                       value="{{ old('stok_tersedia', $stokBahan->stok_tersedia) }}"
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
                                    <option value="kg" {{ old('satuan', $stokBahan->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="liter" {{ old('satuan', $stokBahan->satuan) == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pcs" {{ old('satuan', $stokBahan->satuan) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                    <option value="botol" {{ old('satuan', $stokBahan->satuan) == 'botol' ? 'selected' : '' }}>Botol</option>
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
                                       value="{{ old('stok_minimum', $stokBahan->stok_minimum) }}"
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
                                           value="{{ old('harga_beli', $stokBahan->harga_beli) }}"
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
                                          placeholder="Deskripsi tambahan tentang bahan ini...">{{ old('deskripsi', $stokBahan->deskripsi) }}</textarea>
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
                                <i class="material-icons md-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Status Box -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Status Stok</h5>
                </div>
                <div class="card-body">
                    @php
                        $status = 'Aman';
                        $badgeClass = 'bg-success';
                        if ($stokBahan->stok_tersedia <= 0) {
                            $status = 'Habis';
                            $badgeClass = 'bg-danger';
                        } elseif ($stokBahan->stok_tersedia <= $stokBahan->stok_minimum) {
                            $status = 'Menipis';
                            $badgeClass = 'bg-warning text-dark';
                        }
                    @endphp
                    
                    <div class="text-center mb-3">
                        <h1 class="mb-0">{{ $stokBahan->stok_tersedia }}</h1>
                        <p class="text-muted mb-2">{{ $stokBahan->satuan }}</p>
                        <span class="badge {{ $badgeClass }} px-3 py-2">{{ $status }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Stok Minimum:</span>
                        <strong>{{ $stokBahan->stok_minimum }} {{ $stokBahan->satuan }}</strong>
                    </div>
                    
                    @if($stokBahan->harga_beli)
                    <div class="d-flex justify-content-between">
                        <span>Harga Beli:</span>
                        <strong>Rp {{ number_format($stokBahan->harga_beli, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection