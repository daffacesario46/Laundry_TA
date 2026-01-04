@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Layanan</h2>
            <p>Kelola layanan laundry yang tersedia</p>
        </div>
        <div>
            <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">
                <i class="material-icons md-add"></i> Tambah Layanan
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.layanan.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari layanan..." 
                               class="form-control" 
                               value="{{ request()->query('search') }}" />
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="jenis_cucian">
                            <option value="">Semua Jenis</option>
                            <option {{ request()->query('jenis_cucian') == 'kiloan' ? 'selected' : '' }} value="kiloan">Kiloan</option>
                            <option {{ request()->query('jenis_cucian') == 'satuan' ? 'selected' : '' }} value="satuan">Satuan</option>
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
                    @if(request()->anyFilled(['search', 'jenis_cucian', 'paginate']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('admin.layanan.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($layanan->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data layanan yang ditemukan
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
                                <th>Nama Layanan</th>
                                <th>Jenis Cucian</th>
                                <th>Durasi</th>
                                <th>Deskripsi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($layanan as $index => $item)
                            <tr>
                                <td>{{ $layanan->firstItem() + $index }}</td>
                                <td><strong>{{ $item->nama_layanan }}</strong></td>
                                <td>
                                    <span class="badge {{ $item->isKiloan() ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($item->jenis_cucian) }}
                                    </span>
                                </td>
                                <td>{{ $item->durasi_hari }} hari</td>
                                <td>
                                    @if($item->deskripsi)
                                        {{ Str::limit($item->deskripsi, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-sm btn-light">
                                            <i class="material-icons md-more_horiz"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.layanan.edit', $item->layanan_id) }}">
                                                <i class="material-icons md-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.layanan.destroy', $item->layanan_id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
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

    @if($layanan->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $layanan->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

@endsection