@extends('layouts.home')

@section('content')
<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="mb-3">Lacak Status Cucian Anda</h1>
                    <p class="text-muted">Masukkan nomor order untuk melihat status cucian Anda secara real-time</p>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show wow fadeInUp" data-wow-delay="0.2s" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card border-0 shadow-lg wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('admins/imgs/theme/washwes.png') }}" alt="Washwes" style="width: 80px; height: 80px;">
                        </div>

                        <form action="{{ route('tracking.track') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="no_order" class="form-label fw-bold">
                                    <i class="fa fa-receipt me-2"></i>Nomor Order
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('no_order') is-invalid @enderror" 
                                       id="no_order" 
                                       name="no_order" 
                                       placeholder="Contoh: WW001"
                                       value="{{ old('no_order') }}"
                                       required
                                       autofocus>
                                @error('no_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Nomor order dapat ditemukan pada struk/nota yang Anda terima
                                </small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-search me-2"></i>Lacak Sekarang
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading">
                                <i class="fa fa-lightbulb me-2"></i>Informasi Penting:
                            </h6>
                            <ul class="mb-0 ps-3">
                                <li>No Order dapat ditemukan pada struk/nota yang Anda terima</li>
                                <li>Format No Order: <strong>WW001, WW002, dst</strong></li>
                                <li>Jika No Order tidak ditemukan, hubungi customer service kami</li>
                                <li>Tracking tersedia 24/7</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection