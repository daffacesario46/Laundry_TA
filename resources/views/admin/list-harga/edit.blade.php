@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit List Harga</h2>
            <p>Update data list harga</p>
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
                    <form action="{{ route('admin.list-harga.update', $listHarga->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Jenis Cucian <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_cucian') is-invalid @enderror" name="jenis_cucian" required>
                                <option value="">Pilih Jenis Cucian</option>
                                <option value="Kiloan" {{ old('jenis_cucian', $listHarga->jenis_cucian) == 'Kiloan' ? 'selected' : '' }}>Kiloan</option>
                                <option value="Satuan" {{ old('jenis_cucian', $listHarga->jenis_cucian) == 'Satuan' ? 'selected' : '' }}>Satuan</option>
                                <option value="Karpet" {{ old('jenis_cucian', $listHarga->jenis_cucian) == 'Karpet' ? 'selected' : '' }}>Karpet</option>
                                <option value="Bed Cover" {{ old('jenis_cucian', $listHarga->jenis_cucian) == 'Bed Cover' ? 'selected' : '' }}>Bed Cover</option>
                                <option value="Boneka" {{ old('jenis_cucian', $listHarga->jenis_cucian) == 'Boneka' ? 'selected' : '' }}>Boneka</option>
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
                                   value="{{ old('layanan', $listHarga->layanan) }}"
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
                                           value="{{ old('harga', $listHarga->harga) }}"
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
                                    <option value="kg" {{ old('satuan', $listHarga->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="pcs" {{ old('satuan', $listHarga->satuan) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                    <option value="unit" {{ old('satuan', $listHarga->satuan) == 'unit' ? 'selected' : '' }}>Unit</option>
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
                                      rows="4">{{ old('deskripsi', $listHarga->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update
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
                    <h5 class="card-title">Informasi Data</h5>
                    <p class="text-muted small mb-2">
                        <strong>Dibuat:</strong><br>
                        {{ \Carbon\Carbon::parse($listHarga->created_at)->format('d M Y H:i') }}
                    </p>
                    <p class="text-muted small">
                        <strong>Terakhir Diupdate:</strong><br>
                        {{ \Carbon\Carbon::parse($listHarga->updated_at)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection