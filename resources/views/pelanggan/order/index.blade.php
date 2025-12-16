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

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('pelanggan.order.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option {{ request()->query('status') == 'menunggu' ? 'selected' : '' }} value="menunggu">Menunggu</option>
                            <option {{ request()->query('status') == 'proses' ? 'selected' : '' }} value="proses">Proses</option>
                            <option {{ request()->query('status') == 'selesai' ? 'selected' : '' }} value="selesai">Selesai</option>
                            <option {{ request()->query('status') == 'diambil' ? 'selected' : '' }} value="diambil">Diambil</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="paginate">
                            <option {{ request()->query('paginate') == 10 ? 'selected' : '' }} value="10">Show 10</option>
                            <option {{ request()->query('paginate') == 20 ? 'selected' : '' }} value="20">Show 20</option>
                            <option {{ request()->query('paginate') == 50 ? 'selected' : '' }} value="50">Show 50</option>
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
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Anda belum memiliki order
                    @if(request()->filled('status'))
                        dengan status "<strong>{{ request()->status }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Tanggal</th>
                                <th>Jenis Layanan</th>
                                <th>Berat (kg)</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>{{ $order->no_order }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y H:i') }}</td>
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
                                    @if($order->status_pembayaran == 'sudah_bayar')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.order.show', $order->id) }}" class="btn btn-sm btn-primary">
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
    </div>

    @if($orders->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $orders->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

@endsection