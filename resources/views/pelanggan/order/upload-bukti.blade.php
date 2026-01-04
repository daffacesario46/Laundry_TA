@extends('pelanggan.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Upload Bukti Pembayaran</h2>
            <p>Order {{ $order->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.show', $order->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="material-icons md-cloud_upload"></i> 
                        Form Upload Bukti Transfer
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->pembayaran->bukti_bayar)
                        <div class="alert alert-info mb-4">
                            <i class="material-icons md-info"></i>
                            <strong>Bukti Sebelumnya</strong>
                            <p class="mb-2 mt-2">Anda sudah pernah mengupload bukti transfer. Upload bukti baru akan mengganti yang lama.</p>
                            <img src="{{ Storage::url($order->pembayaran->bukti_bayar) }}" 
                                 alt="Bukti Bayar" 
                                 class="img-fluid rounded mt-2" 
                                 style="max-height: 300px;">
                        </div>
                    @endif

                    <form action="{{ route('pelanggan.order.upload-bukti', $order->cucian_id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">
                                Upload Bukti Transfer <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('bukti_bayar') is-invalid @enderror" 
                                   name="bukti_bayar" 
                                   accept="image/*"
                                   required
                                   onchange="previewImage(event)">
                            @error('bukti_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                        </div>

                        <!-- Preview Image -->
                        <div id="image-preview" class="mb-4" style="display:none;">
                            <label class="form-label">Preview:</label>
                            <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 400px;">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="material-icons md-cloud_upload"></i>
                            {{ $order->pembayaran->bukti_bayar ? 'Update' : 'Upload' }} Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Detail Pembayaran -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 text-white">Detail Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Metode Pembayaran</small>
                        <p class="mb-0"><strong>Transfer Bank</strong></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Total Yang Harus Dibayar</small>
                        <h4 class="text-primary mb-0">{{ $order->pembayaran->getFormattedJumlahBayar() }}</h4>
                    </div>

                    @if($order->layanan->jenis_cucian === 'kiloan')
                        <div class="alert alert-warning mb-3">
                            <i class="material-icons md-info"></i>
                            <small><strong>Catatan:</strong> Berat cucian: {{ number_format($order->total_berat, 1) }} Kg</small>
                        </div>
                    @endif

                    <hr>
                    
                    <small class="text-muted">Pastikan bukti transfer menunjukkan:</small>
                    <ul class="small mb-0">
                        <li>Nominal transfer sesuai</li>
                        <li>Tanggal transfer</li>
                        <li>Nama pengirim</li>
                        <li>Foto jelas dan tidak blur</li>
                    </ul>
                </div>
            </div>

            <!-- Info Rekening -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Rekening Tujuan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Bank</small>
                        <p class="mb-0"><strong>BCA</strong></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">No. Rekening</small>
                        <p class="mb-0"><strong>1234567890</strong></p>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Atas Nama</small>
                        <p class="mb-0"><strong>Laundry Wawan</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('image-preview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>
@endsection