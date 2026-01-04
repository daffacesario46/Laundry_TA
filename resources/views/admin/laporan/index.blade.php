@extends('admin.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Laporan Keuangan</h2>
            <p>Laporan Penghasilan {{ $periodeText }}</p>
        </div>
        <div>
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" 
               class="btn btn-danger" target="_blank">
                <i class="material-icons md-picture_as_pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.laporan.export-excel', request()->query()) }}" 
               class="btn btn-success">
                <i class="material-icons md-description"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.laporan.index') }}" method="GET">
                <div class="row gx-3">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">Tipe Filter</label>
                        <select class="form-select" name="filter_type" id="filter_type" onchange="toggleDateFilter()">
                            <option value="hari" {{ $filterType == 'hari' ? 'selected' : '' }}>Per Hari</option>
                            <option value="bulan" {{ $filterType == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3" id="filter-hari" style="{{ $filterType == 'hari' ? '' : 'display:none;' }}">
                        <label class="form-label">Pilih Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ $tanggal }}" max="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3" id="filter-bulan" style="{{ $filterType == 'bulan' ? '' : 'display:none;' }}">
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" name="bulan" value="{{ $bulan }}" max="{{ now()->format('Y-m') }}">
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons md-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-body bg-primary-light">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Total Penghasilan</h6>
                        <h4 class="mb-0 text-primary">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</h4>
                    </div>
                    <div class="icon-box icon-box-lg bg-primary text-white">
                        <i class="material-icons md-attach_money"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-body bg-success-light">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Total Transaksi</h6>
                        <h4 class="mb-0 text-success">{{ $stats['total_transaksi'] }}</h4>
                    </div>
                    <div class="icon-box icon-box-lg bg-success text-white">
                        <i class="material-icons md-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-body bg-warning-light">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Rata-rata Transaksi</h6>
                        <h4 class="mb-0 text-warning">Rp {{ number_format($stats['rata_rata_transaksi'], 0, ',', '.') }}</h4>
                    </div>
                    <div class="icon-box icon-box-lg bg-warning text-white">
                        <i class="material-icons md-equalizer"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-body bg-info-light">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Periode</h6>
                        <h6 class="mb-0 text-info">{{ $periodeText }}</h6>
                    </div>
                    <div class="icon-box icon-box-lg bg-info text-white">
                        <i class="material-icons md-calendar_today"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart (hanya untuk filter bulan) --}}
    @if($filterType == 'bulan' && $chartData)
    <div class="card mb-4">
        <div class="card-header">
            <h5>Grafik Penghasilan Harian</h5>
        </div>
        <div class="card-body">
            <canvas id="chartPenghasilan" height="80"></canvas>
        </div>
    </div>
    @endif

    <div class="row">
        {{-- Breakdown Per Layanan --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Laporan per Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Layanan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($breakdownLayanan as $item)
                                <tr>
                                    <td><strong>{{ $item['layanan'] }}</strong></td>
                                    <td class="text-center">{{ $item['jumlah'] }} transaksi</td>
                                    <td class="text-end text-primary">
                                        <strong>Rp {{ number_format($item['total'], 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTAL</th>
                                    <th class="text-center">{{ $stats['total_transaksi'] }}</th>
                                    <th class="text-end text-primary">
                                        <h5 class="mb-0">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breakdown Per Metode & Jenis Order --}}
        <div class="col-lg-6 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Laporan per Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Metode</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($breakdownMetode as $item)
                                <tr>
                                    <td>
                                        <span class="badge {{ $item['metode'] == 'cash' ? 'alert-success' : 'alert-info' }}">
                                            {{ ucfirst($item['metode']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item['jumlah'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Laporan per Jenis Order</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($breakdownJenisOrder as $item)
                                <tr>
                                    <td>
                                        <span class="badge {{ $item['jenis'] == 'Online' ? 'alert-success' : 'alert-info' }}">
                                            {{ $item['jenis'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item['jumlah'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Transaksi --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tgl Bayar</th>
                            <th>No. Order</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Metode</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $item)
                        <tr>
                            <td>{{ $item->tgl_bayar->format('d M Y H:i') }}</td>
                            <td><strong>{{ $item->cucian->getNoOrder() }}</strong></td>
                            <td>{{ $item->cucian->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $item->cucian->layanan->nama_layanan ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $item->metode_bayar == 'cash' ? 'alert-success' : 'alert-info' }}">
                                    {{ ucfirst($item->metode_bayar) }}
                                </span>
                            </td>
                            <td class="text-end text-primary">
                                <strong>Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada transaksi dalam periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleDateFilter() {
    const filterType = document.getElementById('filter_type').value;
    document.getElementById('filter-hari').style.display = filterType === 'hari' ? 'block' : 'none';
    document.getElementById('filter-bulan').style.display = filterType === 'bulan' ? 'block' : 'none';
}

@if($filterType == 'bulan' && $chartData)
// Chart Penghasilan
const ctx = document.getElementById('chartPenghasilan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Penghasilan (Rp)',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
@endif
</script>
@endsection