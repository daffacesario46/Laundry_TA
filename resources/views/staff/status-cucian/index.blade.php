@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Status Cucian</h2>
            <p>Konfirmasi dan update status cucian pelanggan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-warning-light">
                        <i class="text-warning material-icons md-pending_actions"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Menunggu Konfirmasi</h6>
                        <span class="h4">{{ $totalMenunggu }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-info-light">
                        <i class="text-info material-icons md-autorenew"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Sedang Proses</h6>
                        <span class="h4">{{ $totalProses }}</span>
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
                        <h6 class="mb-1">Selesai</h6>
                        <span class="h4">{{ $totalSelesai }}</span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body">
                <article class="icontext">
                    <span class="icon icon-sm rounded-circle bg-secondary-light">
                        <i class="text-secondary material-icons md-done_all"></i>
                    </span>
                    <div class="text">
                        <h6 class="mb-1">Sudah Diambil</h6>
                        <span class="h4">{{ $totalDiambil }}</span>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <form method="GET" action="{{ route('staff.status-cucian.index') }}">
                        <input type="text" name="search" placeholder="Cari no order atau nama pelanggan..." 
                               class="form-control" value="{{ request('search') }}">
                    </form>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <form method="GET" action="{{ route('staff.status-cucian.index') }}">
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
                    <form method="GET" action="{{ route('staff.status-cucian.index') }}">
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
                            <th>Layanan</th>
                            <th>Berat</th>
                            <th>Tanggal Masuk</th>
                            <th>Estimasi Selesai</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cucian as $item)
                        <tr>
                            <td><b>{{ $item->no_order }}</b></td>
                            <td>
                                <b>{{ $item->nama_pelanggan }}</b><br>
                                <small class="text-muted">
                                    <i class="material-icons md-phone" style="font-size: 14px;"></i> 
                                    {{ $item->no_telp }}
                                </small>
                            </td>
                            <td>{{ $item->jenis_layanan }}</td>
                            <td>{{ $item->berat }} Kg</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->estimasi_selesai)->format('d/m/Y H:i') }}<br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->estimasi_selesai)->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                @if($item->status == 'menunggu')
                                    <span class="badge rounded-pill alert-warning">
                                        <i class="material-icons md-pending_actions" style="font-size: 14px;"></i> 
                                        Menunggu
                                    </span>
                                @elseif($item->status == 'proses')
                                    <span class="badge rounded-pill alert-info">
                                        <i class="material-icons md-autorenew" style="font-size: 14px;"></i> 
                                        Proses
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="badge rounded-pill alert-success">
                                        <i class="material-icons md-check_circle" style="font-size: 14px;"></i> 
                                        Selesai
                                    </span>
                                @elseif($item->status == 'diambil')
                                    <span class="badge rounded-pill alert-secondary">
                                        <i class="material-icons md-done_all" style="font-size: 14px;"></i> 
                                        Diambil
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($item->status == 'menunggu')
                                    <button class="btn btn-sm btn-success" onclick="updateStatus({{ $item->id }}, 'proses', '{{ $item->no_order }}')">
                                        <i class="material-icons md-play_arrow"></i> Proses
                                    </button>
                                @elseif($item->status == 'proses')
                                    <button class="btn btn-sm btn-primary" onclick="updateStatus({{ $item->id }}, 'selesai', '{{ $item->no_order }}')">
                                        <i class="material-icons md-check"></i> Selesai
                                    </button>
                                @elseif($item->status == 'selesai')
                                    <button class="btn btn-sm btn-secondary" onclick="updateStatus({{ $item->id }}, 'diambil', '{{ $item->no_order }}')">
                                        <i class="material-icons md-shopping_bag"></i> Diambil
                                    </button>
                                @else
                                    <span class="badge bg-secondary">Selesai</span>
                                @endif
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

    <!-- Panduan Status -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="text-white mb-0">
                <i class="material-icons md-info"></i> Panduan Status Cucian
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-warning-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-warning material-icons md-pending_actions" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Menunggu</h6>
                        <p class="text-muted small">Cucian baru masuk, belum diproses</p>
                        <button class="btn btn-sm btn-warning">Action: Proses</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-info-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-info material-icons md-autorenew" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Proses</h6>
                        <p class="text-muted small">Cucian sedang dikerjakan</p>
                        <button class="btn btn-sm btn-info">Action: Selesai</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-success-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-success material-icons md-check_circle" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Selesai</h6>
                        <p class="text-muted small">Cucian sudah selesai, siap diambil</p>
                        <button class="btn btn-sm btn-success">Action: Diambil</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-secondary-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-secondary material-icons md-done_all" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Diambil</h6>
                        <p class="text-muted small">Cucian sudah diambil pelanggan</p>
                        <span class="badge bg-secondary">Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function updateStatus(id, status, noOrder) {
        let statusText = {
            'proses': 'Proses',
            'selesai': 'Selesai',
            'diambil': 'Diambil'
        };

        let icon = {
            'proses': 'info',
            'selesai': 'success',
            'diambil': 'success'
        };

        Swal.fire({
            title: `Konfirmasi Status: ${statusText[status]}`,
            text: `Ubah status cucian ${noOrder} menjadi ${statusText[status]}?`,
            icon: icon[status],
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("staff.status-cucian.konfirmasi", ":id") }}'.replace(':id', id);
                
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                let statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                
                form.appendChild(csrfToken);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection