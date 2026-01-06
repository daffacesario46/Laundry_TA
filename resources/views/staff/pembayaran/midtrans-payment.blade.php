@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Proses Pembayaran - Midtrans</h2>
            <p>Order: <strong>{{ $cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('staff.pembayaran.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="material-icons md-receipt"></i> Detail Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>No. Order:</strong></div>
                        <div class="col-sm-8">{{ $cucian->getNoOrder() }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Pelanggan:</strong></div>
                        <div class="col-sm-8">{{ $cucian->pelanggan->nama ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>No. Telepon:</strong></div>
                        <div class="col-sm-8">{{ $cucian->pelanggan->no_telp ?? '-' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Layanan:</strong></div>
                        <div class="col-sm-8">{{ $cucian->layanan->nama_layanan ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Berat/Item:</strong></div>
                        <div class="col-sm-8">
                            @if($cucian->layanan && $cucian->layanan->jenis_cucian === 'kiloan')
                                {{ number_format($cucian->total_berat, 1) }} Kg
                            @else
                                {{ $cucian->total_item }} Item
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4"><strong>Total Pembayaran:</strong></div>
                        <div class="col-sm-8">
                            <h4 class="text-primary mb-0">{{ $cucian->getFormattedTotalHarga() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <div class="card shadow-lg">
                <div class="card-body text-center py-5">
                    <i class="material-icons md-payment text-primary mb-3" style="font-size: 80px;"></i>
                    <h3 class="mb-2">Proses Pembayaran</h3>
                    <p class="text-muted mb-4">
                        Klik tombol di bawah untuk membuka halaman pembayaran Midtrans
                    </p>
                    
                    <button type="button" 
                            id="pay-button" 
                            class="btn btn-primary btn-lg px-5 py-3 shadow">
                        <i class="material-icons md-payment"></i> Bayar Sekarang
                    </button>

                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="material-icons md-security small"></i>
                            Pembayaran aman melalui Midtrans Payment Gateway
                        </small>
                    </div>
                    
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <strong>Metode Pembayaran:</strong>
                            <ul class="list-unstyled mb-0 mt-2">
                                <li>âœ… Virtual Account (BCA, BNI, Mandiri, BRI)</li>
                                <li>âœ… E-Wallet (GoPay, ShopeePay, OVO)</li>
                                <li>âœ… QRIS</li>
                                <li>âœ… Credit Card</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $pembayaran->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('staff.pembayaran.show', $pembayaran->pembayaran_id) }}";
            },
            onPending: function(result){
                alert('Menunggu pembayaran Anda!');
            },
            onError: function(result){
                alert('Pembayaran gagal!');
            }
        });
    };
</script>
@endsection