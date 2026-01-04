@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Data Pengantaran</h2>
            <p>Kelola pengantaran cucian ke pelanggan</p>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('staff.pengantaran.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" name="search" 
                               placeholder="Cari pelanggan atau no order..." 
                               class="form-control" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-3 col-md-3 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diantar</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Cari
                        </button>
                    </div>
                    @if(request()->anyFilled(['search', 'status']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('staff.pengantaran.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($pengantaran->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="material-icons md-info"></i>
                    Tidak ada data pengantaran
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Alamat Antar</th>
                                <th>Kurir</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengantaran as $item)
                            <tr>
                                <td><b>{{ $item->cucian->getNoOrder() }}</b></td>
                                <td>
                                    <b>{{ $item->cucian->pelanggan->nama ?? 'N/A' }}</b><br>
                                    <small class="text-muted">{{ $item->cucian->pelanggan->no_telp ?? '-' }}</small>
                                </td>
                                <td>{{ Str::limit($item->alamat_antar, 30) }}</td>
                                <td>
                                    @if($item->hasKurir())
                                        <span class="badge bg-success">{{ $item->getKurirNama() }}</span>
                                    @else
                                        <span class="badge bg-warning">Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $item->getStatusBadge() }}">
                                        {{ $item->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('staff.pengantaran.show', $item->pengantaran_id) }}" 
                                       class="btn btn-sm btn-light" title="Detail">
                                        <i class="material-icons md-visibility"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($pengantaran->hasPages())
            <div class="card-footer">
                {{ $pengantaran->links() }}
            </div>
        @endif
    </div>
</section>
@endsection