@extends('pelanggan.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Dashboard Pelanggan</h2>
            <p>Selamat datang, <strong>{{ $pelanggan->nama ?? 'Pelanggan' }}</strong></p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Buat Order Baru
            </a>
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="material-icons md-info"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-primary-light">
                        <i class="text-primary material-icons md-shopping_cart"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Order</h6>
                        <span class="h5">{{ $stats['total_order'] ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Semua order</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-warning-light">
                        <i class="text-warning material-icons md-autorenew"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Sedang Proses</h6>
                        <span class="h5">{{ $stats['sedang_proses'] ?? 0 }}</span>
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
                        <span class="h5">{{ $stats['selesai'] ?? 0 }}</span>
                        <p class="text-muted mb-0 small">Siap diambil</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-info-light">
                        <i class="text-info material-icons md-payments"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Belanja</h6>
                        <span class="h6">Rp {{ number_format($stats['total_spending'] ?? 0, 0, ',', '.') }}</span>
                        <p class="text-muted mb-0 small">Lifetime value</p>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Terbaru -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="material-icons md-info" style="font-size: 48px;"></i>
                            <p class="mb-0 mt-2">Belum ada order. Yuk mulai order pertamamu!</p>
                            <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary mt-3">
                                <i class="material-icons md-add"></i> Buat Order Sekarang
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Order</th>
                                        <th>Tanggal</th>
                                        <th>Layanan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong>{{ $order->getNoOrder() }}</strong></td>
                                        <td>{{ $order->tgl_order->format('d M Y') }}</td>
                                        <td>{{ $order->layanan->nama_layanan ?? '-' }}</td>
                                        <td>{{ $order->getFormattedTotalHarga() }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $order->getStatusBadge() }}">
                                                {{ $order->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('pelanggan.order.show', $order->cucian_id) }}" 
                                               class="btn btn-sm btn-light" title="Lihat Detail">
                                                <i class="material-icons md-visibility"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-outline-primary">
                                Lihat Semua Order <i class="material-icons md-arrow_forward"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info Profile -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title text-white mb-0">Profile Saya</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($pelanggan && $pelanggan->foto)
                            <img src="{{ Storage::url($pelanggan->foto) }}" 
                                 class="rounded-circle mb-2" 
                                 width="80" 
                                 height="80" 
                                 style="object-fit: cover;"
                                 alt="{{ $pelanggan->nama }}">
                        @else
                            <div class="mx-auto mb-2 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" 
                                 style="width: 80px; height: 80px;">
                                <i class="material-icons" style="font-size: 40px;">person</i>
                            </div>
                        @endif
                        <h6 class="mb-0">{{ $pelanggan->nama ?? 'Pelanggan' }}</h6>
                        <small class="text-muted">
                            @if($pelanggan->kategori_pelanggan == 'online')
                                <span class="badge bg-success">Pelanggan Online</span>
                            @else
                                <span class="badge bg-secondary">Pelanggan Offline</span>
                            @endif
                        </small>
                    </div>

                    @if($pelanggan && $pelanggan->user)
                    <div class="mb-2">
                        <small class="text-muted">Email:</small><br>
                        <strong>{{ $pelanggan->user->email }}</strong>
                    </div>
                    @endif

                    @if($pelanggan)
                    <div class="mb-2">
                        <small class="text-muted">Telepon:</small><br>
                        <strong>{{ $pelanggan->no_telp ?? '-' }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Alamat:</small><br>
                        <strong>{{ Str::limit($pelanggan->alamat ?? '-', 50) }}</strong>
                    </div>
                    @endif

                    <a href="{{ route('pelanggan.profile.index') }}" class="btn btn-outline-primary w-100">
                        <i class="material-icons md-edit"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Perlu Perhatian -->
            @if(isset($needsAttention) && $needsAttention->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="card-title text-white mb-0">
                        <i class="material-icons md-notifications"></i>
                        Perlu Perhatian
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($needsAttention as $order)
                        <div class="alert alert-light border mb-2">
                            <strong>{{ $order->getNoOrder() }}</strong><br>
                            @if($order->status_cucian == 'selesai')
                                <small class="text-success">
                                    <i class="material-icons md-check_circle small"></i>
                                    Cucian sudah selesai, siap diambil!
                                </small>
                            @elseif($order->pembayaran && $order->pembayaran->status_bayar == 'belum')
                                <small class="text-danger">
                                    <i class="material-icons md-payment small"></i>
                                    Menunggu pembayaran
                                </small>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('pelanggan.order.show', $order->cucian_id) }}" 
                                   class="btn btn-sm btn-warning w-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary w-100 mb-2">
                        <i class="material-icons md-add"></i> Buat Order Baru
                    </a>
                    <a href="{{ route('pelanggan.order.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="material-icons md-list"></i> Riwayat Order
                    </a>
                    <a href="{{ route('tracking.index') }}" class="btn btn-outline-info w-100">
                        <i class="material-icons md-search"></i> Lacak Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection