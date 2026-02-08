@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Assign Kurir Pengantaran</h2>
            <p>Tugaskan kurir untuk mengantar cucian {{ $cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('staff.pengantaran.assign', $cucian->cucian_id) }}" method="POST">
                @csrf

                <!-- Info Cucian -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
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
                        <div class="alert alert-success mb-0">
                            <i class="material-icons md-check_circle"></i>
                            Cucian sudah <strong>selesai</strong> dan siap untuk diantar!
                        </div>
                    </div>
                </div>

                <!-- Form Pengantaran -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Form Pengantaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="kurir_id" class="form-label">
                                Pilih Kurir <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('kurir_id') is-invalid @enderror" 
                                    id="kurir_id" 
                                    name="kurir_id" 
                                    required>
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($kurirs as $kurir)
                                <option value="{{ $kurir->users_id }}" 
                                        {{ old('kurir_id', $cucian->pengantaran->kurir_id ?? '') == $kurir->users_id ? 'selected' : '' }}>
                                    {{ $kurir->nama }} - {{ $kurir->getRoleLabel() }}
                                </option>
                                @endforeach
                            </select>
                            @error('kurir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat_antar" class="form-label">
                                Alamat Antar <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat_antar') is-invalid @enderror" 
                                      id="alamat_antar" 
                                      name="alamat_antar" 
                                      rows="3" 
                                      required
                                      placeholder="Masukkan alamat lengkap untuk pengantaran">{{ old('alamat_antar', $cucian->pengantaran->alamat_antar ?? $cucian->pelanggan->alamat) }}</textarea>
                            @error('alamat_antar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Alamat default diambil dari data pelanggan</small>
                        </div>


                        <div class="alert alert-info">
                            <i class="material-icons md-info"></i>
                            <strong>Informasi:</strong> Setelah kurir ditugaskan, status pengantaran akan berubah menjadi "Dalam Pengiriman"
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="material-icons md-local_shipping"></i> 
                                    {{ $cucian->pengantaran ? 'Update Kurir' : 'Tugaskan Kurir' }}
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
            <!-- Timeline Pengantaran -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="text-white mb-0">Timeline Pengantaran</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                <small class="text-muted">
                                    {{ $cucian->tgl_selesai ? $cucian->tgl_selesai->format('d M Y H:i') : '-' }}
                                </small>
                            </div>
                        </div>

                        @if($cucian->pengantaran && $cucian->pengantaran->kurir_id)
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Kurir Ditugaskan</h6>
                                <p class="mb-0 small">{{ $cucian->pengantaran->kurir->nama }}</p>
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
                                <h6 class="mb-1">Dalam Pengiriman</h6>
                                <small class="text-muted">Menunggu kurir berangkat</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Terkirim</h6>
                                <small class="text-muted">Belum selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Pembayaran -->
            @if($cucian->pembayaran)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Status</small>
                        <p class="mb-0">
                            <span class="badge {{ $cucian->pembayaran->getStatusBadge() }}">
                                {{ $cucian->pembayaran->getStatusLabel() }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Total</small>
                        <h5 class="text-primary mb-0">{{ $cucian->getFormattedTotalHarga() }}</h5>
                    </div>
                </div>
            </div>
            @endif
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