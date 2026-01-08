@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Laporan</h2>
            <p>Laporan Cucian {{ $jenis_order ?? 'Selesai' }}</p>
        </div>
        <div class="col-lg-4 col-md-6">
            <form action="{{ route('admin.dashboard') }}" method="GET" id="searchForm">
                @if(request()->filled('jenis_order'))
                    <input type="hidden" name="jenis_order" value="{{ request()->jenis_order }}">
                @endif
                @if(request()->filled('jenis_ambil'))
                    <input type="hidden" name="jenis_ambil" value="{{ request()->jenis_ambil }}">
                @endif
                @if(request()->filled('paginate'))
                    <input type="hidden" name="paginate" value="{{ request()->paginate }}">
                @endif
                
                <input type="text" 
                       name="search" 
                       placeholder="Cari No. Order/Nama Pelanggan..." 
                       class="form-control bg-white" 
                       id="search" 
                       value="{{ request()->query('search') }}" />
            </form>
        </div>
    </div>
    </div>
        @if($stokMenipis->isNotEmpty())
        <div class="alert alert-warning mb-4">
            <h5><i class="material-icons md-warning"></i> Stok Bahan Menipis!</h5>
            <ul class="mb-0">
                @foreach($stokMenipis as $stok)
                <li>{{ $stok->jenis_bahan }} ({{ $stok->merk }}) - Sisa: <strong>{{ $stok->stok_tersedia }} {{ $stok->satuan }}</strong></li>
                @endforeach
            </ul>
        </div>
        @endif
    
    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('admin.dashboard') }}" method="GET">
                @if(request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request()->search }}">
                @endif
                
                <div class="row gx-3 gy-3">
                    <div class="col-lg-3 col-md-3 col-6">
                        <select class="form-select" name="jenis_order">
                            <option {{ request()->query('jenis_order') == '' ? 'selected' : '' }} value=''>Semua Jenis Order</option>
                            <option {{ request()->query('jenis_order') == 'online' ? 'selected' : '' }} value="online">Cucian Online</option>
                            <option {{ request()->query('jenis_order') == 'offline' ? 'selected' : '' }} value="offline">Cucian Offline</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6">
                        <select class="form-select" name="jenis_ambil">
                            <option {{ request()->query('jenis_ambil') == '' ? 'selected' : '' }} value=''>Semua Jenis Ambil</option>
                            <option {{ request()->query('jenis_ambil') == 'diantar' ? 'selected' : '' }} value="diantar">Diantar</option>
                            <option {{ request()->query('jenis_ambil') == 'ambil_sendiri' ? 'selected' : '' }} value="ambil_sendiri">Ambil Sendiri</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6">
                        <select class="form-select" name="paginate">
                            <option {{ request()->query('paginate') == 15 ? 'selected' : '' }} value="15">Show 15</option>
                            <option {{ request()->query('paginate') == 30 ? 'selected' : '' }} value="30">Show 30</option>
                            <option {{ request()->query('paginate') == 50 ? 'selected' : '' }} value="50">Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="material-icons md-filter_list"></i> Terapkan
                        </button>
                        @if(request()->anyFilled(['search', 'jenis_order', 'jenis_ambil', 'paginate']))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">
                                <i class="material-icons md-close"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </header>
        
        <div class="card-body">
            @if($cucian->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data cucian yang ditemukan
                    @if(request()->filled('search'))
                        untuk pencarian "<strong>{{ request()->search }}</strong>"
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Nama Pelanggan</th>
                                <th>Jenis Order</th>
                                <th>Total Item</th>
                                <th>Jenis Ambil</th>
                                <th>Waktu Diambil</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cucian as $c)
                            <tr>
                                <td><strong>{{ $c->getNoOrder() }}</strong></td>
                                <td>{{ $c->pelanggan->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $c->jenis_order == 'online' ? 'bg-success' : 'bg-info' }}">
                                        {{ ucfirst($c->jenis_order) }}
                                    </span>
                                </td>
                                <td>{{ $c->total_item }}</td>
                                <td>
                                    <span class="badge {{ $c->jenis_ambil == 'diantar' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $c->jenis_ambil)) }}
                                    </span>
                                </td>
                                <td>{{ $c->tgl_diambil ? \Carbon\Carbon::parse($c->tgl_diambil)->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.dashboard.detail', $c->cucian_id) }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons md-visibility"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    
    
    @if($cucian->hasPages())
        <div class="pagination-area mt-15 mb-50">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-start">
                    {{ $cucian->links() }}
                </ul>
            </nav>
        </div>
    @endif
</section>

<script>
    document.getElementById('search').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
</script>

@endsection