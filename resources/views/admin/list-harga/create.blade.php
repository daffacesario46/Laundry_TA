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
                            <label class="form-label">Jenis Cucian <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_cucian') is-invalid @enderror" name="jenis_cucian" required>
                                <option value="">Pilih Jenis Cucian</option>
                                <option value="Kiloan" {{ old('jenis_cucian') == 'Kiloan' ? 'selected' : '' }}>Kiloan</option>
                                <option value="Satuan" {{ old('jenis_cucian') == 'Satuan' ? 'selected' : '' }}>Satuan</option>
                                <option value="Karpet" {{ old('jenis_cucian') == 'Karpet' ? 'selected' : '' }}>Karpet</option>
                                <option value="Bed Cover" {{ old('jenis_cucian') == 'Bed Cover' ? 'selected' : '' }}>Bed Cover</option>
                                <option value="Boneka" {{ old('jenis_cucian') == 'Boneka' ? 'selected' : '' }}>Boneka</option>
                            </select>
                            @error('jenis_cucian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Layanan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('layanan') is-invalid @enderror" 
                                   name="layanan" 
                                   placeholder="Contoh: Cuci + Setrika" 
                                   value="{{ old('layanan') }}"
                                   required />
                            @error('layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga') is-invalid @enderror" 
                                           name="harga" 
                                           placeholder="0" 
                                           value="{{ old('harga') }}"
                                           min="0"
                                           required />
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select @error('satuan') is-invalid @enderror" name="satuan" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                    <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>Unit</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      name="deskripsi" 
                                      rows="4" 
                                      placeholder="Deskripsi layanan (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        Pastikan harga yang Anda masukkan sudah sesuai dengan jenis layanan dan satuan yang dipilih.
                    </p>
                    <hr>
                    <p class="text-muted small mb-2"><strong>Tips:</strong></p>
                    <ul class="text-muted small">
                        <li>Gunakan harga yang kompetitif</li>
                        <li>Satuan harus sesuai dengan jenis cucian</li>
                        <li>Deskripsi membantu customer memahami layanan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection