@extends('staff.layouts.app')

@section('content')
<style>
/* Custom Dropdown Styles */
.dropdown {
    position: relative;
}

.dropdown-toggle-custom {
    background: transparent;
    border: none;
    padding: 4px 8px;
    cursor: pointer;
    border-radius: 4px;
}

.dropdown-toggle-custom:hover {
    background: #f0f0f0;
}

.dropdown-menu-custom {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 160px;
    z-index: 1000;
    padding: 8px 0;
    margin-top: 4px;
}

.dropdown-menu-custom.show {
    display: block;
}

.dropdown-item-custom {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
    gap: 10px;
}

.dropdown-item-custom:hover {
    background: #f5f5f5;
}

.dropdown-item-custom i {
    font-size: 18px;
}

.dropdown-item-custom.text-danger {
    color: #dc3545 !important;
}
</style>

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Data Pelanggan</h2>
            <p>Kelola data pelanggan laundry</p>
        </div>
        <div>
            <a href="{{ route('staff.pelanggan.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah Pelanggan
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="material-icons md-check_circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="material-icons md-error"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('staff.pelanggan.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" name="search" 
                               placeholder="Cari nama, telepon, atau WA..." 
                               class="form-control" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="kategori">
                            <option value="">Semua Kategori</option>
                            <option value="online" {{ request('kategori') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('kategori') == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="paginate">
                            <option value="15" {{ request('paginate', 15) == 15 ? 'selected' : '' }}>Show 15</option>
                            <option value="30" {{ request('paginate') == 30 ? 'selected' : '' }}>Show 30</option>
                            <option value="50" {{ request('paginate') == 50 ? 'selected' : '' }}>Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($pelanggan->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data pelanggan
                    @if(request()->filled('search'))
                        untuk pencarian "<strong>{{ request()->search }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggan as $item)
                            <tr>
                                <td>
                                    @if($item->foto)
                                        <img src="{{ Storage::url($item->foto) }}" 
                                             class="rounded-circle" 
                                             width="40" 
                                             height="40" 
                                             style="object-fit: cover;"
                                             alt="{{ $item->nama }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" 
                                             style="width: 40px; height: 40px;">
                                            <i class="material-icons">person</i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->nama }}</strong>
                                    @if($item->user)
                                        <br><small class="text-muted">
                                            <i class="material-icons md-email" style="font-size: 12px;"></i> 
                                            {{ $item->user->email }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <i class="material-icons md-phone small"></i> {{ $item->no_telp }}<br>
                                    @if($item->no_wa)
                                        <i class="material-icons md-chat small text-success"></i> {{ $item->no_wa }}
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->alamat, 30) }}</td>
                                <td>
                                    @if($item->kategori_pelanggan == 'online')
                                        <span class="badge bg-success">Online</span>
                                    @else
                                        <span class="badge bg-secondary">Offline</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $item->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle-custom" 
                                                type="button" 
                                                onclick="toggleDropdown({{ $item->pelanggan_id }})"
                                                id="dropdownMenu{{ $item->pelanggan_id }}">
                                            <i class="material-icons md-more_vert"></i>
                                        </button>
                                        <div class="dropdown-menu-custom" id="dropdown{{ $item->pelanggan_id }}">
                                            <a href="{{ route('staff.pelanggan.edit', $item->pelanggan_id) }}" class="dropdown-item-custom">
                                                <i class="material-icons md-edit text-warning"></i> Edit
                                            </a>
                                            <a href="#" 
                                               onclick="toggleStatus({{ $item->pelanggan_id }}, '{{ $item->status }}')" 
                                               class="dropdown-item-custom">
                                                <i class="material-icons md-check_circle text-success"></i> 
                                                {{ $item->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </a>
                                            <a href="#" 
                                               onclick="confirmDelete({{ $item->pelanggan_id }}, '{{ addslashes($item->nama) }}')" 
                                               class="dropdown-item-custom text-danger">
                                                <i class="material-icons md-delete text-danger"></i> Hapus
                                            </a>
                                        </div>
                                    </div>

                                    <form id="delete-form-{{ $item->pelanggan_id }}" 
                                          action="{{ route('staff.pelanggan.destroy', $item->pelanggan_id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <form id="toggle-form-{{ $item->pelanggan_id }}" 
                                          action="{{ route('staff.pelanggan.update', $item->pelanggan_id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nama" value="{{ $item->nama }}">
                                        <input type="hidden" name="no_telp" value="{{ $item->no_telp }}">
                                        <input type="hidden" name="alamat" value="{{ $item->alamat }}">
                                        <input type="hidden" name="kategori_pelanggan" value="{{ $item->kategori_pelanggan }}">
                                        <input type="hidden" name="status" id="status-{{ $item->pelanggan_id }}" value="">
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($pelanggan->hasPages())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Menampilkan {{ $pelanggan->firstItem() ?? 0 }} sampai {{ $pelanggan->lastItem() ?? 0 }} dari {{ $pelanggan->total() }} data</p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-end">
                            {{ $pelanggan->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
// Toggle dropdown visibility
function toggleDropdown(id) {
    event.stopPropagation();
    const dropdown = document.getElementById('dropdown' + id);
    const allDropdowns = document.querySelectorAll('.dropdown-menu-custom');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== 'dropdown' + id) {
            d.classList.remove('show');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.dropdown-menu-custom');
    dropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
    });
});

// Prevent dropdown from closing when clicking inside
document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

// Toggle status function
function toggleStatus(id, currentStatus) {
    event.preventDefault();
    const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
    const statusText = newStatus === 'aktif' ? 'mengaktifkan' : 'menonaktifkan';
    
    Swal.fire({
        title: 'Ubah Status Pelanggan?',
        text: `Yakin ingin ${statusText} pelanggan ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('status-' + id).value = newStatus;
            document.getElementById('toggle-form-' + id).submit();
        }
    });
}

// Delete confirmation function
function confirmDelete(id, nama) {
    event.preventDefault();
    Swal.fire({
        title: 'Hapus Data Pelanggan?',
        text: `Yakin ingin menghapus pelanggan ${nama}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection