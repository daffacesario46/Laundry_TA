@extends('pelanggan.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Proses Pembayaran - Midtrans</h2>
                <p class="text-muted">Order: <strong>{{ $order->getNoOrder() }}</strong></p>
            </div>
            <div>
                <a href="{{ route('pelanggan.order.detail', $order->cucian_id) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Order Summary -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-receipt"></i> Detail Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>No. Order:</strong></div>
                        <div class="col-sm-8">{{ $order->getNoOrder() }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Pelanggan:</strong></div>
                        <div class="col-sm-8">{{ $order->pelanggan->nama ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>No. Telepon:</strong></div>
                        <div class="col-sm-8">{{ $order->pelanggan->no_telp ?? '-' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Layanan:</strong></div>
                        <div class="col-sm-8">{{ $order->layanan->nama_layanan ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Berat/Item:</strong></div>
                        <div class="col-sm-8">
                            @if($order->layanan && $order->layanan->jenis_cucian === 'kiloan')
                                {{ number_format($order->total_berat, 1) }} Kg
                            @else
                                {{ $order->total_item }} Item
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4"><strong>Total Pembayaran:</strong></div>
                        <div class="col-sm-8">
                            <h4 class="text-primary mb-0">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <div class="card shadow-lg">
                <div class="card-body text-center py-5">
                    <i class="fas fa-credit-card text-primary mb-3" style="font-size: 80px;"></i>
                    <h3 class="mb-2">Proses Pembayaran</h3>
                    <p class="text-muted mb-4">
                        Klik tombol di bawah untuk membuka halaman pembayaran Midtrans
                    </p>
                    
                    <button type="button" 
                            id="pay-button" 
                            class="btn btn-primary btn-lg px-5 py-3 shadow">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>

                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i>
                            Pembayaran aman melalui Midtrans Payment Gateway
                        </small>
                    </div>
                    
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Metode Pembayaran yang Tersedia:</strong>
                            <ul class="list-unstyled mb-0 mt-3 text-left">
                                <li class="mb-2">✅ <strong>Virtual Account</strong> (BCA, BNI, Mandiri, BRI, Permata)</li>
                                <li class="mb-2">✅ <strong>E-Wallet</strong> (GoPay, ShopeePay, OVO)</li>
                                <li class="mb-2">✅ <strong>QRIS</strong> (Scan untuk bayar)</li>
                                <li class="mb-2">✅ <strong>Kartu Kredit/Debit</strong> (Visa, Mastercard, JCB)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    (function() {
        'use strict';
        
        // Tunggu sampai halaman ter-load penuh
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPayment);
        } else {
            initPayment();
        }
        
        function initPayment() {
            var payButton = document.getElementById('pay-button');
            var snapToken = '{{ $snapToken }}';
            
            console.log('Payment initialized');
            console.log('Snap Token:', snapToken ? 'Available' : 'Missing');
            console.log('Snap Object:', typeof snap !== 'undefined' ? 'Loaded' : 'Not Loaded');
            
            if (!payButton) {
                console.error('Pay button not found');
                return;
            }
            
            if (!snapToken) {
                alert('Error: Token pembayaran tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Event listener untuk button
            payButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Pay button clicked');
                
                // Cek apakah Snap sudah ter-load
                if (typeof snap === 'undefined') {
                    alert('Sistem pembayaran belum siap. Silakan refresh halaman.');
                    console.error('Snap.js not loaded');
                    return;
                }
                
                // Disable button dan ubah text
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                
                // Panggil Midtrans Snap
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = '{{ route("pelanggan.order.detail", $order->cucian_id) }}?payment=success';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        alert('Menunggu pembayaran Anda!');
                        window.location.href = '{{ route("pelanggan.order.detail", $order->cucian_id) }}?payment=pending';
                    },
                    onError: function(result) {
                        console.error('Payment error:', result);
                        alert('Pembayaran gagal! Silakan coba lagi.');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
                    }
                });
            });
            
            console.log('Event listener attached successfully');
        }
    })();
</script>
@endsection
