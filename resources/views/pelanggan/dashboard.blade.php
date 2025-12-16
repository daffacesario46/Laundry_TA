@extends('pelanggan.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Dashboard Pelanggan</h2>
            <p>Selamat datang di dashboard Anda</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Buat Order Baru
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle alert-primary">
                        <i class="material-icons md-shopping_cart"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Total Order</h6>
                        <span>{{ $stats['total_order'] }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle alert-warning">
                        <i class="material-icons md-autorenew"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Sedang Proses</h6>
                        <span>{{ $stats['sedang_proses'] }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle alert-success">
                        <i class="material-icons md-check_circle"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Selesai</h6>
                        <span>{{ $stats['selesai'] }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle alert-info">
                        <i class="material-icons md-inventory"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Menunggu Diambil</h6>
                        <span>{{ $stats['menunggu_diambil'] }}</span>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Order Terbaru -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Order Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Jenis Layanan</th>
                            <th>Berat</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>{{ $order->no_order }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y') }}</td>
                            <td>{{ $order->jenis_layanan }}</td>
                            <td>{{ $order->berat }} kg</td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'menunggu')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($order->status == 'proses')
                                    <span class="badge bg-info">Proses</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Diambil</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.order.index') }}" class="btn btn-sm btn-light">
                                    <i class="material-icons md-visibility"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada order</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection