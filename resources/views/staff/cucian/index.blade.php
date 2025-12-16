@extends('staff.layouts.app')

@section('content')
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

    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <form method="GET" action="{{ route('staff.cucian.index') }}">
                        <input type="text" name="search" placeholder="Cari no order atau nama pelanggan..." 
                               class="form-control" value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <form method="GET" action="{{ route('staff.cucian.index') }}">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                    </form>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <form method="GET" action="{{ route('staff.cucian.index') }}">
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
                            <th>No Order</th>
                            <th>Pelanggan</th>
                            <th>Jenis Layanan</th>
                            <th>Berat (Kg)</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal Masuk</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cucian as $item)
                        <tr>
                            <td><b>{{ $item->no_order }}</b></td>
                            <td>
                                <b>{{ $item->nama_pelanggan }}</b><br>
                                <small class="text-muted">{{ $item->no_telp }}</small>
                            </td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ $item->berat }} Kg</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status == 'menunggu')
                                    <span class="badge rounded-pill alert-warning">Menunggu</span>
                                @elseif($item->status == 'proses')
                                    <span class="badge rounded-pill alert-info">Proses</span>
                                @elseif($item->status == 'selesai')
                                    <span class="badge rounded-pill alert-success">Selesai</span>
                                @elseif($item->status == 'diambil')
                                    <span class="badge rounded-pill alert-secondary">Diambil</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light">
                                        <i class="material-icons md-more_vert"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('staff.cucian.show', $item->id) }}">
                                            <i class="material-icons md-visibility"></i> Lihat Detail
                                        </a>
                                        <a class="dropdown-item" href="{{ route('staff.cucian.edit', $item->id) }}">
                                            <i class="material-icons md-edit"></i> Edit
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="confirmDelete({{ $item->id }}, '{{ $item->no_order }}')">
                                            <i class="material-icons md-delete"></i> Hapus
                                        </a>
                                    </div>
                                </div>

                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('staff.cucian.destroy', $item->id) }}" 
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
                                <p class="text-muted mt-3">Belum ada data cucian</p>
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
                    <p class="mb-0">Showing {{ $cucian->firstItem() ?? 0 }} to {{ $cucian->lastItem() ?? 0 }} of {{ $cucian->total() }} entries</p>
                </div>
                <div class="col-md-6">
                    <nav class="float-end">
                        {{ $cucian->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
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