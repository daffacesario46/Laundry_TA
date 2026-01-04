@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Manajemen Pembayaran</h2>
            <p>Kelola pembayaran cucian pelanggan</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                {{-- Filter Status --}}
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="filter-status" onchange="filterData()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                {{-- Filter Metode --}}
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="filter-metode" onchange="filterData()">
                        <option value="all" {{ request('metode') == 'all' ? 'selected' : '' }}>Semua Metode</option>
                        <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-lg-6 col-md-12 mb-3">
                    <label class="form-label">Cari Pelanggan</label>
                    <form action="{{ route('staff.pembayaran.index') }}" method="GET" class="input-group">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <input type="hidden" name="metode" value="{{ request('metode', 'all') }}">
                        <input type="text" class="form-control" name="search" placeholder="Cari nama pelanggan..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="icon material-icons md-search"></i> Cari
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="card-body">
            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-body bg-warning-light">
                        <h6 class="text-warning">Belum Bayar</h6>
                        <h4 class="mb-0">{{ $pembayaran->where('status_bayar', 'belum')->count() }}</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-body bg-success-light">
                        <h6 class="text-success">Lunas</h6>
                        <h4 class="mb-0">{{ $pembayaran->where('status_bayar', 'lunas')->count() }}</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-body bg-info-light">
                        <h6 class="text-info">Total Pembayaran</h6>
                        <h4 class="mb-0">Rp {{ number_format($pembayaran->where('status_bayar', 'lunas')->sum('jumlah_bayar'), 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tgl Bayar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $item)
                        <tr>
                            <td><strong>{{ $item->cucian->getNoOrder() }}</strong></td>
                            <td>
                                <strong>{{ $item->cucian->pelanggan->nama }}</strong><br>
                                <small class="text-muted">{{ $item->cucian->jenis_order }}</small>
                            </td>
                            <td><strong>{{ $item->getFormattedJumlahBayar() }}</strong></td>
                            <td>
                                <span class="badge rounded-pill {{ $item->metode_bayar == 'cash' ? 'alert-success' : 'alert-info' }}">
                                    {{ $item->getMetodeBayarLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $item->getStatusBadge() }}">
                                    {{ $item->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                @if($item->tgl_bayar)
                                    {{ $item->tgl_bayar->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('staff.pembayaran.show', $item->pembayaran_id) }}" 
                                   class="btn btn-sm btn-light" title="Detail">
                                    <i class="icon material-icons md-visibility"></i>
                                </a>
                                
                                @if($item->metode_bayar == 'transfer' && $item->status_bayar == 'belum' && $item->bukti_bayar)
                                    <a href="{{ route('staff.pembayaran.validate-form', $item->pembayaran_id) }}" 
                                       class="btn btn-sm btn-warning" title="Validasi">
                                        <i class="icon material-icons md-check"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Tidak ada data pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $pembayaran->withQueryString()->links() }}
            </div>
        </div>
    </div>
</section>

<script>
function filterData() {
    const status = document.getElementById('filter-status').value;
    const metode = document.getElementById('filter-metode').value;
    const search = '{{ request("search") }}';
    
    let url = '{{ route("staff.pembayaran.index") }}?status=' + status + '&metode=' + metode;
    if (search) {
        url += '&search=' + search;
    }
    
    window.location.href = url;
}
</script>
@endsection