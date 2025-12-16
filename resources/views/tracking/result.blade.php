@extends('layouts.home')

@section('content')
<div class="container-xxl py-6">
    <div class="container">
        <!-- Search Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <form action="{{ route('tracking.track') }}" method="POST">
                    @csrf
                    <div class="position-relative">
                        <input class="form-control form-control-lg rounded-pill ps-4 pe-5" 
                               name="no_order" 
                               type="text" 
                               placeholder="Masukkan Kode Transaksi untuk Cek Status" 
                               value="{{ $cucian->no_order }}">
                        <button class="btn btn-primary rounded-pill py-2 px-4 position-absolute top-0 end-0 m-1">
                            <i class="fa fa-search me-1"></i> Cek
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Status Header -->
                    @if($cucian->status == 'menunggu')
                        <div class="card-header bg-warning" style="height: 150px"></div>
                    @elseif($cucian->status == 'proses')
                        <div class="card-header bg-info" style="height: 150px"></div>
                    @elseif($cucian->status == 'selesai')
                        <div class="card-header bg-success" style="height: 150px"></div>
                    @else
                        <div class="card-header bg-primary" style="height: 150px"></div>
                    @endif

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Logo -->
                            <div class="col-xl col-lg flex-grow-0" style="flex-basis: 230px">
                                <div class="img-thumbnail shadow w-100 bg-white position-relative text-center" style="height: 190px; width: 200px; margin-top: -120px">
                                    <img src="{{ asset('admins/imgs/theme/washwes.png') }}" style="max-height: 190px; max-width: 200px;" class="center-xy img-fluid" alt="Logo Brand" />
                                </div>
                            </div>

                            <!-- Order Info -->
                            <div class="col-xl col-lg">
                                <h3 class="mb-1">{{ $cucian->no_order }}</h3>
                                <p class="text-muted mb-0">{{ $cucian->nama_pelanggan }}</p>
                            </div>

                            <!-- Status Badge -->
                            <div class="col-xl-6 text-md-end">
                                @if($cucian->status == 'menunggu')
                                    <span class="badge bg-warning text-dark" style="width: 30%; font-size: 16px; padding: 10px;">
                                        <i class="fa fa-clock me-1"></i> Menunggu Konfirmasi
                                    </span>
                                @elseif($cucian->status == 'proses')
                                    <span class="badge bg-info" style="width: 30%; font-size: 16px; padding: 10px;">
                                        <i class="fa fa-sync fa-spin me-1"></i> Sedang Diproses
                                    </span>
                                @elseif($cucian->status == 'selesai')
                                    <span class="badge bg-success" style="width: 30%; font-size: 16px; padding: 10px;">
                                        <i class="fa fa-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-primary" style="width: 30%; font-size: 16px; padding: 10px;">
                                        <i class="fa fa-check-double me-1"></i> Sudah Diambil
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4" />

                        <!-- Detail Information -->
                        <div class="row g-4">
                            <!-- Price Info -->
                            <div class="col-md-12 col-lg-3 col-xl-2">
                                <article class="box">
                                    <p class="mb-1 text-muted small">Berat:</p>
                                    <h5 class="text-primary mb-3">{{ $cucian->berat }} KG</h5>

                                    <p class="mb-1 text-muted small">Jenis Layanan:</p>
                                    <h6 class="mb-3">{{ $cucian->jenis_layanan }}</h6>

                                    <p class="mb-1 text-muted small">Total Harga:</p>
                                    <h5 class="text-success mb-0">Rp {{ number_format($cucian->total_harga, 0, ',', '.') }}</h5>
                                </article>
                            </div>

                            <!-- Customer Detail -->
                            <div class="col-sm-6 col-lg-5 col-xl-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fa fa-user me-2 text-primary"></i>Detail Pelanggan
                                </h6>
                                <p>
                                    <strong>Nama:</strong> {{ $cucian->nama_pelanggan }}<br />
                                    <strong>Telepon:</strong> {{ $cucian->no_telp }}<br />
                                    @if($cucian->staff_name)
                                    <strong>Ditangani oleh:</strong> {{ $cucian->staff_name }}<br />
                                    @endif
                                </p>
                            </div>

                            <!-- Order Detail -->
                            <div class="col-sm-6 col-lg-5 col-xl-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="fa fa-receipt me-2 text-primary"></i>Detail Pesanan
                                </h6>
                                <p>
                                    <strong>Diterima:</strong><br>
                                    {{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('d M Y, H:i') }}<br />
                                    
                                    @if($cucian->tanggal_selesai)
                                    <strong>Selesai:</strong><br>
                                    {{ \Carbon\Carbon::parse($cucian->tanggal_selesai)->format('d M Y, H:i') }}<br />
                                    @else
                                    <strong>Estimasi Selesai:</strong><br>
                                    {{ \Carbon\Carbon::parse($cucian->estimasi_selesai)->format('d M Y, H:i') }}<br />
                                    @endif
                                </p>
                            </div>

                            <!-- Address -->
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="fa fa-map-marker-alt me-2 text-primary"></i>Alamat Pengantaran
                                </h6>
                                <p>{{ $cucian->alamat }}</p>
                                @if($cucian->catatan)
                                <div class="alert alert-warning p-2 small">
                                    <strong>Catatan:</strong> {{ $cucian->catatan }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4" />

                        <!-- Timeline -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold mb-4">
                                    <i class="fa fa-clock me-2 text-primary"></i>Timeline Pesanan
                                </h6>

                                <div class="timeline-horizontal">
                                    <div class="row text-center">
                                        <!-- Step 1: Diterima -->
                                        <div class="col-3">
                                            <div class="timeline-step completed">
                                                <div class="timeline-icon bg-primary">
                                                    <i class="fa fa-check text-white"></i>
                                                </div>
                                                <h6 class="mt-3 mb-1">Diterima</h6>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('d M Y') }}<br>
                                                    {{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('H:i') }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Step 2: Proses -->
                                        <div class="col-3">
                                            <div class="timeline-step {{ in_array($cucian->status, ['proses', 'selesai', 'diambil']) ? 'completed' : '' }} {{ $cucian->status == 'proses' ? 'active' : '' }}">
                                                <div class="timeline-icon {{ in_array($cucian->status, ['proses', 'selesai', 'diambil']) ? 'bg-info' : 'bg-secondary' }}">
                                                    <i class="fa {{ $cucian->status == 'proses' ? 'fa-sync fa-spin' : 'fa-check' }} text-white"></i>
                                                </div>
                                                <h6 class="mt-3 mb-1">Diproses</h6>
                                                <small class="text-muted">
                                                    @if($cucian->tanggal_proses)
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_proses)->format('d M Y') }}<br>
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_proses)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Step 3: Selesai -->
                                        <div class="col-3">
                                            <div class="timeline-step {{ in_array($cucian->status, ['selesai', 'diambil']) ? 'completed' : '' }} {{ $cucian->status == 'selesai' ? 'active' : '' }}">
                                                <div class="timeline-icon {{ in_array($cucian->status, ['selesai', 'diambil']) ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="fa fa-check-circle text-white"></i>
                                                </div>
                                                <h6 class="mt-3 mb-1">Selesai</h6>
                                                <small class="text-muted">
                                                    @if($cucian->tanggal_selesai)
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_selesai)->format('d M Y') }}<br>
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_selesai)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Step 4: Diambil -->
                                        <div class="col-3">
                                            <div class="timeline-step {{ $cucian->status == 'diambil' ? 'completed' : '' }}">
                                                <div class="timeline-icon {{ $cucian->status == 'diambil' ? 'bg-primary' : 'bg-secondary' }}">
                                                    <i class="fa fa-shopping-bag text-white"></i>
                                                </div>
                                                <h6 class="mt-3 mb-1">Diambil</h6>
                                                <small class="text-muted">
                                                    @if($cucian->tanggal_diambil)
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_diambil)->format('d M Y') }}<br>
                                                        {{ \Carbon\Carbon::parse($cucian->tanggal_diambil)->format('H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button onclick="window.print()" class="btn btn-outline-primary">
                                        <i class="fa fa-print me-1"></i> Cetak
                                    </button>
                                    <a href="{{ route('tracking.index') }}" class="btn btn-primary">
                                        <i class="fa fa-search me-1"></i> Lacak Lagi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-horizontal {
        position: relative;
    }
    .timeline-horizontal::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 12.5%;
        right: 12.5%;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }
    .timeline-step {
        position: relative;
        z-index: 1;
    }
    .timeline-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 24px;
        border: 4px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .timeline-step.active .timeline-icon {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .timeline-step.completed::before {
        background: var(--bs-primary);
    }

    @media print {
        .btn, form, .navbar, .footer {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endsection