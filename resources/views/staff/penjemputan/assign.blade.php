@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Assign Kurir Penjemputan</h2>
            <p>Tugaskan kurir untuk menjemput cucian {{ $cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('staff.penjemputan.assign', $cucian->cucian_id) }}" method="POST">
                @csrf

                <!-- Info Cucian -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="text-white mb-0">Informasi Cucian</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">No Order</label>
                                <p class="fw-bold">{{ $cucian->getNoOrder() }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Status Cucian</label>
                                <p>
                                    <span class="badge {{ $cucian->getStatusBadge() }}">
                                        {{ $cucian->getStatusLabel() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Nama Pelanggan</label>
                                <p class="fw-bold">{{ $cucian->pelanggan->nama }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">No Telepon</label>
                                <p class="fw-bold">{{ $cucian->pelanggan->no_telp }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Penjemputan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Form Penjemputan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="staff_id" class="form-label">
                                Pilih Kurir <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('staff_id') is-invalid @enderror" 
                                    id="staff_id" 
                                    name="staff_id" 
                                    required>
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($kurirs as $kurir)
                                <option value="{{ $kurir->users_id }}" 
                                        {{ old('staff_id', $cucian->penjemputan->staff_id ?? '') == $kurir->users_id ? 'selected' : '' }}>
                                    {{ $kurir->nama }} - {{ $kurir->getRoleLabel() }}
                                </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat_jemput" class="form-label">
                                Alamat Jemput <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat_jemput') is-invalid @enderror" 
                                      id="alamat_jemput" 
                                      name="alamat_jemput" 
                                      rows="3" 
                                      required
                                      placeholder="Masukkan alamat lengkap untuk penjemputan">{{ old('alamat_jemput', $cucian->penjemputan->alamat_jemput ?? $cucian->pelanggan->alamat) }}</textarea>
                            @error('alamat_jemput')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Alamat default diambil dari data pelanggan</small>
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="2"
                                      placeholder="Catatan untuk kurir (opsional)">{{ old('catatan', $cucian->penjemputan->catatan ?? '') }}</textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="material-icons md-info"></i>
                            <strong>Informasi:</strong> Setelah kurir ditugaskan, status penjemputan akan berubah menjadi "Sedang Dijemput"
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="material-icons md-person_add"></i> 
                                    {{ $cucian->penjemputan ? 'Update Kurir' : 'Tugaskan Kurir' }}
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light w-100">
                                    <i class="material-icons md-close"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Timeline Penjemputan -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="text-white mb-0">Timeline Penjemputan</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Masuk</h6>
                                <small class="text-muted">{{ $cucian->tgl_order->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        @if($cucian->penjemputan && $cucian->penjemputan->staff_id)
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Kurir Ditugaskan</h6>
                                <p class="mb-0 small">{{ $cucian->penjemputan->staff->nama }}</p>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Kurir</h6>
                                <small class="text-muted">Belum ditugaskan</small>
                            </div>
                        </div>
                        @endif

                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Proses Penjemputan</h6>
                                <small class="text-muted">Menunggu kurir</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Selesai Dijemput</h6>
                                <small class="text-muted">Belum selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
@endsection