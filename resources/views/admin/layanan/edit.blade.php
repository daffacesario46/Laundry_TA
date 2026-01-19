@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Layanan</h2>
            <p>Update data layanan</p>
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
                    <form action="{{ route('admin.layanan.update', $layanan->layanan_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_layanan') is-invalid @enderror" 
                                   name="nama_layanan" 
                                   value="{{ old('nama_layanan', $layanan->nama_layanan) }}"
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
                                    <option value="kiloan" {{ old('jenis_cucian', $layanan->jenis_cucian) == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                                    <option value="satuan" {{ old('jenis_cucian', $layanan->jenis_cucian) == 'satuan' ? 'selected' : '' }}>Satuan</option>
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
                                       value="{{ old('durasi_hari', $layanan->durasi_hari) }}"
                                       min="1"
                                       required />
                                @error('durasi_hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ✅ INPUT HARGA --}}
                        <div class="mb-4">
                            <label class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       name="harga" 
                                       value="{{ old('harga', $layanan->harga) }}"
                                       min="0"
                                       step="100"
                                       required />
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Harga per kg (kiloan) atau per item (satuan)</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      name="deskripsi" 
                                      rows="4" 
                                      placeholder="Deskripsi layanan (opsional)">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update
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
                    <h5 class="card-title">Informasi Data</h5>
                    <p class="text-muted small mb-2">
                        <strong>ID:</strong> {{ $layanan->layanan_id }}
                    </p>
                    <p class="text-muted small mb-2">
                        <strong>Harga Saat Ini:</strong><br>
                        <span class="h5 text-primary">{{ $layanan->getFormattedHarga() }}</span>
                    </p>
                    <p class="text-muted small mb-2">
                        <strong>Dibuat:</strong><br>
                        {{ \Carbon\Carbon::parse($layanan->created_at)->format('d M Y H:i') }}
                    </p>
                    <p class="text-muted small">
                        <strong>Terakhir Diupdate:</strong><br>
                        {{ \Carbon\Carbon::parse($layanan->updated_at)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Status Penggunaan</h5>
                    @if($layanan->cucian()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="material-icons md-warning"></i>
                            Layanan ini sedang digunakan dalam <strong>{{ $layanan->cucian()->count() }}</strong> transaksi cucian
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="material-icons md-check_circle"></i>
                            Layanan ini belum pernah digunakan
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tips Update</h5>
                    <ul class="text-muted small">
                        <li>Pastikan durasi pengerjaan realistis</li>
                        <li>Jenis cucian menentukan cara perhitungan harga</li>
                        <li>Harga yang diupdate tidak akan mempengaruhi transaksi lama</li>
                        <li>Perbarui deskripsi jika ada perubahan detail layanan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection