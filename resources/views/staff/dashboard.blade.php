@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Dashboard Staff</h2>
            <p>Selamat datang di panel staff Washwes</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-primary-light">
                        <i class="text-primary material-icons md-local_laundry_service"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Cucian</h6>
                        <span>{{ $totalCucian }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-warning-light">
                        <i class="text-warning material-icons md-pending_actions"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Menunggu</h6>
                        <span>{{ $cucianMenunggu }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-info-light">
                        <i class="text-info material-icons md-autorenew"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Sedang Proses</h6>
                        <span>{{ $cucianProses }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-success-light">
                        <i class="text-success material-icons md-check_circle"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Selesai</h6>
                        <span>{{ $cucianSelesai }}</span>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <header class="card-header">
                    <h4 class="card-title">Cucian Terbaru</h4>
                </header>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Order</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Berat (Kg)</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cucianTerbaru as $cucian)
                                <tr>
                                    <td><b>{{ $cucian->no_order }}</b></td>
                                    <td>
                                        <b>{{ $cucian->nama_pelanggan }}</b><br>
                                        <small class="text-muted">{{ $cucian->no_telp }}</small>
                                    </td>
                                    <td>{{ $cucian->jenis_layanan }}</td>
                                    <td>{{ $cucian->berat }} Kg</td>
                                    <td>
                                        @if($cucian->status == 'menunggu')
                                            <span class="badge rounded-pill alert-warning">Menunggu</span>
                                        @elseif($cucian->status == 'proses')
                                            <span class="badge rounded-pill alert-info">Proses</span>
                                        @elseif($cucian->status == 'selesai')
                                            <span class="badge rounded-pill alert-success">Selesai</span>
                                        @elseif($cucian->status == 'diambil')
                                            <span class="badge rounded-pill alert-secondary">Diambil</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($cucian->created_at)->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.cucian.show', $cucian->id) }}" class="btn btn-sm btn-light">
                                            <i class="material-icons md-visibility"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data cucian</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <header class="card-header">
                    <h4 class="card-title">Statistik Pelanggan</h4>
                </header>
                <div class="card-body">
                    <article class="icontext mb-3">
                        <span class="icon icon-sm rounded-circle bg-primary-light">
                            <i class="text-primary material-icons md-people"></i>
                        </span>
                        <div class="text">
                            <h6 class="mb-1">Total Pelanggan</h6>
                            <span>{{ $totalPelanggan }} Pelanggan</span>
                        </div>
                    </article>
                    <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-primary w-100">
                        <i class="material-icons md-people"></i> Lihat Semua Pelanggan
                    </a>
                </div>
            </div>

            <div class="card mb-4">
                <header class="card-header bg-warning">
                    <h4 class="card-title text-white">Perlu Konfirmasi</h4>
                </header>
                <div class="card-body">
                    @forelse($cucianMenungguKonfirmasi as $cucian)
                    <article class="itemlist mb-3">
                        <div class="info">
                            <h6 class="mb-0">{{ $cucian->no_order }}</h6>
                            <p class="text-muted mb-1">{{ $cucian->nama_pelanggan }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($cucian->created_at)->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('staff.status-cucian.index') }}" class="btn btn-sm btn-warning">
                            Konfirmasi
                        </a>
                    </article>
                    @empty
                    <p class="text-center text-muted">Tidak ada cucian yang perlu dikonfirmasi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection