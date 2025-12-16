@extends('pelanggan.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Edit Profile</h2>
            <p>Update informasi profil Anda</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.profile.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Profile Picture -->
                        <div class="mb-4 text-center">
                            <img src="{{ $user->img ?? asset('admins/imgs/people/avatar-2.png') }}" 
                                 class="rounded-circle mb-3" 
                                 id="preview-image"
                                 style="width: 120px; height: 120px; object-fit: cover;" 
                                 alt="Profile">
                            <div>
                                <label for="profile-image" class="btn btn-sm btn-outline-primary">
                                    <i class="material-icons md-camera_alt"></i> Ubah Foto
                                </label>
                                <input type="file" id="profile-image" name="img" class="d-none" accept="image/*">
                                <p class="text-muted small mt-2">Format: JPG, PNG. Max: 2MB</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Personal Info -->
                        <h5 class="mb-3">Informasi Pribadi</h5>

                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   name="nama" 
                                   value="{{ old('nama', $user->nama) }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('no_telp') is-invalid @enderror" 
                                       name="no_telp" 
                                       value="{{ old('no_telp', $user->no_telp) }}"
                                       required>
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" 
                                       class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      name="alamat" 
                                      rows="3"
                                      required>{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('pelanggan.profile.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Tips Keamanan</h6>
                    <ul class="text-muted small">
                        <li class="mb-2">Pastikan email yang digunakan masih aktif</li>
                        <li class="mb-2">Gunakan nomor telepon yang dapat dihubungi</li>
                        <li class="mb-2">Alamat harus lengkap untuk pengiriman</li>
                        <li>Update data secara berkala</li>
                    </ul>

                    <hr>

                    <div class="alert alert-info p-3 mb-0">
                        <small>
                            <i class="material-icons md-info" style="font-size: 18px;"></i>
                            <strong>Informasi:</strong> Data yang Anda ubah akan langsung digunakan untuk order selanjutnya.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('profile-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

@endsection