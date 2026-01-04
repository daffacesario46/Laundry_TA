@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Cucian</h2>
            <p>Order #{{ $cucian->getNoOrder() }}</p>
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
                            <p class="text-muted">{{ $cucian->getNoOrder() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Status:</strong></p>
                            <span class="badge {{ $cucian->getStatusBadge() }}">{{ $cucian->getStatusLabel() }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jenis Order:</strong></p>
                            <span class="badge {{ $cucian->jenis_order == 'online' ? 'bg-success' : 'bg-info' }}">
                                {{ ucfirst($cucian->jenis_order) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jenis Ambil:</strong></p>
                            <span class="badge {{ $cucian->jenis_ambil == 'diantar' ? 'bg-info' : 'bg-secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $cucian->jenis_ambil)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tanggal Order:</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($cucian->tgl_order)->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tanggal Selesai:</strong></p>
                            <p class="text-muted">{{ $cucian->tgl_selesai ? \Carbon\Carbon::parse($cucian->tgl_selesai)->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tanggal Diambil:</strong></p>
                            <p class="text-muted">{{ $cucian->tgl_diambil ? \Carbon\Carbon::parse($cucian->tgl_diambil)->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Estimasi Selesai:</strong></p>
                            <p class="text-muted">{{ $cucian->estimasi ? \Carbon\Carbon::parse($cucian->estimasi)->format('d M Y H:i') : '-' }}</p>
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
                                    <th>Jumlah/Berat</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cucian->detail as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->listHarga->nama_item }}</strong>
                                        @if($item->deskripsi)
                                            <br><small class="text-muted">{{ $item->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->berat_kg)
                                            {{ $item->berat_kg }} kg
                                        @else
                                            {{ $item->jumlah }} pcs
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->berat_kg && $item->listHarga->harga_kiloan)
                                            {{ $item->listHarga->getFormattedHargaKiloan() }}
                                        @else
                                            {{ $item->listHarga->getFormattedHargaSatuan() }}
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->getFormattedSubtotal() }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td><strong class="text-primary">{{ $cucian->getFormattedTotalHarga() }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Info Pembayaran -->
            @if($cucian->pembayaran)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Status Pembayaran:</strong></p>
                            <span class="badge {{ $cucian->pembayaran->getStatusBadge() }}">
                                {{ $cucian->pembayaran->getStatusLabel() }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Metode Bayar:</strong></p>
                            <p class="text-muted">{{ $cucian->pembayaran->getMetodeBayarLabel() }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jumlah Bayar:</strong></p>
                            <p class="text-muted">{{ $cucian->pembayaran->getFormattedJumlahBayar() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tanggal Bayar:</strong></p>
                            <p class="text-muted">{{ $cucian->pembayaran->tgl_bayar ? \Carbon\Carbon::parse($cucian->pembayaran->tgl_bayar)->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Informasi Customer -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-2"><strong>Nama Pelanggan:</strong></p>
                        <p class="text-muted">{{ $cucian->pelanggan->nama }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-2"><strong>Kategori:</strong></p>
                        <span class="badge {{ $cucian->pelanggan->isMember() ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($cucian->pelanggan->kategori_pelanggan) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="mb-2"><strong>No. Telepon:</strong></p>
                        <p class="text-muted">{{ $cucian->pelanggan->no_telp ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-2"><strong>No. WhatsApp:</strong></p>
                        <p class="text-muted">{{ $cucian->pelanggan->no_wa ?? '-' }}</p>
                    </div>

                    @if($cucian->pelanggan->alamat)
                    <div class="mb-3">
                        <p class="mb-2"><strong>Alamat:</strong></p>
                        <p class="text-muted">{{ $cucian->pelanggan->alamat }}</p>
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
                    @if($cucian->total_berat)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Berat:</span>
                        <strong>{{ $cucian->total_berat }} kg</strong>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span><strong>Total Harga:</strong></span>
                        <strong class="text-primary">{{ $cucian->getFormattedTotalHarga() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection