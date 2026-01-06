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
                                <li>✅ Virtual Account (BCA, BNI, Mandiri, BRI)</li>
                                <li>✅ E-Wallet (GoPay, ShopeePay, OVO)</li>
                                <li>✅ QRIS</li>
                                <li>✅ Credit Card</li>
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

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    const button = this;
    button.disabled = true;
    button.innerHTML = '<i class="material-icons md-hourglass_empty"></i> Loading...';

    // Request snap token from server
    fetch('{{ route("payment.create-snap", $cucian->cucian_id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Open Midtrans Snap
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    
                    // UPDATE STATUS VIA AJAX ke Backend
                    fetch('{{ route("payment.update-status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pembayaran_id: {{ $pembayaran->pembayaran_id }},
                            transaction_id: result.order_id,
                            payment_type: result.payment_type || 'midtrans'
                        })
                    })
                    .then(response => response.json())
                    .then(updateData => {
                        console.log('Status update response:', updateData);
                        
                        if (updateData.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                html: `
                                    <p>Transaksi telah berhasil diproses</p>
                                    <p class="mb-0"><small>Order ID: ${result.order_id}</small></p>
                                `,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '{{ route("staff.pembayaran.index") }}';
                            });
                        } else {
                            // Jika gagal update, tetap kasih tau sukses tapi minta refresh manual
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pembayaran Berhasil',
                                text: 'Pembayaran berhasil, silakan refresh halaman untuk melihat status terbaru',
                                confirmButtonText: 'Refresh'
                            }).then(() => {
                                window.location.href = '{{ route("staff.pembayaran.index") }}';
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                        
                        // Tetap kasih tau pembayaran berhasil walau update gagal
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pembayaran Berhasil',
                            text: 'Pembayaran berhasil, silakan refresh halaman',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route("staff.pembayaran.index") }}';
                        });
                    });
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Menunggu pembayaran dari customer',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route("staff.pembayaran.index") }}';
                    });
                },
                onError: function(result) {
                    console.log('Payment Error:', result);
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal!',
                        text: 'Terjadi kesalahan saat memproses pembayaran',
                        confirmButtonText: 'OK'
                    });
                    button.disabled = false;
                    button.innerHTML = '<i class="material-icons md-payment"></i> Bayar Sekarang';
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    button.disabled = false;
                    button.innerHTML = '<i class="material-icons md-payment"></i> Bayar Sekarang';
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Gagal membuat transaksi',
                confirmButtonText: 'OK'
            });
            button.disabled = false;
            button.innerHTML = '<i class="material-icons md-payment"></i> Bayar Sekarang';
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan sistem',
            confirmButtonText: 'OK'
        });
        button.disabled = false;
        button.innerHTML = '<i class="material-icons md-payment"></i> Bayar Sekarang';
    });
});
</script>
@endsection