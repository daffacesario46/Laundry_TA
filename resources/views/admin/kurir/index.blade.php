@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Manajemen Kurir</h2>
            <p>Kelola data kurir laundry</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKurir">
                <i class="material-icons md-add"></i> Tambah Kurir
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.kurir.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari nama atau email..." 
                               class="form-control" 
                               value="{{ request()->query('search') }}" />
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option {{ request()->query('status') == 'aktif' ? 'selected' : '' }} value="aktif">Aktif</option>
                            <option {{ request()->query('status') == 'nonaktif' ? 'selected' : '' }} value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="paginate">
                            <option {{ request()->query('paginate') == 15 ? 'selected' : '' }} value="15">Show 15</option>
                            <option {{ request()->query('paginate') == 30 ? 'selected' : '' }} value="30">Show 30</option>
                            <option {{ request()->query('paginate') == 50 ? 'selected' : '' }} value="50">Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Cari
                        </button>
                    </div>
                    @if(request()->anyFilled(['search', 'status', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($kurir->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data kurir yang ditemukan
                    @if(request()->filled('search'))
                        untuk pencarian "<strong>{{ request()->search }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kurir as $index => $item)
                            <tr>
                                <td>{{ $kurir->firstItem() + $index }}</td>
                                <td>
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                            class="img-sm rounded-circle" 
                                            width="40" 
                                            height="40"
                                            style="object-fit: cover;"
                                            alt="{{ $item->nama }}" />
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" 
                                            style="width: 40px; height: 40px;">
                                            <i class="material-icons" style="font-size: 20px;">person</i>
                                        </div>
                                    @endif
                                </td>

                                <td><strong>{{ $item->nama }}</strong></td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_telp ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $item->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-light">
                                            <i class="material-icons md-more_horiz"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#modalEditKurir{{ $item->users_id }}">
                                                <i class="material-icons md-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.kurir.toggle-status', $item->users_id) }}" 
                                                  method="POST" 
                                                  style="display:inline;">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="material-icons md-{{ $item->status == 'aktif' ? 'block' : 'check_circle' }}"></i> 
                                                    {{ $item->status == 'aktif' ? 'Non-aktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.kurir.destroy', $item->users_id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus kurir ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="material-icons md-delete"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if($kurir->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $kurir->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

<!-- Modal Tambah Kurir -->
<div class="modal fade" id="modalTambahKurir" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kurir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kurir.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="08xxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WhatsApp</label>
                            <input type="text" name="no_wa" class="form-control" placeholder="08xxx">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Foto Profile</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons md-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kurir (Loop) -->
@foreach($kurir as $item)
<div class="modal fade" id="modalEditKurir{{ $item->users_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kurir: {{ $item->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kurir.update', $item->users_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $item->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" minlength="6">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" minlength="6">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control" value="{{ $item->no_telp }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. WhatsApp</label>
                            <input type="text" name="no_wa" class="form-control" value="{{ $item->no_wa }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ $item->alamat }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Foto Profile</label>
                            @if($item->foto)
                                <div class="mb-2">
                                    <label class="form-label">Foto Saat Ini:</label><br>
                                    <img src="{{ Storage::url($item->foto) }}" 
                                        width="100" 
                                        height="100"
                                        style="object-fit: cover;"
                                        class="rounded">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons md-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection