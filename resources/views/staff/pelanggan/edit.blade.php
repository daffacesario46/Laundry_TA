@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Pelanggan</h2>
            <p>Edit data pelanggan {{ $pelanggan->nama }}</p>
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
                    <form action="{{ route('staff.pelanggan.update', $pelanggan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="{{ $pelanggan->nama }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $pelanggan->email }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_telp" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                       value="{{ $pelanggan->no_telp }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ $pelanggan->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $pelanggan->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="4" required>{{ $pelanggan->alamat }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="tanggal_daftar" class="form-label">Tanggal Daftar</label>
                                <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" 
                                       value="{{ $pelanggan->tanggal_daftar }}">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $pelanggan->status == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="material-icons md-save"></i> Update Data
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
                <div class="card-header bg-info text-white">
                    <h4 class="text-white mb-0">Statistik Pelanggan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Total Transaksi</p>
                        <h3 class="text-primary">{{ $pelanggan->total_transaksi }}</h3>
                        <small class="text-muted">Transaksi selesai</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Bergabung Sejak</p>
                        <h6>{{ \Carbon\Carbon::parse($pelanggan->tanggal_daftar)->format('d F Y') }}</h6>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($pelanggan->tanggal_daftar)->diffForHumans() }}
                        </small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Status Saat Ini</p>
                        @if($pelanggan->status == 'aktif')
                            <span class="badge rounded-pill alert-success">Aktif</span>
                        @else
                            <span class="badge rounded-pill alert-warning">Non-aktif</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Kontak</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-phone"></i> Telepon
                        </p>
                        <p class="fw-bold">{{ $pelanggan->no_telp }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-email"></i> Email
                        </p>
                        <p class="fw-bold">{{ $pelanggan->email }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-location_on"></i> Alamat
                        </p>
                        <p class="fw-bold">{{ $pelanggan->alamat }}</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h4 class="text-white mb-0">Aksi Lainnya</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary" onclick="window.print()">
                            <i class="material-icons md-print"></i> Cetak Data
                        </button>
                        <button class="btn btn-danger" onclick="confirmDelete()">
                            <i class="material-icons md-delete"></i> Hapus Pelanggan
                        </button>
                    </div>

                    <form id="delete-form" action="{{ route('staff.pelanggan.destroy', $pelanggan->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Data Pelanggan?',
            text: 'Yakin ingin menghapus pelanggan {{ $pelanggan->nama }}?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endsection