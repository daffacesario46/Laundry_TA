@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Validasi Pembayaran Transfer</h2>
            <p>Order: <strong>{{ $pembayaran->cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('staff.pembayaran.index') }}" class="btn btn-light">
                <i class="icon material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Bukti Transfer --}}
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Bukti Transfer</h5>
                </div>
                <div class="card-body text-center">
                    @if($pembayaran->bukti_bayar)
                        <img src="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                             alt="Bukti Transfer" 
                             class="img-fluid rounded border"
                             style="max-height: 500px; cursor: pointer;"
                             onclick="window.open('{{ Storage::url($pembayaran->bukti_bayar) }}', '_blank')">
                        <p class="mt-3 text-muted">
                            <small>Klik gambar untuk memperbesar</small>
                        </p>
                    @else
                        <div class="alert alert-warning">
                            <i class="icon material-icons md-warning"></i>
                            Bukti transfer belum diupload
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Validasi --}}
        <div class="col-lg-6">
            {{-- Info Pembayaran --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="40%">No. Order</td>
                            <td><strong>{{ $pembayaran->cucian->getNoOrder() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td><strong>{{ $pembayaran->cucian->pelanggan->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Harga</td>
                            <td><strong class="text-primary">{{ $pembayaran->getFormattedJumlahBayar() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Metode</td>
                            <td>
                                <span class="badge rounded-pill alert-info">
                                    {{ $pembayaran->getMetodeBayarLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="badge rounded-pill {{ $pembayaran->getStatusBadge() }}">
                                    {{ $pembayaran->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Upload</td>
                            <td>{{ $pembayaran->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Form Action --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Validasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.pembayaran.validate', $pembayaran->pembayaran_id) }}" method="POST" id="validateForm">
                        @csrf
                        @method('PUT')
                    

                        <div class="d-grid gap-2">
                            <button type="button" 
                                    class="btn btn-success btn-lg" 
                                    onclick="confirmValidation('approve')">
                                <i class="icon material-icons md-check_circle"></i>
                                Setujui Pembayaran
                            </button>
                            
                            <button type="button" 
                                    class="btn btn-danger btn-lg" 
                                    onclick="confirmValidation('reject')">
                                <i class="icon material-icons md-cancel"></i>
                                Tolak Pembayaran
                            </button>
                        </div>

                        <input type="hidden" name="action" id="action-input">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmValidation(action) {
    const actionText = action === 'approve' ? 'menyetujui' : 'menolak';
    const actionIcon = action === 'approve' ? 'success' : 'error';
    
    Swal.fire({
        title: `Konfirmasi ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}`,
        text: `Apakah Anda yakin ingin ${actionText} pembayaran ini?`,
        icon: actionIcon,
        showCancelButton: true,
        confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, ' + actionText.charAt(0).toUpperCase() + actionText.slice(1),
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('action-input').value = action;
            document.getElementById('validateForm').submit();
        }
    });
}
</script>
@endsection