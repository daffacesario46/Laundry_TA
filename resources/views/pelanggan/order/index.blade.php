@extends('pelanggan.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Riwayat Order</h2>
            <p>Lihat semua order yang pernah Anda buat</p>
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

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('pelanggan.order.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="paginate">
                            <option value="10" {{ request('paginate') == 10 ? 'selected' : '' }}>Show 10</option>
                            <option value="20" {{ request('paginate') == 20 ? 'selected' : '' }}>Show 20</option>
                            <option value="50" {{ request('paginate') == 50 ? 'selected' : '' }}>Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Filter
                        </button>
                    </div>
                    @if(request()->anyFilled(['status', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="material-icons md-inbox" style="font-size: 64px; opacity: 0.5;"></i>
                    <h5 class="mt-3">Belum Ada Order</h5>
                    <p class="text-muted">Anda belum memiliki order
                    @if(request()->filled('status'))
                        dengan status "<strong>{{ request('status') }}</strong>"
                    @endif
                    </p>
                    <a href="{{ route('pelanggan.order.create') }}" class="btn btn-primary mt-2">
                        <i class="material-icons md-add"></i> Buat Order Pertama
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
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Status Cucian</th>
                                <th>Pembayaran</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>{{ $order->getNoOrder() }}</strong></td>
                                <td>{{ $order->tgl_order->format('d M Y H:i') }}</td>
                                <td>{{ $order->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ $order->total_item }} item</td>
                                <td>{{ $order->getFormattedTotalHarga() }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $order->getStatusBadge() }}">
                                        {{ $order->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->pembayaran)
                                        @if($order->pembayaran->status_bayar == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pelanggan.order.show', $order->cucian_id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="material-icons md-visibility"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($orders->hasPages())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} data</p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-end">
                            {{ $orders->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection