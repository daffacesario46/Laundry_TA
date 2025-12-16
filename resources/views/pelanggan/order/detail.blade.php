@extends('pelanggan.layouts.app')

@section('title', 'Detail Order')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Detail Order</h2>
            <p>Informasi lengkap order Anda</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Order -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Order</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">No. Order</p>
                            <h6>{{ $order->no_order }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tanggal Order</p>
                            <h6>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y, H:i') }}</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Jenis Layanan</p>
                            <h6>{{ $order->jenis_layanan }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Berat Cucian</p>
                            <h6>{{ $order->berat }} kg</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Status Cucian</p>
                            @if($order->status == 'proses')
                                <span class="badge rounded-pill alert-info">Proses</span>
                            @elseif($order->status == 'selesai')
                                <span class="badge rounded-pill alert-success">Selesai</span>
                            @elseif($order->status == 'menunggu')
                                <span class="badge rounded-pill alert-warning">Menunggu</span>
                            @else
                                <span class="badge rounded-pill alert-secondary">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Status Pembayaran</p>
                            @if($order->status_pembayaran == 'belum_bayar')
                                <span class="badge rounded-pill alert-danger">Belum Bayar</span>
                            @else
                                <span class="badge rounded-pill alert-success">Lunas</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted">Catatan</p>
                            <p>{{ $order->catatan ?: 'Tidak ada catatan khusus' }}</p>
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
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Dibuat</h6>
                                <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->status_pembayaran == 'belum_bayar')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Pembayaran</h6>
                                <p class="text-muted mb-0">Status saat ini</p>
                            </div>
                        </div>
                        <div class="timeline-item opacity-50">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pembayaran Dikonfirmasi</h6>
                                <p class="text-muted mb-0">Belum selesai</p>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pembayaran Dikonfirmasi</h6>
                                <p class="text-muted mb-0">Pembayaran berhasil</p>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'proses')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Sedang Diproses</h6>
                                <p class="text-muted mb-0">Status saat ini</p>
                            </div>
                        </div>
                        @elseif($order->status == 'selesai')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                <p class="text-muted mb-0">Siap diambil/diantar</p>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item opacity-50">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diproses</h6>
                                <p class="text-muted mb-0">Belum dimulai</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $order->jenis_layanan }}</span>
                        <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Berat ({{ $order->berat }} kg)</span>
                        <span>-</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total Bayar</strong>
                        <strong class="text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Tombol Bayar - Hanya muncul jika belum bayar -->
                    @if($order->status_pembayaran == 'belum_bayar')
                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
                        <i class="material-icons md-payment"></i> Bayar Sekarang
                    </button>

                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="material-icons md-info"></i>
                            Pembayaran harus diselesaikan dalam 24 jam
                        </small>
                    </div>
                    @else
                    <div class="alert alert-success mb-0">
                        <i class="material-icons md-check_circle"></i>
                        <strong>Pembayaran Lunas</strong>
                        <br>
                        <small>Metode: {{ ucfirst($order->metode_pembayaran) }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Pickup/Delivery -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengambilan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1 text-muted">Metode Pengambilan</p>
                        <h6>{{ $order->jenis_pengambilan == 'diantar' ? 'Antar Jemput' : 'Ambil Sendiri' }}</h6>
                    </div>
                    
                    @if($order->jenis_pengambilan == 'diantar' && $order->alamat_pengambilan)
                    <div class="mb-3">
                        <p class="mb-1 text-muted">Alamat</p>
                        <p class="mb-0">{{ $order->alamat_pengambilan }}</p>
                    </div>
                    @endif

                    <div class="mb-0">
                        <p class="mb-1 text-muted">Estimasi Selesai</p>
                        <h6 class="text-success">{{ \Carbon\Carbon::parse($order->estimasi_selesai)->format('d M Y, H:i') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="material-icons md-shopping_cart" style="font-size: 64px; color: #0d6efd;"></i>
                    <h4 class="mt-3">Order #{{ $order->no_order }}</h4>
                    <p class="text-muted">{{ $order->jenis_layanan }}</p>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Pembayaran</strong>
                            <strong class="text-primary h5 mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran Info -->
                <div class="alert alert-info">
                    <h6 class="mb-2">
                        <i class="material-icons md-info"></i> Metode Pembayaran
                    </h6>
                    <small>
                        Anda akan diarahkan ke halaman pembayaran Midtrans yang aman. 
                        Pilihan pembayaran meliputi:
                    </small>
                    <ul class="mb-0 mt-2" style="font-size: 13px;">
                        <li>Transfer Bank (BCA, Mandiri, BNI, dll)</li>
                        <li>E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
                        <li>Kartu Kredit/Debit</li>
                        <li>Minimarket (Alfamart, Indomaret)</li>
                    </ul>
                </div>

                <!-- Syarat & Ketentuan -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="agreeTerm">
                    <label class="form-check-label" for="agreeTerm">
                        <small>Saya setuju dengan <a href="#">syarat dan ketentuan</a> yang berlaku</small>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnProsesPayment">
                    <i class="material-icons md-payment"></i> Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS untuk Timeline -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -25px;
    top: 10px;
    bottom: -20px;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e0e0e0;
}

.timeline-content h6 {
    font-size: 14px;
    margin-bottom: 4px;
}

.timeline-content p {
    font-size: 12px;
}
</style>

<!-- JavaScript untuk Pembayaran -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnPayment = document.getElementById('btnProsesPayment');
    
    if (btnPayment) {
        btnPayment.addEventListener('click', function() {
            const checkbox = document.getElementById('agreeTerm');
            
            if (!checkbox.checked) {
                alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu');
                return;
            }
            
            // TODO: Nanti integrasikan dengan Midtrans Snap
            // Contoh flow:
            // 1. Kirim request ke backend untuk generate snap token
            // 2. Backend hit Midtrans API
            // 3. Dapat snap token
            // 4. Munculkan popup Midtrans
            
            alert('Pembayaran akan diproses...\n\nNanti akan muncul popup Midtrans di sini');
            
            // Simulasi - Nanti ganti dengan:
            // snap.pay('SNAP_TOKEN_DARI_BACKEND');
        });
    }
});
</script>
@endsection