@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Pelanggan</h2>
            <p>Edit data pelanggan {{ $pelanggan->nama }}</p>
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

                    <form action="{{ route('staff.pelanggan.update', $pelanggan->pelanggan_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- ✅ IMPROVED FOTO SECTION -->
                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto Pelanggan</label>
                            
                            <!-- Current Photo Display -->
                            <div class="text-center mb-3" id="current-photo-container">
                                @if($pelanggan->hasFoto())
                                    <img src="{{ $pelanggan->getFotoUrl() }}" alt="{{ $pelanggan->nama }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                    <p class="small text-muted mt-1">Foto saat ini</p>
                                    
                                    <!-- ✅ Delete Photo Button -->
                                    <form action="{{ route('staff.pelanggan.delete-foto', $pelanggan->pelanggan_id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus foto ini?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="material-icons md-delete"></i> Hapus Foto
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <i class="material-icons md-info"></i> 
                                        Pelanggan belum memiliki foto
                                    </div>
                                @endif
                            </div>
                            
                            <!-- New Photo Preview -->
                            <div class="text-center mb-3" id="new-preview-container" style="display: none;">
                                <p class="text-success fw-bold">Foto Baru:</p>
                                <img id="foto-preview" src="" alt="Preview" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-warning mt-2" onclick="removePreview()">
                                    <i class="material-icons md-close"></i> Batal Ganti
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
                                Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti foto.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="no_telp" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                       id="no_telp" name="no_telp" 
                                       value="{{ old('no_telp', $pelanggan->no_telp) }}" required>
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="no_wa" class="form-label">No WhatsApp</label>
                                <input type="text" class="form-control @error('no_wa') is-invalid @enderror" 
                                       id="no_wa" name="no_wa" 
                                       value="{{ old('no_wa', $pelanggan->no_wa) }}">
                                @error('no_wa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="4" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="kategori_pelanggan" class="form-label">Kategori Pelanggan <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_pelanggan') is-invalid @enderror" 
                                        id="kategori_pelanggan" 
                                        name="kategori_pelanggan" 
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="offline" {{ $pelanggan->kategori_pelanggan == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="online" {{ $pelanggan->kategori_pelanggan == 'online' ? 'selected' : '' }}>Online</option>
                                </select>
                                @error('kategori_pelanggan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="aktif" {{ old('status', $pelanggan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $pelanggan->status) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Akun Login {{ $pelanggan->user ? '(Sudah Ada)' : '(Belum Ada)' }}</h5>
                        
                        @if($pelanggan->user)
                            <p class="text-muted small">Update informasi akun login pelanggan</p>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email', $pelanggan->user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        @else
                            <p class="text-muted small">Pelanggan ini belum memiliki akun login. Anda bisa membuatkan akun dengan mengisi form di bawah ini.</p>
                            
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
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update Data
                            </button>
                            <a href="{{ route('staff.pelanggan.index') }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="text-white mb-0">Statistik Pelanggan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Total Transaksi</p>
                        <h3 class="text-primary">{{ $pelanggan->getTotalOrder() }}</h3>
                        <small class="text-muted">Transaksi tercatat</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Bergabung Sejak</p>
                        <h6>{{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d F Y') }}</h6>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($pelanggan->created_at)->diffForHumans() }}
                        </small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Status Saat Ini</p>
                        <span class="{{ $pelanggan->getStatusBadge() }}">{{ ucfirst($pelanggan->status) }}</span>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Kategori</p>
                        <span class="{{ $pelanggan->getKategoriBadge() }}">{{ $pelanggan->getKategoriLabel() }}</span>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Kontak</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-phone"></i> Telepon
                        </p>
                        <p class="fw-bold">{{ $pelanggan->no_telp }}</p>
                    </div>

                    @if($pelanggan->no_wa)
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-chat"></i> WhatsApp
                        </p>
                        <p class="fw-bold">{{ $pelanggan->no_wa }}</p>
                    </div>
                    @endif

                    @if($pelanggan->user)
                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-email"></i> Email
                        </p>
                        <p class="fw-bold">{{ $pelanggan->user->email }}</p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <p class="text-muted mb-1">
                            <i class="material-icons md-location_on"></i> Alamat
                        </p>
                        <p class="fw-bold">{{ $pelanggan->alamat }}</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h4 class="text-white mb-0">Aksi Lainnya</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary" onclick="window.print()">
                            <i class="material-icons md-print"></i> Cetak Data
                        </button>
                        <button class="btn btn-danger" onclick="confirmDelete()">
                            <i class="material-icons md-delete"></i> Hapus Pelanggan
                        </button>
                    </div>

                    <form id="delete-form" action="{{ route('staff.pelanggan.destroy', $pelanggan->pelanggan_id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ JavaScript untuk Preview Foto Baru -->
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const newPreviewContainer = document.getElementById('new-preview-container');
    const currentPhotoContainer = document.getElementById('current-photo-container');
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
            newPreviewContainer.style.display = 'block';
            currentPhotoContainer.style.opacity = '0.5';
        }
        reader.readAsDataURL(file);
    }
}

function removePreview() {
    const newPreviewContainer = document.getElementById('new-preview-container');
    const currentPhotoContainer = document.getElementById('current-photo-container');
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('foto-preview');
    
    // Reset
    fotoInput.value = '';
    previewImg.src = '';
    newPreviewContainer.style.display = 'none';
    currentPhotoContainer.style.opacity = '1';
}

function confirmDelete() {
    if (confirm('Yakin ingin menghapus pelanggan {{ $pelanggan->nama }}? Data tidak bisa dikembalikan!')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection