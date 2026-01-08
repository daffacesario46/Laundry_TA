@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">List Harga</h2>
            <p>Kelola daftar harga layanan laundry</p>
        </div>
        <div>
            <a href="{{ route('admin.list-harga.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah List Harga
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.list-harga.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari nama item..." 
                               class="form-control" 
                               value="{{ request()->query('search') }}" />
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
                    @if(request()->anyFilled(['search', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('admin.list-harga.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($listHarga->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data list harga yang ditemukan
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
                                <th>Nama Item</th>
                                <th>Harga Satuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listHarga as $index => $item)
                            <tr>
                                <td>{{ $listHarga->firstItem() + $index }}</td>
                                <td><strong>{{ $item->nama_item }}</strong></td>
                                <td>{{ $item->getFormattedHargaSatuan() }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-light">
                                            <i class="material-icons md-more_horiz"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.list-harga.edit', $item->list_harga_id) }}">
                                                <i class="material-icons md-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.list-harga.destroy', $item->list_harga_id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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

    @if($listHarga->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $listHarga->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

@endsection