@extends('pelanggan.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Detail Order</h2>
            <p>Informasi lengkap order {{ $order->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
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

    <div class="row">
        <!-- Informasi Order -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Order</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">No. Order</p>
                            <h6>{{ $order->getNoOrder() }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Tanggal Order</p>
                            <h6>{{ $order->tgl_order->format('d M Y, H:i') }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Status</p>
                            <h6>
                                <span class="badge rounded-pill {{ $order->getStatusBadge() }}">
                                    {{ $order->getStatusLabel() }}
                                </span>
                            </h6>
                        </div>
                    </div>

                    <hr>

                    {{-- ✅ BARIS DENGAN 4 KOLOM (SAMA SEPERTI STAFF) --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Order</p>
                            <p class="fw-bold">{{ ucfirst($order->jenis_order) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Cucian</p>
                            <p class="fw-bold">
                                @if($order->layanan)
                                    <span class="badge {{ $order->layanan->jenis_cucian == 'kiloan' ? 'alert-info' : 'alert-success' }}">
                                        {{ ucfirst($order->layanan->jenis_cucian) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        
                        {{-- ✅ TAMBAHAN BARU: Metode Cuci --}}
                        <div class="col-md-3">
                            <p class="text-muted mb-1">
                                <i class="material-icons md-local_laundry_service" style="font-size: 14px; vertical-align: middle;"></i>
                                Metode Cuci
                            </p>
                            <p class="fw-bold">
                                @if($order->metode_cuci == 'express')
                                    <span class="badge bg-warning text-dark">
                                        <i class="material-icons md-flash_on" style="font-size: 12px; vertical-align: middle;"></i>
                                        Express (+50%)
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Normal</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Pengambilan</p>
                            <p class="fw-bold">{{ $order->jenis_ambil == 'diantar' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Jenis Layanan</p>
                            <h6>{{ $order->layanan->nama_layanan ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Estimasi Selesai</p>
                            <h6 class="text-success">{{ $order->estimasi ? $order->estimasi->format('d M Y, H:i') : '-' }}</h6>
                        </div>
                    </div>

                    @if($order->catatan)
                    <hr>
                    <div>
                        <p class="mb-1 text-muted">
                            <i class="material-icons md-note"></i> Catatan
                        </p>
                        <div class="alert alert-info">
                            {{ $order->catatan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SECTION PEMBAYARAN -->
            @if($order->hasPembayaran())
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="material-icons md-payment"></i> 
                        Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $pembayaran = $order->pembayaran;
                    @endphp

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Metode Pembayaran</small>
                            <h6>
                                <span class="badge rounded-pill {{ $pembayaran->metode_bayar == 'cash' ? 'alert-success' : 'alert-info' }}">
                                    <i class="material-icons md-{{ $pembayaran->metode_bayar == 'cash' ? 'money' : 'credit_card' }}"></i>
                                    {{ $pembayaran->getMetodeBayarLabel() }}
                                </span>
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Status Pembayaran</small>
                            <h6>
                                <span class="badge rounded-pill {{ $pembayaran->getStatusBadge() }}">
                                    <i class="material-icons md-{{ $pembayaran->isLunas() ? 'check_circle' : 'schedule' }}"></i>
                                    {{ $pembayaran->getStatusLabel() }}
                                </span>
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Total Bayar</small>
                            <h5 class="text-primary mb-0">{{ $pembayaran->getFormattedJumlahBayar() }}</h5>
                        </div>

                        @if($pembayaran->tgl_bayar)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Tanggal Bayar</small>
                            <h6>{{ $pembayaran->tgl_bayar->format('d M Y, H:i') }}</h6>
                        </div>
                        @endif

                        @if($pembayaran->catatan)
                        <div class="col-12">
                            <small class="text-muted">Catatan</small>
                            <div class="alert alert-light">
                                <i class="material-icons md-info"></i>
                                {{ $pembayaran->catatan }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr>

                    {{-- Action Buttons for Payment --}}
                    <div class="d-flex gap-2">
                        @if($pembayaran->status_bayar === 'belum' && $pembayaran->metode_bayar === 'transfer')
                            @if($order->pembayaran && $order->pembayaran->status_bayar !== 'lunas')
                                <a href="{{ route('pelanggan.order.pay-midtrans', $order->cucian_id) }}" 
                                class="btn btn-success">
                                    <i class="fas fa-credit-card"></i> Bayar dengan Midtrans
                                </a>
                            @endif
                            @if($pembayaran->bukti_bayar)
                                {{-- Already uploaded, show status --}}
                                <div class="alert alert-warning w-100 mb-0">
                                    <i class="material-icons md-schedule"></i>
                                    <strong>Menunggu Validasi Staff</strong>
                                    <p class="mb-2 mt-2 small">Bukti pembayaran Anda sedang diverifikasi oleh staff.</p>
                                    <a href="{{ route('pelanggan.order.show-upload-bukti', $order->cucian_id) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="material-icons md-refresh"></i>
                                        Update Bukti Pembayaran
                                    </a>
                                </div>
                            @else
                                {{-- Not uploaded yet --}}
                                <a href="{{ route('pelanggan.order.show-upload-bukti', $order->cucian_id) }}" 
                                   class="btn btn-primary btn-lg w-100">
                                    <i class="material-icons md-cloud_upload"></i>
                                    Upload Bukti Pembayaran
                                </a>
                            @endif
                        @elseif($pembayaran->status_bayar === 'lunas')
                            {{-- Payment completed --}}
                            <div class="alert alert-success w-100 mb-0">
                                <i class="material-icons md-check_circle"></i>
                                <strong>Pembayaran Lunas!</strong>
                                <p class="mb-0 mt-1 small">Terima kasih, pembayaran Anda telah dikonfirmasi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
                {{-- Pembayaran belum dibuat --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="material-icons md-info"></i>
                            <strong>Informasi Pembayaran</strong>
                            <p class="mb-0 mt-1">Informasi pembayaran akan tersedia setelah order Anda divalidasi oleh staff.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Detail Item -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Item Cucian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Tipe</th>
                                    <th>Jumlah/Berat</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->detail as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->listHarga->nama_item ?? '-' }}</strong>
                                        @if($detail->deskripsi)
                                            <br><small class="text-muted">{{ $detail->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            <span class="badge alert-info">Kiloan</span>
                                        @else
                                            <span class="badge alert-success">Satuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            {{ number_format($detail->berat_kg, 1) }} Kg
                                        @else
                                            {{ $detail->jumlah }} pcs
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            Rp {{ number_format($detail->listHarga->harga_kiloan ?? 0, 0, ',', '.') }}/Kg
                                        @else
                                            Rp {{ number_format($detail->listHarga->harga_satuan ?? 0, 0, ',', '.') }}/pcs
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            @if($detail->berat_kg)
                                                Rp {{ number_format($detail->berat_kg * ($detail->listHarga->harga_kiloan ?? 0), 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($detail->jumlah * ($detail->listHarga->harga_satuan ?? 0), 0, ',', '.') }}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada detail item</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th>
                                        <h5 class="text-primary mb-0">{{ $order->getFormattedTotalHarga() }}</h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Item:</strong> {{ $order->total_item }} item</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Berat:</strong> {{ $order->total_berat ? number_format($order->total_berat, 1) . ' Kg' : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Timeline Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Dibuat</h6>
                                <p class="text-muted small mb-0">{{ $order->tgl_order->format('d M Y, H:i') }}</p>
                                <span class="badge bg-primary mt-1">Order Masuk</span>
                            </div>
                        </div>

                        @if($order->status_cucian == 'diproses' || $order->status_cucian == 'selesai' || $order->status_cucian == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Sedang Diproses</h6>
                                <span class="badge bg-info mt-1">Proses</span>
                            </div>
                        </div>
                        @endif

                        @if($order->status_cucian == 'selesai' || $order->status_cucian == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                @if($order->tgl_selesai)
                                <p class="text-muted small mb-0">{{ $order->tgl_selesai->format('d M Y, H:i') }}</p>
                                @endif
                                <span class="badge bg-success mt-1">Selesai</span>
                            </div>
                        </div>
                        @endif

                        @if($order->status_cucian == 'diambil')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diambil</h6>
                                @if($order->tgl_diambil)
                                <p class="text-muted small mb-0">{{ $order->tgl_diambil->format('d M Y, H:i') }}</p>
                                @endif
                                <span class="badge bg-secondary mt-1">Diambil</span>
                            </div>
                        </div>
                        @endif

                        @if($order->status_cucian == 'menunggu')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Konfirmasi</h6>
                                <span class="badge bg-warning mt-1">Menunggu</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Ringkasan Order -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Ringkasan Order</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ $order->getFormattedTotalHarga() }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong class="text-primary">{{ $order->getFormattedTotalHarga() }}</strong>
                    </div>

                    @if($order->status_cucian == 'menunggu')
                        <button class="btn btn-danger w-100" onclick="cancelOrder()">
                            <i class="material-icons md-cancel"></i> Batalkan Order
                        </button>
                    @endif
                </div>
            </div>

            <!-- Info Estimasi -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengambilan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Metode</small>
                        <p class="mb-0"><strong>{{ $order->jenis_ambil == 'diantar' ? 'Diantar' : 'Ambil Sendiri' }}</strong></p>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted">Estimasi Selesai</small>
                        <p class="mb-0"><strong class="text-success">{{ $order->estimasi ? $order->estimasi->format('d M Y, H:i') : '-' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Cancel Order -->
<form id="cancel-form" action="{{ route('pelanggan.order.cancel', $order->cucian_id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Styles -->
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
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cancelOrder() {
    Swal.fire({
        title: 'Batalkan Order?',
        text: 'Order {{ $order->getNoOrder() }} akan dibatalkan. Tindakan ini tidak dapat diurungkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form').submit();
        }
    });
}
</script>
@endsection