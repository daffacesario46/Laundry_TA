@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Cucian</h2>
            <p>Order #{{ $cucian->no_order }}</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Order -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Informasi Order</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>No. Order:</strong></p>
                            <p class="text-muted">{{ $cucian->no_order }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Status:</strong></p>
                            <span class="badge bg-success">{{ ucfirst($cucian->status) }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jenis Order:</strong></p>
                            <span class="badge bg-primary">{{ ucfirst($cucian->jenis_order) }}</span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jenis Ambil:</strong></p>
                            <span class="badge {{ $cucian->jenis_ambil == 'diantar' ? 'bg-info' : 'bg-secondary' }}">
                                {{ ucfirst($cucian->jenis_ambil) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Waktu Diterima:</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($cucian->wkt_diterima)->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Waktu Diambil:</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($cucian->wkt_diambil)->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($cucian->catatan)
                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="mb-2"><strong>Catatan:</strong></p>
                            <div class="alert alert-light">
                                <i class="material-icons md-notes"></i> {{ $cucian->catatan }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Detail Item Cucian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Layanan</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cucian->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->nama_item }}</strong></td>
                                    <td>{{ $item->layanan }}</td>
                                    <td>{{ $item->jumlah }} pcs</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                    <td><strong class="text-primary">Rp {{ number_format($cucian->total_harga, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Customer -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-2"><strong>Nama User:</strong></p>
                        <p class="text-muted">{{ $cucian->user->nama }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-2"><strong>Atas Nama:</strong></p>
                        <p class="text-muted">{{ $cucian->atas_nama }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-2"><strong>No. Telepon:</strong></p>
                        <p class="text-muted">{{ $cucian->user->telp }}</p>
                    </div>

                    @if($cucian->jenis_ambil == 'diantar')
                    <div class="mb-3">
                        <p class="mb-2"><strong>Alamat Pengambilan:</strong></p>
                        <p class="text-muted">{{ $cucian->alamat_ambil }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Item:</span>
                        <strong>{{ $cucian->total_item }} pcs</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Berat:</span>
                        <strong>{{ $cucian->total_berat }} kg</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span><strong>Total Harga:</strong></span>
                        <strong class="text-primary">Rp {{ number_format($cucian->total_harga, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection