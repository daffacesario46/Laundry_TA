@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Penjemputan</h2>
            <p>Informasi lengkap penjemputan {{ $penjemputan->cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.penjemputan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Penjemputan -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="text-white mb-0">Informasi Penjemputan</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">No Order</p>
                            <h5>{{ $penjemputan->cucian->getNoOrder() }}</h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Status Penjemputan</p>
                            <h5>
                                <span class="badge rounded-pill {{ $penjemputan->getStatusBadge() }}">
                                    {{ $penjemputan->getStatusLabel() }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Tanggal Order</p>
                            <h5>{{ $penjemputan->tgl_order->format('d M Y H:i') }}</h5>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-person"></i> Kurir</p>
                            <p class="fw-bold">{{ $penjemputan->getStaffNama() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-phone"></i> No Telepon Kurir</p>
                            <p class="fw-bold">{{ $penjemputan->staff->no_telp ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-location_on"></i> Alamat Jemput</p>
                        <div class="alert alert-light">
                            {{ $penjemputan->alamat_jemput }}
                        </div>
                    </div>

                    @if($penjemputan->catatan)
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-note"></i> Catatan</p>
                        <div class="alert alert-info">
                            {{ $penjemputan->catatan }}
                        </div>
                    </div>
                    @endif

                    @if($penjemputan->foto)
                    <hr>
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-photo_camera"></i> Bukti Penjemputan</p>
                        <img src="{{ asset('storage/' . $penjemputan->foto) }}" 
                             alt="Bukti Penjemputan" 
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
                            <p class="fw-bold">{{ $penjemputan->cucian->pelanggan->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">No Telepon</p>
                            <p class="fw-bold">{{ $penjemputan->cucian->pelanggan->no_telp }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Layanan</p>
                            <p class="fw-bold">{{ $penjemputan->cucian->layanan->nama_layanan ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status Cucian</p>
                            <p>
                                <span class="badge {{ $penjemputan->cucian->getStatusBadge() }}">
                                    {{ $penjemputan->cucian->getStatusLabel() }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total Item</p>
                            <p class="fw-bold">{{ $penjemputan->cucian->total_item }} item</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total Harga</p>
                            <h5 class="text-primary">{{ $penjemputan->cucian->getFormattedTotalHarga() }}</h5>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('staff.cucian.show', $penjemputan->cucian->cucian_id) }}" 
                           class="btn btn-outline-primary">
                            <i class="material-icons md-visibility"></i> Lihat Detail Cucian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="text-white mb-0">Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Masuk</h6>
                                <p class="text-muted small mb-0">
                                    {{ $penjemputan->tgl_order->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($penjemputan->staff_id)
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Kurir Ditugaskan</h6>
                                <p class="mb-0 small">{{ $penjemputan->staff->nama }}</p>
                            </div>
                        </div>
                        @endif

                        @if($penjemputan->status == 'diproses' || $penjemputan->status == 'selesai')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Sedang Dijemput</h6>
                                <span class="badge bg-info">Dalam Perjalanan</span>
                            </div>
                        </div>
                        @endif

                        @if($penjemputan->status == 'selesai')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Selesai Dijemput</h6>
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
                        @if($penjemputan->status != 'selesai')
                        <a href="{{ route('staff.penjemputan.edit', $penjemputan->penjemputan_id) }}" 
                           class="btn btn-primary">
                            <i class="material-icons md-edit"></i> Edit Penjemputan
                        </a>

                        @if(!$penjemputan->foto)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
                            <i class="material-icons md-photo_camera"></i> Upload Bukti Foto
                        </button>
                        @endif

                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="material-icons md-update"></i> Update Status
                        </button>
                        @endif

                        <a href="{{ route('staff.cucian.show', $penjemputan->cucian->cucian_id) }}" 
                           class="btn btn-outline-primary">
                            <i class="material-icons md-local_laundry_service"></i> Lihat Cucian
                        </a>

                        @if($penjemputan->status == 'menunggu')
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
                <h5 class="modal-title text-white">Upload Bukti Penjemputan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.penjemputan.upload-foto', $penjemputan->penjemputan_id) }}" 
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
                <h5 class="modal-title text-white">Update Status Penjemputan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.penjemputan.update-status', $penjemputan->penjemputan_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="menunggu" {{ $penjemputan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $penjemputan->status == 'diproses' ? 'selected' : '' }}>Sedang Dijemput</option>
                            <option value="selesai" {{ $penjemputan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $penjemputan->catatan }}</textarea>
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
<form id="delete-form" action="{{ route('staff.penjemputan.destroy', $penjemputan->penjemputan_id) }}" method="POST" style="display: none;">
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
        title: 'Hapus Penjemputan?',
        text: 'Yakin ingin menghapus data penjemputan ini?',
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