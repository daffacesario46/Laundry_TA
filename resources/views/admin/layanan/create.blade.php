@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Layanan</h2>
            <p>Tambahkan layanan laundry baru</p>
        </div>
        <div>
            <a href="{{ route('admin.layanan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.layanan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_layanan') is-invalid @enderror" 
                                   name="nama_layanan" 
                                   placeholder="Contoh: Cuci + Setrika Express" 
                                   value="{{ old('nama_layanan') }}"
                                   required />
                            @error('nama_layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Jenis Cucian <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_cucian') is-invalid @enderror" name="jenis_cucian" required>
                                    <option value="">Pilih Jenis Cucian</option>
                                    <option value="kiloan" {{ old('jenis_cucian') == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                                    <option value="satuan" {{ old('jenis_cucian') == 'satuan' ? 'selected' : '' }}>Satuan</option>
                                </select>
                                @error('jenis_cucian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Durasi (Hari) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('durasi_hari') is-invalid @enderror" 
                                       name="durasi_hari" 
                                       placeholder="Contoh: 3" 
                                       value="{{ old('durasi_hari', 3) }}"
                                       min="1"
                                       required />
                                @error('durasi_hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Estimasi waktu pengerjaan dalam hari</small>
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
                            <a href="{{ route('admin.layanan.index') }}" class="btn btn-light">
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
                        Pastikan nama layanan sudah sesuai dan durasi pengerjaan realistis.
                    </p>
                    <hr>
                    <p class="text-muted small mb-2"><strong>Jenis Cucian:</strong></p>
                    <ul class="text-muted small">
                        <li><strong>Kiloan:</strong> Dihitung berdasarkan berat (kg)</li>
                        <li><strong>Satuan:</strong> Dihitung per item/pcs</li>
                    </ul>
                    <hr>
                    <p class="text-muted small mb-2"><strong>Contoh Layanan:</strong></p>
                    <ul class="text-muted small">
                        <li>Cuci Kering (kiloan)</li>
                        <li>Cuci Setrika (kiloan)</li>
                        <li>Cuci Setrika Express (kiloan)</li>
                        <li>Setrika Saja (kiloan)</li>
                        <li>Cuci Satuan Premium (satuan)</li>
                        <li>Dry Cleaning (satuan)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection