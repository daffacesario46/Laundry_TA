@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Pengantaran</h2>
            <p>Informasi lengkap pengantaran {{ $pengantaran->cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.pengantaran.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Pengantaran -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="text-white mb-0">Informasi Pengantaran</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">No Order</p>
                            <h5>{{ $pengantaran->cucian->getNoOrder() }}</h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Status Pengantaran</p>
                            <h5>
                                <span class="badge rounded-pill {{ $pengantaran->getStatusBadge() }}">
                                    {{ $pengantaran->getStatusLabel() }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Tanggal Berangkat</p>
                            <h5>{{ $pengantaran->tgl_berangkat ? $pengantaran->tgl_berangkat->format('d M Y H:i') : '-' }}</h5>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-local_shipping"></i> Kurir</p>
                            <p class="fw-bold">{{ $pengantaran->getKurirNama() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-phone"></i> No Telepon Kurir</p>
                            <p class="fw-bold">{{ $pengantaran->kurir->no_telp ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-location_on"></i> Alamat Antar</p>
                        <div class="alert alert-light">
                            {{ $pengantaran->alamat_antar }}
                        </div>
                    </div>

                    @if($pengantaran->catatan)
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-note"></i> Catatan</p>
                        <div class="alert alert-info">
                            {{ $pengantaran->catatan }}
                        </div>
                    </div>
                    @endif

                    @if($pengantaran->foto)
                    <hr>
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-photo_camera"></i> Bukti Pengantaran</p>
                        <img src="{{ asset('storage/' . $pengantaran->foto) }}" 
                             alt="Bukti Pengantaran" 
                             class="img-fluid rounded"
                             style="max-width: 500px; cursor: pointer;"
                             onclick="window.open(this.src, '_blank')">
                        <p class="small text-muted mt-2">Klik gambar untuk memperbesar</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Cucian -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Cucian</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Nama Pelanggan</p>
                            <p class="fw-bold">{{ $pengantaran->cucian->pelanggan->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">No Telepon</p>
                            <p class="fw-bold">{{ $pengantaran->cucian->pelanggan->no_telp }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Layanan</p>
                            <p class="fw-bold">{{ $pengantaran->cucian->layanan->nama_layanan ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status Cucian</p>
                            <p>
                                <span class="badge {{ $pengantaran->cucian->getStatusBadge() }}">
                                    {{ $pengantaran->cucian->getStatusLabel() }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total Item</p>
                            <p class="fw-bold">{{ $pengantaran->cucian->total_item }} item</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total Harga</p>
                            <h5 class="text-primary">{{ $pengantaran->cucian->getFormattedTotalHarga() }}</h5>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('staff.cucian.show', $pengantaran->cucian->cucian_id) }}" 
                           class="btn btn-outline-primary">
                            <i class="material-icons md-visibility"></i> Lihat Detail Cucian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Pembayaran -->
            @if($pengantaran->cucian->pembayaran)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Status Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status</p>
                            <p>
                                <span class="badge {{ $pengantaran->cucian->pembayaran->getStatusBadge() }}">
                                    {{ $pengantaran->cucian->pembayaran->getStatusLabel() }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Metode</p>
                            <p class="fw-bold">{{ ucfirst($pengantaran->cucian->pembayaran->metode_bayar) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="text-white mb-0">Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                <p class="text-muted small mb-0">
                                    {{ $pengantaran->cucian->tgl_selesai ? $pengantaran->cucian->tgl_selesai->format('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>

                        @if($pengantaran->kurir_id)
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Kurir Ditugaskan</h6>
                                <p class="mb-0 small">{{ $pengantaran->kurir->nama }}</p>
                            </div>
                        </div>
                        @endif

                        @if($pengantaran->status == 'diproses' || $pengantaran->status == 'selesai')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Dalam Pengiriman</h6>
                                @if($pengantaran->tgl_berangkat)
                                <p class="text-muted small mb-0">{{ $pengantaran->tgl_berangkat->format('d M Y H:i') }}</p>
                                @endif
                                <span class="badge bg-info">Dalam Perjalanan</span>
                            </div>
                        </div>
                        @endif

                        @if($pengantaran->status == 'selesai')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Terkirim</h6>
                                <span class="badge bg-success">Selesai</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Aksi</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($pengantaran->status != 'selesai')
                        <a href="{{ route('staff.pengantaran.edit', $pengantaran->pengantaran_id) }}" 
                           class="btn btn-primary">
                            <i class="material-icons md-edit"></i> Edit Pengantaran
                        </a>

                        @if(!$pengantaran->foto)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
                            <i class="material-icons md-photo_camera"></i> Upload Bukti Foto
                        </button>
                        @endif

                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="material-icons md-update"></i> Update Status
                        </button>
                        @endif

                        <a href="{{ route('staff.cucian.show', $pengantaran->cucian->cucian_id) }}" 
                           class="btn btn-outline-primary">
                            <i class="material-icons md-local_laundry_service"></i> Lihat Cucian
                        </a>

                        @if($pengantaran->status == 'menunggu')
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="material-icons md-delete"></i> Hapus
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Upload Foto -->
<div class="modal fade" id="uploadFotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">Upload Bukti Pengantaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.pengantaran.upload-foto', $pengantaran->pengantaran_id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Pilih Foto <span class="text-danger">*</span></label>
                        <input type="file" 
                               class="form-control" 
                               id="foto" 
                               name="foto" 
                               accept="image/*"
                               required
                               onchange="previewImage(event)">
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                    </div>
                    <div id="preview-container" style="display: none;">
                        <p class="mb-2">Preview:</p>
                        <img id="preview-image" src="" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons md-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">Update Status Pengantaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.pengantaran.update-status', $pengantaran->pengantaran_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="menunggu" {{ $pengantaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $pengantaran->status == 'diproses' ? 'selected' : '' }}>Dalam Pengiriman</option>
                            <option value="selesai" {{ $pengantaran->status == 'selesai' ? 'selected' : '' }}>Terkirim</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $pengantaran->catatan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="material-icons md-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="delete-form" action="{{ route('staff.pengantaran.destroy', $pengantaran->pengantaran_id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
</style>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview-image');
    const container = document.getElementById('preview-container');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function confirmDelete() {
    Swal.fire({
        title: 'Hapus Pengantaran?',
        text: 'Yakin ingin menghapus data pengantaran ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endsection