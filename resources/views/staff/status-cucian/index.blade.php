@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Status Cucian</h2>
            <p>Konfirmasi dan update status cucian pelanggan</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="material-icons md-check_circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="material-icons md-error"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                        <small class="text-muted">(Hari ini)</small>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <header class="card-header">
            <form action="{{ route('staff.status-cucian.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <input type="text" name="search" 
                               placeholder="Cari no order atau nama pelanggan..." 
                               class="form-control" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <select class="form-select" name="paginate">
                            <option value="15" {{ request('paginate', 15) == 15 ? 'selected' : '' }}>Show 15</option>
                            <option value="30" {{ request('paginate') == 30 ? 'selected' : '' }}>Show 30</option>
                            <option value="50" {{ request('paginate') == 50 ? 'selected' : '' }}>Show 50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Cari
                        </button>
                    </div>
                    @if(request()->anyFilled(['search', 'status']))
                        <div class="col-lg-2 col-md-3 mb-3">
                            <a href="{{ route('staff.status-cucian.index') }}" class="btn btn-light w-100">
                                <i class="material-icons md-refresh"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </header>

        <div class="card-body">
            @if($cucian->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="material-icons md-inbox" style="font-size: 64px;"></i>
                    <p class="mt-3 mb-0">
                        @if(request()->filled('search') || request()->filled('status'))
                            Tidak ada cucian yang sesuai filter
                        @else
                            Tidak ada cucian yang perlu diproses
                        @endif
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat/Item</th>
                                <th>Total Harga</th>
                                <th>Tanggal Order</th>
                                <th>Estimasi</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cucian as $item)
                            <tr>
                                <td><b>{{ $item->getNoOrder() }}</b></td>
                                <td>
                                    <b>{{ $item->pelanggan->nama ?? 'N/A' }}</b><br>
                                    <small class="text-muted">
                                        <i class="material-icons md-phone" style="font-size: 14px;"></i> 
                                        {{ $item->pelanggan->no_telp ?? '-' }}
                                    </small>
                                </td>
                                <td>{{ $item->layanan->nama_layanan ?? '-' }}</td>
                                <td>
                                    @if($item->total_berat)
                                        {{ number_format($item->total_berat, 1) }} Kg
                                    @else
                                        {{ $item->total_item }} item
                                    @endif
                                </td>
                                <td>{{ $item->getFormattedTotalHarga() }}</td>
                                <td>{{ $item->tgl_order->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($item->estimasi)
                                        {{ \Carbon\Carbon::parse($item->estimasi)->format('d/m/Y H:i') }}<br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->estimasi)->diffForHumans() }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $item->getStatusBadge() }}">
                                        {{ $item->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($item->status_cucian == 'menunggu')
                                        <button class="btn btn-sm btn-info" 
                                                onclick="updateStatus({{ $item->cucian_id }}, 'diproses', '{{ addslashes($item->getNoOrder()) }}')">
                                            <i class="material-icons md-play_arrow"></i> Proses
                                        </button>
                                    @elseif($item->status_cucian == 'diproses')
                                        <button class="btn btn-sm btn-success" 
                                                onclick="updateStatus({{ $item->cucian_id }}, 'selesai', '{{ addslashes($item->getNoOrder()) }}')">
                                            <i class="material-icons md-check"></i> Selesai
                                        </button>
                                    @elseif($item->status_cucian == 'selesai')
                                        <button class="btn btn-sm btn-primary" 
                                                onclick="updateStatus({{ $item->cucian_id }}, 'diambil', '{{ addslashes($item->getNoOrder()) }}')">
                                            <i class="material-icons md-shopping_bag"></i> Diambil
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($cucian->hasPages())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Menampilkan {{ $cucian->firstItem() ?? 0 }} sampai {{ $cucian->lastItem() ?? 0 }} dari {{ $cucian->total() }} data</p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-end">
                            {{ $cucian->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
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
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-warning-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-warning material-icons md-pending_actions" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Menunggu</h6>
                        <p class="text-muted small">Cucian baru masuk, belum diproses</p>
                        <span class="badge bg-info">Klik "Proses"</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-info-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-info material-icons md-autorenew" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Diproses</h6>
                        <p class="text-muted small">Cucian sedang dikerjakan</p>
                        <span class="badge bg-success">Klik "Selesai"</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <span class="icon icon-sm rounded-circle bg-success-light d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="text-success material-icons md-check_circle" style="font-size: 28px;"></i>
                        </span>
                        <h6 class="mt-2">Selesai</h6>
                        <p class="text-muted small">Cucian sudah selesai, siap diambil</p>
                        <span class="badge bg-primary">Klik "Diambil"</span>
                    </div>
                </div>
            </div>
            
            <hr class="my-3">
            
            <div class="alert alert-info mb-0">
                <i class="material-icons md-info"></i>
                <strong>Catatan:</strong> Cucian yang sudah ditandai "Diambil" akan hilang dari daftar ini dan bisa dilihat di menu "Data Cucian".
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateStatus(id, status, noOrder) {
        let statusText = {
            'diproses': 'Proses',
            'selesai': 'Selesai',
            'diambil': 'Diambil'
        };

        let icon = {
            'diproses': 'info',
            'selesai': 'success',
            'diambil': 'question'
        };

        let confirmText = {
            'diproses': `Mulai memproses cucian ${noOrder}?`,
            'selesai': `Tandai cucian ${noOrder} sudah selesai?`,
            'diambil': `Konfirmasi cucian ${noOrder} sudah diambil pelanggan?`
        };

        Swal.fire({
            title: `Update Status: ${statusText[status]}`,
            text: confirmText[status],
            icon: icon[status],
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
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