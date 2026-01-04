@extends('admin.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Manajemen Kurir</h2>
            <p>Kelola data kurir laundry</p>
        </div>
        <div>
            <a href="{{ route('admin.kurir.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah Kurir
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.kurir.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari nama atau email..." 
                               class="form-control" 
                               value="{{ request('search') }}" />
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
                            <option value="15" {{ request('paginate') == 15 ? 'selected' : '' }}>Show 15</option>
                            <option value="30" {{ request('paginate') == 30 ? 'selected' : '' }}>Show 30</option>
                            <option value="50" {{ request('paginate') == 50 ? 'selected' : '' }}>Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Filter
                        </button>
                    </div>
                    @if(request()->hasAny(['search', 'status']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('admin.kurir.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <!-- Table -->
        <div class="card-body">
            @if($kurir->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data kurir
                    @if(request('search'))
                        untuk pencarian "<strong>{{ request('search') }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kurir as $index => $item)
                            <tr>
                                <td>{{ $kurir->firstItem() + $index }}</td>
                                <td>
                                    @if($item->foto)
                                        <img src="{{ Storage::url($item->foto) }}" 
                                             class="img-sm rounded-circle" 
                                             alt="{{ $item->nama }}" />
                                    @else
                                        <div class="icon-shape icon-sm rounded-circle bg-light">
                                            <i class="material-icons md-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->nama }}</strong><br>
                                    <small class="text-muted">{{ $item->no_wa ?? '-' }}</small>
                                </td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_telp ?? '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $item->status == 'aktif' ? 'alert-success' : 'alert-danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-light btn-sm">
                                            <i class="material-icons md-more_horiz"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('admin.kurir.edit', $item->users_id) }}">
                                                <i class="material-icons md-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.kurir.toggle-status', $item->users_id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin mengubah status kurir ini?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="material-icons md-{{ $item->status == 'aktif' ? 'block' : 'check_circle' }}"></i>
                                                    {{ $item->status == 'aktif' ? 'Non-aktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <hr class="dropdown-divider">
                                            <form action="{{ route('admin.kurir.destroy', $item->users_id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kurir ini? Data tidak dapat dikembalikan!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="material-icons md-delete_forever"></i> Hapus
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

    <!-- Pagination -->
    @if($kurir->hasPages())
        <div class="pagination-area mt-30 mb-50">
            {{ $kurir->appends(request()->query())->links() }}
        </div>
    @endif
</section>
@endsection