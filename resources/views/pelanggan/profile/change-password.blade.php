@extends('pelanggan.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Ubah Password</h2>
            <p>Perbarui password akun Anda</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.profile.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pelanggan.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password"
                                   id="new_password"
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                   name="new_password_confirmation"
                                   id="new_password_confirmation"
                                   required>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="material-icons md-warning"></i> Perhatian:
                            </h6>
                            <ul class="mb-0 ps-3 small">
                                <li>Password minimal 8 karakter</li>
                                <li>Gunakan kombinasi huruf besar, kecil, dan angka</li>
                                <li>Jangan gunakan password yang mudah ditebak</li>
                                <li>Setelah mengubah password, Anda akan otomatis logout</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-lock"></i> Ubah Password
                            </button>
                            <a href="{{ route('pelanggan.profile.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection