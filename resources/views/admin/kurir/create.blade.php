@extends('admin.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Kurir Baru</h2>
            <p>Tambahkan data kurir baru ke sistem</p>
        </div>
        <div>
            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Kurir</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kurir.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Data Pribadi -->
                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama') }}" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="email@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Email akan digunakan untuk login</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" 
                                       name="no_telp" 
                                       class="form-control @error('no_telp') is-invalid @enderror" 
                                       value="{{ old('no_telp') }}" 
                                       placeholder="08xxxxxxxxxx">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">No. WhatsApp</label>
                            <input type="text" 
                                   name="no_wa" 
                                   class="form-control @error('no_wa') is-invalid @enderror" 
                                   value="{{ old('no_wa') }}" 
                                   placeholder="08xxxxxxxxxx">
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Untuk notifikasi via WhatsApp</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Password -->
                        <h6 class="mb-3">Keamanan Akun</h6>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 6 karakter"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Ulangi password"
                                       required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Foto -->
                        <h6 class="mb-3">Foto Profile</h6>
                        <div class="mb-4">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" 
                                   name="foto" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            
                            <!-- Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan Kurir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">📋 Informasi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="material-icons md-check_circle text-success"></i>
                            Pastikan data yang dimasukkan sudah benar
                        </li>
                        <li class="mb-2">
                            <i class="material-icons md-check_circle text-success"></i>
                            Email harus unik dan belum terdaftar
                        </li>
                        <li class="mb-2">
                            <i class="material-icons md-check_circle text-success"></i>
                            Password minimal 6 karakter
                        </li>
                        <li class="mb-2">
                            <i class="material-icons md-check_circle text-success"></i>
                            Foto maksimal 2MB
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">🔐 Keamanan</h6>
                    <p class="small text-muted mb-0">
                        Password yang dibuat akan di-enkripsi secara otomatis. 
                        Pastikan password cukup kuat untuk keamanan akun.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
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
@endpush