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
    min-width: 180px;
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

.dropdown-item-custom.text-success {
    color: #28a745 !important;
}

.dropdown-item-custom.text-primary {
    color: #007bff !important;
}
</style>

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Data Cucian</h2>
            <p>Kelola data cucian pelanggan</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah Cucian
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
            <form action="{{ route('staff.cucian.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" name="search" 
                               placeholder="Cari no order atau nama pelanggan..." 
                               class="form-control" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
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
                    @if(request()->anyFilled(['search', 'status', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('staff.cucian.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($cucian->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data cucian
                    @if(request()->filled('search'))
                        untuk pencarian "<strong>{{ request()->search }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Total Item</th>
                                <th>Berat</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Order</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cucian as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->getNoOrder() }}</strong>
                                    <br>
                                    {{-- ✅ ONLINE = HIJAU, OFFLINE = ABU-ABU --}}
                                    @if($item->jenis_order == 'online')
                                        <span class="badge bg-success text-white">
                                            Online
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white">
                                            Offline
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <b>{{ $item->pelanggan->nama ?? 'N/A' }}</b><br>
                                    <small class="text-muted">{{ $item->pelanggan->no_telp ?? '-' }}</small>
                                </td>
                                <td>{{ $item->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ $item->total_item }} item</td>
                                <td>{{ $item->total_berat ? number_format($item->total_berat, 1) . ' Kg' : '-' }}</td>
                                <td>{{ $item->getFormattedTotalHarga() }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $item->getStatusBadge() }}">
                                        {{ $item->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $item->tgl_order->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle-custom" 
                                                type="button" 
                                                onclick="toggleDropdown({{ $item->cucian_id }})"
                                                id="dropdownMenu{{ $item->cucian_id }}">
                                            <i class="material-icons md-more_vert"></i>
                                        </button>
                                        <div class="dropdown-menu-custom" id="dropdown{{ $item->cucian_id }}">
                                            <a href="{{ route('staff.cucian.show', $item->cucian_id) }}" 
                                               class="dropdown-item-custom">
                                                <i class="material-icons md-visibility text-primary"></i> Lihat Detail
                                            </a>
                                            @if($item->status_cucian != 'diambil' && $item->status_cucian != 'selesai')
                                            <a href="{{ route('staff.cucian.edit', $item->cucian_id) }}" 
                                               class="dropdown-item-custom">
                                                <i class="material-icons md-edit text-warning"></i> Edit
                                            </a>
                                            @endif
                                            @php
                                                $nextStatus = [
                                                    'menunggu' => ['status' => 'diproses', 'label' => 'Proses Cucian', 'icon' => 'hourglass_empty', 'color' => 'text-info'],
                                                    'diproses' => ['status' => 'selesai', 'label' => 'Tandai Selesai', 'icon' => 'check_circle', 'color' => 'text-success'],
                                                    'selesai' => ['status' => 'diambil', 'label' => 'Tandai Diambil', 'icon' => 'local_shipping', 'color' => 'text-primary'],
                                                ];
                                                $current = $item->status_cucian;
                                            @endphp
                                            
                                            @if(isset($nextStatus[$current]))
                                                <a href="#" 
                                                   onclick="updateStatus({{ $item->cucian_id }}, '{{ $nextStatus[$current]['status'] }}', '{{ $item->getNoOrder() }}')" 
                                                   class="dropdown-item-custom {{ $nextStatus[$current]['color'] }}">
                                                    <i class="material-icons md-{{ $nextStatus[$current]['icon'] }} {{ $nextStatus[$current]['color'] }}"></i> 
                                                    {{ $nextStatus[$current]['label'] }}
                                                </a>
                                            @endif
                                            
                                            @if($item->status_cucian == 'menunggu')
                                                <a href="#" 
                                                   onclick="confirmDelete({{ $item->cucian_id }}, '{{ addslashes($item->getNoOrder()) }}')" 
                                                   class="dropdown-item-custom text-danger">
                                                    <i class="material-icons md-delete text-danger"></i> Hapus
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Form Delete -->
                                    <form id="delete-form-{{ $item->cucian_id }}" 
                                          action="{{ route('staff.cucian.destroy', $item->cucian_id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <!-- Form Update Status -->
                                    <form id="status-form-{{ $item->cucian_id }}" 
                                          action="{{ route('staff.status-cucian.update-status', $item->cucian_id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="status" id="status-value-{{ $item->cucian_id }}" value="">
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($cucian->hasPages())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Menampilkan {{ $cucian->firstItem() ?? 0 }} sampai {{ $cucian->lastItem() ?? 0 }} dari {{ $cucian->total() }} data</p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-end">
                            {{ $cucian->links() }}
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

// Update status function
function updateStatus(id, newStatus, noOrder) {
    event.preventDefault();
    
    const statusLabels = {
        'diproses': 'memproses',
        'selesai': 'menyelesaikan',
        'diambil': 'menandai sudah diambil'
    };
    
    const statusText = statusLabels[newStatus] || 'mengubah status';
    
    Swal.fire({
        title: 'Update Status Cucian?',
        text: `Yakin ingin ${statusText} cucian ${noOrder}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('status-value-' + id).value = newStatus;
            document.getElementById('status-form-' + id).submit();
        }
    });
}

// Delete confirmation function
function confirmDelete(id, noOrder) {
    event.preventDefault();
    Swal.fire({
        title: 'Hapus Data Cucian?',
        text: `Yakin ingin menghapus cucian ${noOrder}?`,
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