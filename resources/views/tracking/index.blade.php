@extends('layouts.home')

@section('content')
<div class="container-xxl py-6" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="mb-4">
                        <img src="{{ asset('admins/imgs/theme/washwes.png') }}" alt="Washwes" style="width: 100px; height: 100px;">
                    </div>
                    <h1 class="display-5 mb-3">Lacak Status Cucian Anda</h1>
                    <p class="text-muted fs-5">Masukkan nomor order untuk melihat status cucian secara real-time</p>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show wow fadeInUp" data-wow-delay="0.2s" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show wow fadeInUp" data-wow-delay="0.2s" role="alert">
                    <i class="fa fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card border-0 shadow-lg wow fadeInUp" data-wow-delay="0.3s" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-5">
                        <form action="{{ route('tracking.track') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="no_order" class="form-label fw-bold fs-5">
                                    <i class="fa fa-barcode me-2 text-primary"></i>Nomor Order
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white border-0">
                                        <i class="fa fa-receipt"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-0 shadow-sm @error('no_order') is-invalid @enderror" 
                                           id="no_order" 
                                           name="no_order" 
                                           placeholder="Contoh: WW00001 atau 1"
                                           value="{{ old('no_order') }}"
                                           style="font-size: 1.2rem; padding: 0.8rem;"
                                           required
                                           autofocus>
                                </div>
                                @error('no_order')
                                <div class="text-danger mt-2">
                                    <i class="fa fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted d-block mt-2">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Nomor order dapat ditemukan pada struk yang Anda terima
                                </small>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg shadow" style="padding: 1rem; font-size: 1.1rem; border-radius: 10px;">
                                    <i class="fa fa-search me-2"></i>Lacak Sekarang
                                </button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <span class="text-muted">atau</span>
                        </div>

                        <div class="d-grid">
                            @auth
                                <a href="{{ route('pelanggan.order.index') }}" class="btn btn-outline-primary btn-lg" style="border-radius: 10px;">
                                    <i class="fa fa-list me-2"></i>Lihat Semua Order Saya
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg" style="border-radius: 10px;">
                                    <i class="fa fa-sign-in-alt me-2"></i>Login untuk Melihat Order
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="mt-4 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fa fa-lightbulb text-warning me-2"></i>Informasi Penting:
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                                        <small>Format: <strong>WW00001</strong> atau cukup <strong>1</strong></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                                        <small>Tracking tersedia <strong>24/7</strong></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                                        <small>Update status <strong>real-time</strong></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                                        <small>Lihat detail lengkap pesanan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection