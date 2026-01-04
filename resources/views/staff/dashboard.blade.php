@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Dashboard Staff</h2>
            <p>Selamat datang, <strong>{{ auth()->user()->nama ?? 'Staff' }}</strong></p>
        </div>
        <div>
            <span class="badge bg-info">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="material-icons md-check_circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="material-icons md-error"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-primary-light">
                        <i class="text-primary material-icons md-local_laundry_service"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Cucian</h6>
                        <span class="h5">{{ $totalCucian ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Semua data cucian</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-warning-light">
                        <i class="text-warning material-icons md-pending_actions"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Menunggu</h6>
                        <span class="h5">{{ $cucianMenunggu ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Perlu konfirmasi</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-info-light">
                        <i class="text-info material-icons md-autorenew"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Sedang Proses</h6>
                        <span class="h5">{{ $cucianProses ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Dalam pengerjaan</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-success-light">
                        <i class="text-success material-icons md-check_circle"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Selesai</h6>
                        <span class="h5">{{ $cucianSelesai ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Siap diambil</p>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Statistik Hari Ini -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Statistik Hari Ini</h5>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $cucianHariIni ?? 0 }}</h3>
                                <p class="text-muted mb-0">Cucian Masuk</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-success">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                                <p class="text-muted mb-0">Pendapatan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Info Pelanggan</h5>
                    <div class="row mt-3">
                        <div class="col-12">
                            <article class="icontext">
                                <span class="icon icon-sm rounded-circle bg-primary-light">
                                    <i class="text-primary material-icons md-people"></i>
                                </span>
                                <div class="text">
                                    <h3 class="mb-0">{{ $totalPelanggan ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Total Pelanggan Aktif</p>
                                </div>
                            </article>
                            <div class="mt-3">
                                <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-primary w-100">
                                    <i class="material-icons md-people"></i> Lihat Semua Pelanggan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cucian Terbaru -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <header class="card-header">
                    <h4 class="card-title">Cucian Terbaru</h4>
                </header>
                <div class="card-body">
                    @if(isset($cucianTerbaru) && $cucianTerbaru->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Order</th>
                                        <th>Pelanggan</th>
                                        <th>Layanan</th>
                                        <th>Berat</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cucianTerbaru as $cucian)
                                    <tr>
                                        <td><b>{{ $cucian->getNoOrder() }}</b></td>
                                        <td>
                                            <b>{{ $cucian->pelanggan->nama ?? 'N/A' }}</b><br>
                                            <small class="text-muted">{{ $cucian->pelanggan->no_telp ?? '-' }}</small>
                                        </td>
                                        <td>{{ $cucian->layanan->nama_layanan ?? '-' }}</td>
                                        <td>{{ number_format($cucian->total_berat, 1) }} Kg</td>
                                        <td>{{ $cucian->getFormattedTotalHarga() }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $cucian->getStatusBadge() }}">
                                                {{ $cucian->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $cucian->tgl_order ? $cucian->tgl_order->format('d/m/Y') : '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" 
                                               class="btn btn-sm btn-light"
                                               title="Lihat Detail">
                                                <i class="material-icons md-visibility"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="material-icons md-info"></i>
                            Belum ada data cucian
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan -->
        <div class="col-lg-4">
            <!-- Cucian Perlu Konfirmasi -->
            <div class="card mb-4">
                <header class="card-header bg-warning">
                    <h5 class="card-title text-white mb-0">
                        <i class="material-icons md-pending_actions"></i>
                        Perlu Konfirmasi
                    </h5>
                </header>
                <div class="card-body">
                    @if(isset($cucianMenungguKonfirmasi) && $cucianMenungguKonfirmasi->isNotEmpty())
                        @foreach($cucianMenungguKonfirmasi as $cucian)
                            <article class="itemlist mb-3 pb-3 border-bottom">
                                <div class="info">
                                    <h6 class="mb-1">{{ $cucian->getNoOrder() }}</h6>
                                    <p class="text-muted mb-1">
                                        <i class="material-icons md-person small"></i>
                                        {{ $cucian->pelanggan->nama ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            {{ $cucian->layanan->nama_layanan ?? '-' }} - {{ number_format($cucian->total_berat, 1) }} Kg
                                        </small>
                                    </p>
                                    <small class="text-muted">
                                        <i class="material-icons md-access_time small"></i>
                                        {{ $cucian->tgl_order ? $cucian->tgl_order->diffForHumans() : '-' }}
                                    </small>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('staff.status-cucian.index') }}" 
                                       class="btn btn-sm btn-warning w-100">
                                        <i class="material-icons md-check"></i> Konfirmasi
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="material-icons md-check_circle text-success" style="font-size: 48px;"></i>
                            <p class="text-muted mb-0">Tidak ada cucian yang perlu dikonfirmasi</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <header class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </header>
                <div class="card-body">
                    <a href="{{ route('staff.cucian.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="material-icons md-local_laundry_service"></i> Lihat Semua Cucian
                    </a>
                    <a href="{{ route('staff.status-cucian.index') }}" class="btn btn-outline-warning w-100 mb-2">
                        <i class="material-icons md-pending_actions"></i> Update Status Cucian
                    </a>
                    <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-outline-info w-100">
                        <i class="material-icons md-people"></i> Data Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection