@extends('staff.layouts.app')

@section('content')
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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-primary-light">
                        <i class="text-primary material-icons md-people"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Total Pelanggan</h6>
                        <span>{{ $pelanggan->total() }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-success-light">
                        <i class="text-success material-icons md-check_circle"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Pelanggan Aktif</h6>
                        <span>{{ $pelanggan->where('status', 'aktif')->count() }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-warning-light">
                        <i class="text-warning material-icons md-cancel"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Pelanggan Non-aktif</h6>
                        <span>{{ $pelanggan->where('status', 'nonaktif')->count() }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-info-light">
                        <i class="text-info material-icons md-person_add"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Pelanggan Baru (Bulan Ini)</h6>
                        <span>5</span>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <form method="GET" action="{{ route('staff.pelanggan.index') }}">
                        <input type="text" name="search" placeholder="Cari nama, telp, atau email..." 
                               class="form-control" value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <form method="GET" action="{{ route('staff.pelanggan.index') }}">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </form>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <form method="GET" action="{{ route('staff.pelanggan.index') }}">
                        <select class="form-select" name="paginate" onchange="this.form.submit()">
                            <option value="15" {{ request('paginate') == 15 ? 'selected' : '' }}>Show 15</option>
                            <option value="30" {{ request('paginate') == 30 ? 'selected' : '' }}>Show 30</option>
                            <option value="50" {{ request('paginate') == 50 ? 'selected' : '' }}>Show 50</option>
                        </select>
                    </form>
                </div>
            </div>
        </header>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Total Transaksi</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggan as $index => $item)
                        <tr>
                            <td>{{ $pelanggan->firstItem() + $index }}</td>
                            <td>
                                <b>{{ $item->nama }}</b><br>
                                <small class="text-muted">
                                    <i class="material-icons md-{{ $item->jenis_kelamin == 'Laki-laki' ? 'male' : 'female' }}"></i>
                                    {{ $item->jenis_kelamin }}
                                </small>
                            </td>
                            <td>
                                <i class="material-icons md-phone"></i> {{ $item->no_telp }}<br>
                                <small class="text-muted">
                                    <i class="material-icons md-email"></i> {{ $item->email }}
                                </small>
                            </td>
                            <td>
                                <small>{{ Str::limit($item->alamat, 40) }}</small>
                            </td>
                            <td>
                                <span class="badge rounded-pill alert-info">
                                    {{ $item->total_transaksi }} Transaksi
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->status == 'aktif')
                                    <span class="badge rounded-pill alert-success">Aktif</span>
                                @else
                                    <span class="badge rounded-pill alert-warning">Non-aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light">
                                        <i class="material-icons md-more_vert"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" onclick="showDetail({{ $item->id }})">
                                            <i class="material-icons md-visibility"></i> Lihat Detail
                                        </a>
                                        <a class="dropdown-item" href="{{ route('staff.pelanggan.edit', $item->id) }}">
                                            <i class="material-icons md-edit"></i> Edit
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')">
                                            <i class="material-icons md-delete"></i> Hapus
                                        </a>
                                    </div>
                                </div>

                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('staff.pelanggan.destroy', $item->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <img src="{{ asset('admins/imgs/theme/empty.png') }}" alt="No data" style="width: 100px;">
                                <p class="text-muted mt-3">Belum ada data pelanggan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">Showing {{ $pelanggan->firstItem() ?? 0 }} to {{ $pelanggan->lastItem() ?? 0 }} of {{ $pelanggan->total() }} entries</p>
                </div>
                <div class="col-md-6">
                    <nav class="float-end">
                        {{ $pelanggan->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function showDetail(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Detail Pelanggan',
            html: '<p class="text-muted">Fitur detail pelanggan akan segera hadir!</p>',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

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