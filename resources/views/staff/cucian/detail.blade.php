@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Cucian</h2>
            <p>Informasi lengkap cucian {{ $cucian->no_order }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
            <a href="{{ route('staff.cucian.edit', $cucian->id) }}" class="btn btn-primary">
                <i class="material-icons md-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Cucian</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">No Order</p>
                            <h5>{{ $cucian->no_order }}</h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Status</p>
                            <h5>
                                @if($cucian->status == 'menunggu')
                                    <span class="badge rounded-pill alert-warning">Menunggu</span>
                                @elseif($cucian->status == 'proses')
                                    <span class="badge rounded-pill alert-info">Proses</span>
                                @elseif($cucian->status == 'selesai')
                                    <span class="badge rounded-pill alert-success">Selesai</span>
                                @elseif($cucian->status == 'diambil')
                                    <span class="badge rounded-pill alert-secondary">Diambil</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Tanggal Masuk</p>
                            <h5>{{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('d M Y H:i') }}</h5>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Data Pelanggan</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-person"></i> Nama Pelanggan</p>
                            <p class="fw-bold">{{ $cucian->nama_pelanggan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-phone"></i> No Telepon</p>
                            <p class="fw-bold">{{ $cucian->no_telp }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-muted mb-1"><i class="material-icons md-location_on"></i> Alamat</p>
                            <p class="fw-bold">{{ $cucian->alamat }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Detail Layanan</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-local_laundry_service"></i> Jenis Layanan</p>
                            <p class="fw-bold">{{ $cucian->jenis_layanan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-scale"></i> Berat</p>
                            <p class="fw-bold">{{ $cucian->berat }} Kg</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Harga per Kg</p>
                            <p class="fw-bold">Rp {{ number_format($cucian->harga_per_kg, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total Harga</p>
                            <h4 class="text-primary">Rp {{ number_format($cucian->total_harga, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    @if($cucian->catatan)
                    <hr>
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-note"></i> Catatan</p>
                        <div class="alert alert-info">
                            {{ $cucian->catatan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white mb-0">Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diterima</h6>
                                <p class="text-muted small mb-0">
                                    {{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('d M Y H:i') }}
                                </p>
                                <span class="badge bg-primary">Masuk</span>
                            </div>
                        </div>

                        @if($cucian->status == 'proses' || $cucian->status == 'selesai' || $cucian->status == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Sedang Diproses</h6>
                                <span class="badge bg-info">Proses</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status == 'selesai' || $cucian->status == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                @if($cucian->tanggal_selesai)
                                <p class="text-muted small mb-0">
                                    {{ \Carbon\Carbon::parse($cucian->tanggal_selesai)->format('d M Y H:i') }}
                                </p>
                                @endif
                                <span class="badge bg-success">Selesai</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status == 'diambil')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diambil</h6>
                                <span class="badge bg-secondary">Diambil</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status == 'menunggu')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Konfirmasi</h6>
                                <span class="badge bg-warning">Menunggu</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>Aksi</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.cucian.edit', $cucian->id) }}" class="btn btn-primary">
                            <i class="material-icons md-edit"></i> Edit Data
                        </a>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="material-icons md-print"></i> Cetak
                        </button>
                        <button class="btn btn-danger" onclick="confirmDelete()">
                            <i class="material-icons md-delete"></i> Hapus
                        </button>
                    </div>

                    <form id="delete-form" action="{{ route('staff.cucian.destroy', $cucian->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-marker {
        position: absolute;
        left: -26px;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px currentColor;
    }
    .timeline-content {
        padding-left: 10px;
    }

    @media print {
        .btn, .content-header, .card-header h4:contains('Aksi') {
            display: none !important;
        }
    }
</style>

<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Data Cucian?',
            text: 'Yakin ingin menghapus cucian {{ $cucian->no_order }}?',
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