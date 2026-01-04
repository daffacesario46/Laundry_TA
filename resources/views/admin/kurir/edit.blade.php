@extends('admin.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Kurir</h2>
            <p>Update data kurir: <strong>{{ $kurir->nama }}</strong></p>
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
                    <form action="{{ route('admin.kurir.update', $kurir->users_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Data Pribadi -->
                        <div class="mb-4">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama', $kurir->nama) }}" 
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
                                       value="{{ old('email', $kurir->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" 
                                       name="no_telp" 
                                       class="form-control @error('no_telp') is-invalid @enderror" 
                                       value="{{ old('no_telp', $kurir->no_telp) }}">
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
                                   value="{{ old('no_wa', $kurir->no_wa) }}">
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3">{{ old('alamat', $kurir->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Password (Optional) -->
                        <h6 class="mb-3">Ganti Password (Opsional)</h6>
                        <div class="alert alert-info">
                            <i class="material-icons md-info"></i>
                            Kosongkan jika tidak ingin mengubah password
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Password Baru</label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 6 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Foto -->
                        <h6 class="mb-3">Foto Profile</h6>
                        
                        @if($kurir->foto)
                            <div class="mb-3">
                                <label class="form-label d-block">Foto Saat Ini:</label>
                                <img src="{{ Storage::url($kurir->foto) }}" 
                                     alt="{{ $kurir->nama }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Upload Foto Baru</label>
                            <input type="file" 
                                   name="foto" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.
                            </small>
                            
                            <!-- Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <label class="form-label">Preview Foto Baru:</label>
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update Kurir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Status Kurir</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge {{ $kurir->status == 'aktif' ? 'alert-success' : 'alert-danger' }} rounded-pill px-3 py-2">
                                {{ ucfirst($kurir->status) }}
                            </span>
                        </div>
                        <form action="{{ route('admin.kurir.toggle-status', $kurir->users_id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin mengubah status?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="material-icons md-sync"></i>
                                {{ $kurir->status == 'aktif' ? 'Non-aktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">📊 Informasi</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <strong>Terdaftar:</strong><br>
                            {{ $kurir->created_at->format('d M Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Update Terakhir:</strong><br>
                            {{ $kurir->updated_at->format('d M Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>ID Kurir:</strong> #{{ $kurir->users_id }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-danger mb-3">⚠️ Danger Zone</h6>
                    <p class="small text-muted mb-3">
                        Menghapus kurir akan menghapus semua data terkait. Aksi ini tidak dapat dibatalkan!
                    </p>
                    <form action="{{ route('admin.kurir.destroy', $kurir->users_id) }}" 
                          method="POST" 
                          onsubmit="return confirm('PERHATIAN: Yakin ingin menghapus kurir ini? Data tidak dapat dikembalikan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="material-icons md-delete_forever"></i> Hapus Kurir
                        </button>
                    </form>
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