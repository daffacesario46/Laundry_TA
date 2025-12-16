@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Pelanggan Baru</h2>
            <p>Tambahkan data pelanggan baru</p>
        </div>
        <div>
            <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Pelanggan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.pelanggan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="nama@email.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_telp" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                       placeholder="08xxxxxxxxxx" value="{{ old('no_telp') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="4" 
                                      placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            <small class="text-muted">Contoh: Jl. Merdeka No. 123, Jakarta Selatan</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="tanggal_daftar" class="form-label">Tanggal Daftar</label>
                                <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" 
                                       value="{{ old('tanggal_daftar', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="material-icons md-save"></i> Simpan Data
                            </button>
                            <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-light flex-fill">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Panduan Pengisian</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="material-icons md-info text-primary"></i> Tips:</h6>
                        <ul class="text-muted small">
                            <li>Pastikan nama lengkap pelanggan sesuai KTP</li>
                            <li>Email harus valid dan aktif</li>
                            <li>No telepon harus bisa dihubungi</li>
                            <li>Alamat harus lengkap untuk pengiriman</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6><i class="material-icons md-verified_user text-success"></i> Status Pelanggan:</h6>
                        <ul class="text-muted small">
                            <li><strong>Aktif:</strong> Pelanggan masih aktif bertransaksi</li>
                            <li><strong>Non-aktif:</strong> Pelanggan tidak aktif atau suspend</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="alert alert-warning">
                        <small>
                            <i class="material-icons md-warning"></i>
                            <strong>Perhatian!</strong><br>
                            Data yang sudah disimpan dapat diubah melalui menu Edit.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white mb-0">Format Data</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2"><strong>No Telepon:</strong></p>
                    <p class="text-muted small">081234567890</p>

                    <p class="text-muted small mb-2 mt-3"><strong>Email:</strong></p>
                    <p class="text-muted small">nama@email.com</p>

                    <p class="text-muted small mb-2 mt-3"><strong>Alamat:</strong></p>
                    <p class="text-muted small">Jl. Nama Jalan No. XX, Kota</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection