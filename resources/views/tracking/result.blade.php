@extends('layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <!-- Quick Search -->
        <div class="row justify-content-center mb-4 wow fadeInUp" data-wow-delay="0.1s">
            <div class="col-lg-8">
                <form action="{{ route('tracking.track') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-lg shadow-sm" style="border-radius: 50px; overflow: hidden;">
                        <span class="input-group-text bg-white border-0 ps-4">
                            <i class="fa fa-barcode text-primary"></i>
                        </span>
                        <input class="form-control border-0 ps-2" 
                               name="no_order" 
                               type="text" 
                               placeholder="Cari order lain..."
                               value="{{ $cucian->getNoOrder() }}">
                        <button class="btn btn-primary px-5 border-0" type="submit" style="border-radius: 0 50px 50px 0;">
                            <i class="fa fa-search me-2"></i>Lacak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg mb-4 wow fadeInUp" data-wow-delay="0.2s" style="border-radius: 20px; overflow: hidden;">
                    <!-- Status Header dengan Gradient -->
                    @php
                        $headerClass = match($cucian->status_cucian) {
                            'menunggu' => 'bg-gradient-warning',
                            'diproses' => 'bg-gradient-info',
                            'selesai' => 'bg-gradient-success',
                            'diambil' => 'bg-gradient-secondary',
                            default => 'bg-gradient-primary'
                        };
                    @endphp
                    <div class="{{ $headerClass }}" style="height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>

                    <div class="card-body p-4">
                        <!-- Logo & Order Info -->
                        <div class="row align-items-end mb-4" style="margin-top: -80px;">
                            <div class="col-auto">
                                <div class="bg-white shadow-lg p-3 rounded-4" style="width: 130px; height: 130px;">
                                    <img src="{{ asset('admins/imgs/theme/washwes.png') }}" 
                                         class="img-fluid" 
                                         alt="Washwes Logo">
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="mb-1 text-white fw-bold">{{ $cucian->getNoOrder() }}</h2>
                                <p class="mb-0 text-white-50">
                                    <i class="fa fa-user me-2"></i>{{ $cucian->pelanggan->nama ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-auto text-end">
                                @php
                                    $badgeClass = match($cucian->status_cucian) {
                                        'menunggu' => 'bg-warning',
                                        'diproses' => 'bg-info',
                                        'selesai' => 'bg-success',
                                        'diambil' => 'bg-secondary',
                                        default => 'bg-primary'
                                    };
                                    $iconClass = match($cucian->status_cucian) {
                                        'menunggu' => 'fa-clock',
                                        'diproses' => 'fa-sync fa-spin',
                                        'selesai' => 'fa-check-circle',
                                        'diambil' => 'fa-check-double',
                                        default => 'fa-info-circle'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-4 py-3 fs-5 shadow">
                                    <i class="fa {{ $iconClass }} me-2"></i>{{ $cucian->getStatusLabel() }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Info Cards Grid -->
                        <div class="row g-3 mb-4">
                            <!-- Layanan -->
                            <div class="col-md-3">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-tags text-primary fs-3 mb-2"></i>
                                        <p class="text-muted small mb-1">Jenis Layanan</p>
                                        <h6 class="mb-0 fw-bold">{{ $cucian->layanan->nama_layanan ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Berat/Item -->
                            <div class="col-md-3">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-weight text-info fs-3 mb-2"></i>
                                        <p class="text-muted small mb-1">
                                            {{ $cucian->layanan && $cucian->layanan->jenis_cucian === 'kiloan' ? 'Berat' : 'Total Item' }}
                                        </p>
                                        <h6 class="mb-0 fw-bold">
                                            @if($cucian->layanan && $cucian->layanan->jenis_cucian === 'kiloan')
                                                {{ $cucian->total_berat ? number_format($cucian->total_berat, 1) . ' Kg' : 'Belum ditimbang' }}
                                            @else
                                                {{ $cucian->total_item }} Item
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Harga -->
                            <div class="col-md-3">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-money-bill-wave text-success fs-3 mb-2"></i>
                                        <p class="text-muted small mb-1">Total Harga</p>
                                        <h6 class="mb-0 fw-bold text-success">
                                            {{ $cucian->getFormattedTotalHarga() }}
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Bayar -->
                            <div class="col-md-3">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fa fa-credit-card text-warning fs-3 mb-2"></i>
                                        <p class="text-muted small mb-1">Pembayaran</p>
                                        @if($cucian->pembayaran)
                                            <span class="badge {{ $cucian->pembayaran->status_bayar === 'lunas' ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                                {{ $cucian->pembayaran->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">
                                    <i class="fa fa-route text-primary me-2"></i>Timeline Pesanan
                                </h5>

                                <div class="timeline-wrapper">
                                    @php
                                        $isOnline = $cucian->jenis_order === 'online';
                                        $needsDelivery = $cucian->jenis_ambil === 'diantar';
                                        
                                        $steps = [
                                            [
                                                'title' => 'Order Diterima',
                                                'icon' => 'fa-check-circle',
                                                'status' => 'completed',
                                                'date' => $cucian->tgl_order->format('d M Y'),
                                                'time' => $cucian->tgl_order->format('H:i'),
                                                'color' => 'success'
                                            ]
                                        ];
                                        
                                        // Jika online, tambah step penjemputan
                                        if ($isOnline) {
                                            $penjemputanStatus = 'pending';
                                            $penjemputanDate = '-';
                                            $penjemputanTime = '-';
                                            
                                            if ($cucian->penjemputan) {
                                                if ($cucian->penjemputan->status === 'selesai') {
                                                    $penjemputanStatus = 'completed';
                                                    $penjemputanDate = $cucian->penjemputan->updated_at->format('d M Y');
                                                    $penjemputanTime = $cucian->penjemputan->updated_at->format('H:i');
                                                } elseif ($cucian->penjemputan->status === 'diproses') {
                                                    $penjemputanStatus = 'active';
                                                }
                                            }
                                            
                                            $steps[] = [
                                                'title' => 'Dijemput Kurir',
                                                'icon' => 'fa-truck',
                                                'status' => $penjemputanStatus,
                                                'date' => $penjemputanDate,
                                                'time' => $penjemputanTime,
                                                'color' => 'info',
                                                'subtitle' => $cucian->penjemputan && $cucian->penjemputan->staff ? 
                                                    'Kurir: ' . $cucian->penjemputan->staff->nama : null
                                            ];
                                        }
                                        
                                        // Step diproses
                                        $prosesStatus = in_array($cucian->status_cucian, ['diproses', 'selesai', 'diambil']) ? 'completed' : 
                                            ($cucian->status_cucian === 'diproses' ? 'active' : 'pending');
                                        $steps[] = [
                                            'title' => 'Sedang Diproses',
                                            'icon' => 'fa-sync',
                                            'status' => $prosesStatus,
                                            'date' => $prosesStatus !== 'pending' ? $cucian->updated_at->format('d M Y') : '-',
                                            'time' => $prosesStatus !== 'pending' ? $cucian->updated_at->format('H:i') : '-',
                                            'color' => 'warning'
                                        ];
                                        
                                        // Step selesai
                                        $selesaiStatus = in_array($cucian->status_cucian, ['selesai', 'diambil']) ? 'completed' : 
                                            ($cucian->status_cucian === 'selesai' ? 'active' : 'pending');
                                        $steps[] = [
                                            'title' => 'Cucian Selesai',
                                            'icon' => 'fa-check-double',
                                            'status' => $selesaiStatus,
                                            'date' => $cucian->tgl_selesai ? $cucian->tgl_selesai->format('d M Y') : '-',
                                            'time' => $cucian->tgl_selesai ? $cucian->tgl_selesai->format('H:i') : '-',
                                            'color' => 'success'
                                        ];
                                        
                                        // Jika perlu diantar
                                        if ($needsDelivery) {
                                            $antarStatus = 'pending';
                                            $antarDate = '-';
                                            $antarTime = '-';
                                            $antarSubtitle = null;
                                            
                                            if ($cucian->pengantaran) {
                                                if ($cucian->pengantaran->status === 'selesai') {
                                                    $antarStatus = 'completed';
                                                    $antarDate = $cucian->pengantaran->updated_at->format('d M Y');
                                                    $antarTime = $cucian->pengantaran->updated_at->format('H:i');
                                                } elseif ($cucian->pengantaran->status === 'diproses') {
                                                    $antarStatus = 'active';
                                                    $antarDate = $cucian->pengantaran->tgl_berangkat ? $cucian->pengantaran->tgl_berangkat->format('d M Y') : '-';
                                                    $antarTime = $cucian->pengantaran->tgl_berangkat ? $cucian->pengantaran->tgl_berangkat->format('H:i') : '-';
                                                }
                                                
                                                if ($cucian->pengantaran->kurir) {
                                                    $antarSubtitle = 'Kurir: ' . $cucian->pengantaran->kurir->nama;
                                                }
                                            }
                                            
                                            $steps[] = [
                                                'title' => 'Sedang Diantar',
                                                'icon' => 'fa-shipping-fast',
                                                'status' => $antarStatus,
                                                'date' => $antarDate,
                                                'time' => $antarTime,
                                                'color' => 'primary',
                                                'subtitle' => $antarSubtitle
                                            ];
                                        }
                                        
                                        // Step terakhir
                                        $steps[] = [
                                            'title' => $needsDelivery ? 'Diterima' : 'Diambil',
                                            'icon' => 'fa-flag-checkered',
                                            'status' => $cucian->status_cucian === 'diambil' ? 'completed' : 'pending',
                                            'date' => $cucian->tgl_diambil ? $cucian->tgl_diambil->format('d M Y') : '-',
                                            'time' => $cucian->tgl_diambil ? $cucian->tgl_diambil->format('H:i') : '-',
                                            'color' => 'secondary'
                                        ];
                                    @endphp

                                    <div class="timeline">
                                        @foreach($steps as $index => $step)
                                        <div class="timeline-item {{ $step['status'] }}">
                                            <div class="timeline-marker bg-{{ $step['color'] }}">
                                                <i class="fa {{ $step['icon'] }} text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1 fw-bold">{{ $step['title'] }}</h6>
                                                @if(isset($step['subtitle']))
                                                <small class="text-muted d-block mb-1">{{ $step['subtitle'] }}</small>
                                                @endif
                                                <small class="text-muted">
                                                    @if($step['date'] !== '-')
                                                        <i class="fa fa-calendar me-1"></i>{{ $step['date'] }}
                                                        <i class="fa fa-clock ms-2 me-1"></i>{{ $step['time'] }}
                                                    @else
                                                        <i class="fa fa-hourglass-half me-1"></i>Menunggu
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pesanan -->
                        <div class="row g-4 mb-4">
                            <!-- Customer Info -->
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fa fa-user text-primary me-2"></i>Informasi Pelanggan
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama:</small>
                                            <p class="mb-0 fw-bold">{{ $cucian->pelanggan->nama ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">No. Telepon:</small>
                                            <p class="mb-0 fw-bold">{{ $cucian->pelanggan->no_telp ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <small class="text-muted">Alamat:</small>
                                            <p class="mb-0">{{ $cucian->pelanggan->alamat ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Info -->
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fa fa-receipt text-primary me-2"></i>Detail Pesanan
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Tgl Order:</small>
                                            <p class="mb-0 fw-bold">{{ $cucian->tgl_order->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Estimasi Selesai:</small>
                                            <p class="mb-0 fw-bold">
                                                {{ $cucian->estimasi ? $cucian->estimasi->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                        @if($cucian->tgl_selesai)
                                        <div class="mb-2">
                                            <small class="text-muted">Selesai:</small>
                                            <p class="mb-0 fw-bold text-success">
                                                {{ $cucian->tgl_selesai->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        @endif
                                        @if($cucian->catatan)
                                        <div class="alert alert-warning p-2 mt-2 mb-0">
                                            <small><strong>Catatan:</strong> {{ $cucian->catatan }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button onclick="window.print()" class="btn btn-outline-primary px-4">
                                <i class="fa fa-print me-2"></i>Cetak
                            </button>
                            <a href="{{ route('tracking.index') }}" class="btn btn-primary px-4">
                                <i class="fa fa-search me-2"></i>Lacak Order Lain
                            </a>
                            @auth
                                @if(Auth::user()->role === 'pelanggan' && $cucian->pelanggan->users_id === Auth::id())
                                <a href="{{ route('pelanggan.order.show', $cucian->cucian_id) }}" class="btn btn-success px-4">
                                    <i class="fa fa-eye me-2"></i>Lihat Detail Lengkap
                                </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 30px;
    bottom: 30px;
    width: 3px;
    background: linear-gradient(to bottom, #e9ecef 0%, #e9ecef 100%);
}

.timeline-item {
    position: relative;
    padding-left: 80px;
    margin-bottom: 40px;
    padding-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border: 4px solid white;
    z-index: 2;
}

.timeline-item.completed .timeline-marker {
    animation: none;
}

.timeline-item.active .timeline-marker {
    animation: pulse 2s infinite;
    box-shadow: 0 4px 20px rgba(0,123,255,0.4);
}

.timeline-item.pending .timeline-marker {
    background: #dee2e6 !important;
    opacity: 0.6;
}

.timeline-item.pending .timeline-content {
    opacity: 0.5;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Gradient backgrounds */
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Print Styles */
@media print {
    .btn, form, .navbar, .footer, .input-group {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    .timeline::before {
        background: #000 !important;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .timeline-item {
        padding-left: 70px;
    }
    .timeline-marker {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    .timeline::before {
        left: 25px;
    }
}
</style>
@endsection