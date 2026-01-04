@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Edit Profile</h2>
            <p>Perbarui informasi profile Anda</p>
        </div>
        <div>
            <a href="{{ route('staff.profile.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Photo Upload -->
                        <div class="mb-4 text-center">
                            <div class="mb-3">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" 
                                         alt="Profile Photo" 
                                         class="rounded-circle"
                                         id="photo-preview"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('admins/imgs/people/avatar-2.png') }}" 
                                         alt="Default Avatar" 
                                         class="rounded-circle"
                                         id="photo-preview"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="btn btn-sm btn-primary">
                                    <i class="material-icons md-camera_alt"></i> Pilih Foto
                                </label>
                                <input type="file" 
                                       class="d-none @error('foto') is-invalid @enderror" 
                                       id="foto" 
                                       name="foto" 
                                       accept="image/*"
                                       onchange="previewPhoto(event)">
                                @error('foto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Format: JPG, JPEG, PNG | Maksimal 2MB
                                </small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', $user->nama) }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Telepon -->
                        <div class="mb-4">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" 
                                   class="form-control @error('no_telp') is-invalid @enderror" 
                                   id="no_telp" 
                                   name="no_telp" 
                                   value="{{ old('no_telp', $user->no_telp) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No WhatsApp -->
                        <div class="mb-4">
                            <label for="no_wa" class="form-label">No. WhatsApp</label>
                            <input type="text" 
                                   class="form-control @error('no_wa') is-invalid @enderror" 
                                   id="no_wa" 
                                   name="no_wa" 
                                   value="{{ old('no_wa', $user->no_wa) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Gunakan format: 08xxxxxxxxxx</small>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('staff.profile.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function previewPhoto(event) {
    const preview = document.getElementById('photo-preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection