@extends('pelanggan.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Upload Bukti Pembayaran</h2>
            <p>Order: <strong>{{ $cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="icon material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Form Upload --}}
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Upload Bukti Transfer</h5>
                </div>
                <div class="card-body">
                    {{-- Rekening Info --}}
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="icon material-icons md-account_balance"></i> Informasi Rekening</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td width="30%">Bank</td>
                                <td><strong>: BCA</strong></td>
                            </tr>
                            <tr>
                                <td>No. Rekening</td>
                                <td><strong>: 1234567890</strong></td>
                            </tr>
                            <tr>
                                <td>Atas Nama</td>
                                <td><strong>: Washwes Laundry</strong></td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('pelanggan.order.upload-bukti', $cucian->cucian_id) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="uploadForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('bukti_bayar') is-invalid @enderror" 
                                   name="bukti_bayar" 
                                   id="bukti_bayar"
                                   accept="image/jpeg,image/jpg,image/png"
                                   required
                                   onchange="previewImage(event)">
                            @error('bukti_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                        </div>

                        {{-- Preview Image --}}
                        <div class="mb-3" id="preview-container" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="border rounded p-2">
                                <img id="preview-image" src="" alt="Preview" class="img-fluid" style="max-height: 300px;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" 
                                      name="catatan" 
                                      rows="3" 
                                      placeholder="Tambahkan catatan jika diperlukan (opsional)">{{ old('catatan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="icon material-icons md-cloud_upload"></i>
                            Upload Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Detail Pembayaran --}}
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">No. Order</td>
                            <td><strong>{{ $cucian->getNoOrder() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                            <td>{{ $cucian->layanan->nama_layanan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Total Item</td>
                            <td>{{ $cucian->total_item }} item</td>
                        </tr>
                        @if($cucian->total_berat)
                        <tr>
                            <td>Total Berat</td>
                            <td>{{ $cucian->total_berat }} kg</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Status Order</td>
                            <td>
                                <span class="badge rounded-pill {{ $cucian->getStatusBadge() }}">
                                    {{ $cucian->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td><h6>TOTAL BAYAR</h6></td>
                            <td><h5 class="text-primary mb-0">{{ $pembayaran->getFormattedJumlahBayar() }}</h5></td>
                        </tr>
                    </table>

                    @if($pembayaran->bukti_bayar)
                    <div class="alert alert-warning mt-3">
                        <i class="icon material-icons md-info"></i>
                        <strong>Perhatian!</strong> Anda sudah mengupload bukti sebelumnya. 
                        Upload baru akan menggantikan bukti yang lama.
                    </div>
                    @endif
                </div>
            </div>

            {{-- Petunjuk --}}
            <div class="card">
                <div class="card-header">
                    <h6>Petunjuk Upload</h6>
                </div>
                <div class="card-body">
                    <ol class="ps-3 mb-0">
                        <li>Transfer sesuai jumlah yang tertera</li>
                        <li>Screenshot bukti transfer dari mobile banking</li>
                        <li>Upload bukti transfer (format JPG/PNG, max 2MB)</li>
                        <li>Tunggu validasi dari staff (maksimal 1x24 jam)</li>
                        <li>Anda akan mendapat notifikasi setelah divalidasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Validation before submit
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const file = document.getElementById('bukti_bayar').files[0];
    
    if (!file) {
        e.preventDefault();
        alert('Silakan pilih file bukti pembayaran!');
        return false;
    }
    
    // Check file size (2MB = 2097152 bytes)
    if (file.size > 2097152) {
        e.preventDefault();
        alert('Ukuran file terlalu besar! Maksimal 2MB');
        return false;
    }
    
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        e.preventDefault();
        alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
        return false;
    }
});
</script>
@endsection