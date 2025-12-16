@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Stok Bahan</h2>
            <p>Kelola stok bahan laundry</p>
        </div>
        <div>
            <a href="{{ route('admin.stok-bahan.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah Stok Bahan
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.stok-bahan.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari stok bahan..." 
                               class="form-control" 
                               value="{{ request()->query('search') }}" />
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="jenis_bahan">
                            <option value="">Semua Jenis</option>
                            <option {{ request()->query('jenis_bahan') == 'detergen' ? 'selected' : '' }} value="detergen">Detergen</option>
                            <option {{ request()->query('jenis_bahan') == 'pewangi' ? 'selected' : '' }} value="pewangi">Pewangi</option>
                            <option {{ request()->query('jenis_bahan') == 'pelembut' ? 'selected' : '' }} value="pelembut">Pelembut</option>
                            <option {{ request()->query('jenis_bahan') == 'pemutih' ? 'selected' : '' }} value="pemutih">Pemutih</option>
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
                    @if(request()->anyFilled(['search', 'jenis_bahan', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('admin.stok-bahan.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($stokBahan->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data stok bahan yang ditemukan
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
                                <th>Jenis Bahan</th>
                                <th>Merk</th>
                                <th>Stok Tersedia</th>
                                <th>Satuan</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokBahan as $index => $item)
                            <tr>
                                <td>{{ $stokBahan->firstItem() + $index }}</td>
                                <td><strong>{{ ucfirst($item->jenis_bahan) }}</strong></td>
                                <td>{{ $item->merk }}</td>
                                <td>
                                    <strong>{{ $item->stok_tersedia }}</strong>
                                </td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ $item->stok_minimum }}</td>
                                <td>
                                    @if($item->stok_tersedia <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($item->stok_tersedia <= $item->stok_minimum)
                                        <span class="badge bg-warning text-dark">Menipis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-light">
                                            <i class="material-icons md-more_horiz"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.stok-bahan.edit', $item->stok_bahan_id) }}">
                                                <i class="material-icons md-edit"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.stok-bahan.show', $item->stok_bahan_id) }}">
                                                <i class="material-icons md-visibility"></i> Detail
                                            </a>
                                            <form action="{{ route('admin.stok-bahan.destroy', $item->stok_bahan_id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus stok bahan ini?')">
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

    @if($stokBahan->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $stokBahan->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

@endsection