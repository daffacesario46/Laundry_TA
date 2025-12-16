@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Stok Bahan</h2>
            <p>Informasi lengkap tentang stok bahan</p>
        </div>
        <div>
            <a href="{{ route('admin.stok-bahan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
            <a href="{{ route('admin.stok-bahan.edit', $stokBahan->stok_bahan_id) }}" class="btn btn-primary">
                <i class="material-icons md-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Stok Bahan</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Jenis Bahan</label>
                                <h5>{{ ucfirst($stokBahan->jenis_bahan) }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Merk/Brand</label>
                                <h5>{{ $stokBahan->merk }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted">Stok Tersedia</label>
                                <h4 class="text-primary mb-0">{{ $stokBahan->stok_tersedia }}</h4>
                                <small class="text-muted">{{ $stokBahan->satuan }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted">Stok Minimum</label>
                                <h4 class="mb-0">{{ $stokBahan->stok_minimum }}</h4>
                                <small class="text-muted">{{ $stokBahan->satuan }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="text-muted">Status</label>
                                <div>
                                    @if($stokBahan->stok_tersedia <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($stokBahan->stok_tersedia <= $stokBahan->stok_minimum)
                                        <span class="badge bg-warning text-dark">Menipis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stokBahan->harga_beli)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Harga Beli per Satuan</label>
                                <h5 class="text-success mb-0">Rp {{ number_format($stokBahan->harga_beli, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Total Nilai Stok</label>
                                <h5 class="text-success mb-0">Rp {{ number_format($stokBahan->harga_beli * $stokBahan->stok_tersedia, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($stokBahan->deskripsi)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="text-muted">Deskripsi</label>
                                <p class="mb-0">{{ $stokBahan->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="material-icons md-schedule"></i>
                            Dibuat: {{ \Carbon\Carbon::parse($stokBahan->created_at)->format('d M Y, H:i') }}
                        </div>
                        <div class="col-md-6 text-md-end">
                            <i class="material-icons md-update"></i>
                            Update: {{ \Carbon\Carbon::parse($stokBahan->updated_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Box -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Status Stok</h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $percentage = ($stokBahan->stok_tersedia / max($stokBahan->stok_minimum, 1)) * 100;
                        $progressClass = 'bg-success';
                        if ($percentage <= 0) {
                            $progressClass = 'bg-danger';
                        } elseif ($percentage <= 100) {
                            $progressClass = 'bg-warning';
                        }
                    @endphp
                    
                    <h1 class="display-4 mb-0">{{ $stokBahan->stok_tersedia }}</h1>
                    <p class="text-muted">{{ $stokBahan->satuan }}</p>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar {{ $progressClass }}" 
                             role="progressbar" 
                             style="width: {{ min($percentage, 100) }}%"
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 0) }}%
                        </div>
                    </div>
                    
                    @if($stokBahan->stok_tersedia <= 0)
                        <div class="alert alert-danger">
                            <i class="material-icons md-error"></i>
                            <strong>Stok Habis!</strong><br>
                            Segera lakukan pembelian.
                        </div>
                    @elseif($stokBahan->stok_tersedia <= $stokBahan->stok_minimum)
                        <div class="alert alert-warning">
                            <i class="material-icons md-warning"></i>
                            <strong>Stok Menipis!</strong><br>
                            Pertimbangkan untuk restock.
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="material-icons md-check_circle"></i>
                            <strong>Stok Aman</strong><br>
                            Stok masih mencukupi.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.stok-bahan.edit', $stokBahan->stok_bahan_id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="material-icons md-edit"></i> Edit Stok
                    </a>
                    <form action="{{ route('admin.stok-bahan.destroy', $stokBahan->stok_bahan_id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus stok bahan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="material-icons md-delete"></i> Hapus Stok
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection