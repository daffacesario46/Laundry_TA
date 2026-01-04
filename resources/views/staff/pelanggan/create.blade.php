@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Pelanggan Baru</h2>
            <p>Tambahkan data pelanggan baru</p>
        </div>
        <div>
            <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Pelanggan</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('staff.pelanggan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- ✅ FOTO SECTION WITH PREVIEW -->
                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto Pelanggan</label>
                            
                            <!-- Preview Container -->
                            <div class="text-center mb-3" id="preview-container" style="display: none;">
                                <img id="foto-preview" src="" alt="Preview" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removePreview()">
                                    <i class="material-icons md-close"></i> Hapus Preview
                                </button>
                            </div>
                            
                            <input type="file" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" 
                                   name="foto" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="material-icons md-info" style="font-size: 14px;"></i>
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" placeholder="Masukkan nama lengkap" 
                                   value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="no_telp" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                       id="no_telp" name="no_telp" placeholder="08xxxxxxxxxx" 
                                       value="{{ old('no_telp') }}" required>
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_wa" class="form-label">No WhatsApp</label>
                                <input type="text" class="form-control @error('no_wa') is-invalid @enderror" 
                                       id="no_wa" name="no_wa" placeholder="08xxxxxxxxxx" 
                                       value="{{ old('no_wa') }}">
                                @error('no_wa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Opsional, kosongkan jika sama dengan no telepon</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="4" 
                                      placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Jl. Merdeka No. 123, Jakarta Selatan</small>
                        </div>

                        <div class="mb-4">
                            <label for="kategori_pelanggan" class="form-label">Kategori Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori_pelanggan') is-invalid @enderror" 
                                    id="kategori_pelanggan" name="kategori_pelanggan" required>
                                <option value="">Pilih Kategori</option>
                                <option value="umum" {{ old('kategori_pelanggan') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="member" {{ old('kategori_pelanggan') == 'member' ? 'selected' : '' }}>Member</option>
                            </select>
                            @error('kategori_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Akun Login (Opsional)</h5>
                        <p class="text-muted small">Isi bagian ini jika pelanggan ingin bisa login ke sistem</p>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="nama@email.com" 
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Minimal 6 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi password">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="material-icons md-save"></i> Simpan Data
                            </button>
                            <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-light flex-fill">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Panduan Pengisian</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="material-icons md-info text-primary"></i> Tips:</h6>
                        <ul class="text-muted small">
                            <li>Nama harus sesuai identitas pelanggan</li>
                            <li>No telepon harus bisa dihubungi</li>
                            <li>Alamat harus lengkap untuk pengiriman</li>
                            <li>Foto bersifat opsional</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6><i class="material-icons md-category text-info"></i> Kategori Pelanggan:</h6>
                        <ul class="text-muted small">
                            <li><strong>Umum:</strong> Pelanggan biasa tanpa akun login</li>
                            <li><strong>Member:</strong> Pelanggan dengan akun login dan benefit khusus</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="alert alert-warning">
                        <small>
                            <i class="material-icons md-warning"></i>
                            <strong>Perhatian!</strong><br>
                            Data yang sudah disimpan dapat diubah melalui menu Edit.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white mb-0">Format Data</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2"><strong>No Telepon:</strong></p>
                    <p class="text-muted small">081234567890</p>

                    <p class="text-muted small mb-2 mt-3"><strong>Email:</strong></p>
                    <p class="text-muted small">nama@email.com</p>

                    <p class="text-muted small mb-2 mt-3"><strong>Alamat:</strong></p>
                    <p class="text-muted small">Jl. Nama Jalan No. XX, Kota</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ JavaScript untuk Preview Foto -->
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const previewImg = document.getElementById('foto-preview');
    
    if (file) {
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }
        
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
            event.target.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    const previewContainer = document.getElementById('preview-container');
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('foto-preview');
    
    // Reset
    fotoInput.value = '';
    previewImg.src = '';
    previewContainer.style.display = 'none';
}
</script>
@endsection