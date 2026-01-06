@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Detail Cucian</h2>
            <p>Informasi lengkap cucian {{ $cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
            <a href="{{ route('staff.cucian.edit', $cucian->cucian_id) }}" class="btn btn-primary">
                <i class="material-icons md-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Cucian -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Cucian</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">No Order</p>
                            <h5>{{ $cucian->getNoOrder() }}</h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Status</p>
                            <h5>
                                <span class="badge rounded-pill {{ $cucian->getStatusBadge() }}">
                                    {{ $cucian->getStatusLabel() }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Tanggal Order</p>
                            <h5>{{ $cucian->tgl_order->format('d M Y H:i') }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Order</p>
                            <p class="fw-bold">{{ ucfirst($cucian->jenis_order) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Cucian</p>
                            <p class="fw-bold">
                                @if($cucian->layanan)
                                    <span class="badge {{ $cucian->layanan->jenis_cucian == 'kiloan' ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($cucian->layanan->jenis_cucian) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Jenis Pengambilan</p>
                            <p class="fw-bold">{{ $cucian->jenis_ambil == 'diantar' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Estimasi Selesai</p>
                            <p class="fw-bold">{{ $cucian->estimasi ? $cucian->estimasi->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Data Pelanggan</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-person"></i> Nama Pelanggan</p>
                            <p class="fw-bold">{{ $cucian->pelanggan->nama ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><i class="material-icons md-phone"></i> No Telepon</p>
                            <p class="fw-bold">{{ $cucian->pelanggan->no_telp ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-muted mb-1"><i class="material-icons md-location_on"></i> Alamat</p>
                            <p class="fw-bold">{{ $cucian->pelanggan->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Layanan</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Jenis Layanan</p>
                            <p class="fw-bold">{{ $cucian->layanan->nama_layanan ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Durasi</p>
                            <p class="fw-bold">{{ $cucian->layanan->durasi_hari ?? 0 }} Hari</p>
                        </div>
                    </div>

                    @if($cucian->catatan)
                    <hr>
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="material-icons md-note"></i> Catatan</p>
                        <div class="alert alert-info">
                            {{ $cucian->catatan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Item -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Detail Item Cucian</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Tipe</th>
                                    <th>Jumlah/Berat</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cucian->detail as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->listHarga->nama_item ?? '-' }}</strong>
                                        @if($detail->deskripsi)
                                            <br><small class="text-muted">{{ $detail->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            <span class="badge bg-info">Kiloan</span>
                                        @else
                                            <span class="badge bg-success">Satuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            {{ number_format($detail->berat_kg, 1) }} Kg
                                        @else
                                            {{ $detail->jumlah }} pcs
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->berat_kg)
                                            Rp {{ number_format($detail->harga_kiloan, 0, ',', '.') }}/Kg
                                        @else
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}/pcs
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            @if($detail->berat_kg)
                                                Rp {{ number_format($detail->berat_kg * $detail->harga_kiloan, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada detail item</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th>
                                        <h5 class="text-primary mb-0">{{ $cucian->getFormattedTotalHarga() }}</h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Item:</strong> {{ $cucian->total_item }} item</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Berat:</strong> {{ $cucian->total_berat ? number_format($cucian->total_berat, 1) . ' Kg' : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Pembayaran -->
            @if($cucian->pembayaran)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Metode Pembayaran</p>
                            <p class="fw-bold">{{ ucfirst($cucian->pembayaran->metode_bayar ?? '-') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Status Pembayaran</p>
                            <p>
                                @if($cucian->pembayaran->status_bayar == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum Lunas</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Jumlah Dibayar</p>
                            <p class="fw-bold">Rp {{ number_format($cucian->pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

<!-- CONTINUED FROM PART 1 -->

            <!-- Info Penjemputan (Jika Online) -->
            @if($cucian->jenis_order == 'online')
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="text-white mb-0">Informasi Penjemputan</h5>
                </div>
                <div class="card-body">
                    @if($cucian->penjemputan)
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Kurir</p>
                                <p class="fw-bold">{{ $cucian->penjemputan->staff->nama ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Status</p>
                                <p>
                                    @if($cucian->penjemputan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($cucian->penjemputan->status == 'diproses')
                                        <span class="badge bg-info">Diproses</span>
                                    @else
                                        <span class="badge bg-warning">Menunggu</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Alamat Jemput</p>
                                <p class="fw-bold">{{ Str::limit($cucian->penjemputan->alamat_jemput, 30) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('staff.penjemputan.show', $cucian->penjemputan->penjemputan_id) }}" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="material-icons md-visibility"></i> Lihat Detail Penjemputan
                        </a>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="material-icons md-warning"></i>
                            Belum ada kurir yang ditugaskan untuk penjemputan.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Info Pengantaran (Jika Diantar) -->
            @if($cucian->jenis_ambil == 'diantar')
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="text-white mb-0">Informasi Pengantaran</h5>
                </div>
                <div class="card-body">
                    @if($cucian->pengantaran)
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Kurir</p>
                                <p class="fw-bold">{{ $cucian->pengantaran->kurir->nama ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Status</p>
                                <p>
                                    @if($cucian->pengantaran->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($cucian->pengantaran->status == 'diproses')
                                        <span class="badge bg-info">Diproses</span>
                                    @else
                                        <span class="badge bg-warning">Menunggu</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Alamat Antar</p>
                                <p class="fw-bold">{{ Str::limit($cucian->pengantaran->alamat_antar, 30) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('staff.pengantaran.show', $cucian->pengantaran->pengantaran_id) }}" 
                           class="btn btn-sm btn-success mt-2">
                            <i class="material-icons md-visibility"></i> Lihat Detail Pengantaran
                        </a>
                    @else
                        @if($cucian->status_cucian == 'selesai')
                        <div class="alert alert-info mb-0">
                            <i class="material-icons md-info"></i>
                            Cucian sudah selesai dan siap untuk diantar.
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="material-icons md-info"></i>
                            Pengantaran akan tersedia setelah cucian selesai.
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="text-white mb-0">Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diterima</h6>
                                <p class="text-muted small mb-0">
                                    {{ $cucian->tgl_order->format('d M Y H:i') }}
                                </p>
                                <span class="badge bg-primary mt-1">Order Masuk</span>
                            </div>
                        </div>

                        @if($cucian->status_cucian == 'diproses' || $cucian->status_cucian == 'selesai' || $cucian->status_cucian == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Sedang Diproses</h6>
                                <span class="badge bg-info mt-1">Proses</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status_cucian == 'selesai' || $cucian->status_cucian == 'diambil')
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Selesai</h6>
                                @if($cucian->tgl_selesai)
                                <p class="text-muted small mb-0">
                                    {{ $cucian->tgl_selesai->format('d M Y H:i') }}
                                </p>
                                @endif
                                <span class="badge bg-success mt-1">Selesai</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status_cucian == 'diambil')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Cucian Diambil</h6>
                                @if($cucian->tgl_diambil)
                                <p class="text-muted small mb-0">
                                    {{ $cucian->tgl_diambil->format('d M Y H:i') }}
                                </p>
                                @endif
                                <span class="badge bg-secondary mt-1">Diambil</span>
                            </div>
                        </div>
                        @endif

                        @if($cucian->status_cucian == 'menunggu')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Konfirmasi</h6>
                                <span class="badge bg-warning mt-1">Menunggu</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Aksi Selanjutnya</h4>
                </div>
                <div class="card-body">
                    @php
                        $isOnline = $cucian->jenis_order === 'online';
                        $isKiloan = $cucian->layanan && $cucian->layanan->jenis_cucian === 'kiloan';
                        $hasPenjemputan = $cucian->penjemputan && $cucian->penjemputan->staff_id;
                        $penjemputanSelesai = $cucian->penjemputan && $cucian->penjemputan->status === 'selesai';
                        $hasBerat = $cucian->total_berat && $cucian->total_berat > 0;
                        $isPaid = $cucian->isPaid();
                        $needsDelivery = $cucian->jenis_ambil === 'diantar';
                        $hasDelivery = $cucian->pengantaran && $cucian->pengantaran->kurir_id;
                        $deliveryComplete = $cucian->pengantaran && $cucian->pengantaran->status === 'selesai';
                    @endphp

                    <div class="d-grid gap-2">
                        {{-- FLOW UNTUK OFFLINE --}}
                        @if(!$isOnline)
                            @if(!$isPaid)
                                <div class="alert alert-warning mb-3">
                                    <i class="material-icons md-payment"></i>
                                    <strong>Langkah 1:</strong> Proses pembayaran terlebih dahulu
                                </div>
                                <div class="btn-group w-100 mb-2" role="group">
                                    <a href="{{ route('staff.pembayaran.form', $cucian->cucian_id) }}" 
                                    class="btn btn-warning btn-lg">
                                        <i class="material-icons md-payments"></i> Cash/Transfer
                                    </a>
                                    <a href="{{ route('staff.pembayaran.midtrans', $cucian->pembayaran->pembayaran_id ?? $cucian->cucian_id) }}" 
                                    class="btn btn-success btn-lg">
                                        <i class="material-icons md-credit_card"></i> Midtrans
                                    </a>
                                </div>
                            
                            @elseif($cucian->status_cucian === 'menunggu')
                                <div class="alert alert-success mb-3">
                                    <i class="material-icons md-check_circle"></i>
                                    Pembayaran sudah lunas. Silakan proses cucian.
                                </div>
                                <button type="button" class="btn btn-info btn-lg" onclick="updateStatus('diproses')">
                                    <i class="material-icons md-play_arrow"></i> Mulai Proses Cucian
                                </button>
                            
                            @elseif($cucian->status_cucian === 'diproses')
                                <div class="alert alert-info mb-3">
                                    <i class="material-icons md-hourglass_empty"></i>
                                    Cucian sedang diproses...
                                </div>
                                <button type="button" class="btn btn-success btn-lg" onclick="updateStatus('selesai')">
                                    <i class="material-icons md-check_circle"></i> Tandai Selesai
                                </button>
                            
                            @elseif($cucian->status_cucian === 'selesai')
                                @if($needsDelivery && !$hasDelivery)
                                    <div class="alert alert-primary mb-3">
                                        <i class="material-icons md-local_shipping"></i>
                                        <strong>Langkah Selanjutnya:</strong> Assign kurir untuk pengantaran
                                    </div>
                                    <a href="{{ route('staff.pengantaran.assign-form', $cucian->cucian_id) }}" class="btn btn-success btn-lg">
                                        <i class="material-icons md-local_shipping"></i> Assign Pengantaran
                                    </a>
                                @elseif($needsDelivery && $hasDelivery && !$deliveryComplete)
                                    <div class="alert alert-info mb-3">
                                        <i class="material-icons md-local_shipping"></i>
                                        <strong>Menunggu:</strong> Kurir sedang mengantarkan cucian
                                    </div>
                                    <a href="{{ route('staff.pengantaran.show', $cucian->pengantaran->pengantaran_id) }}" class="btn btn-outline-success">
                                        <i class="material-icons md-visibility"></i> Lihat Status Pengantaran
                                    </a>
                                @else
                                    <div class="alert alert-success mb-3">
                                        <i class="material-icons md-check"></i>
                                        Cucian selesai dan siap diambil pelanggan
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-lg" onclick="updateStatus('diambil')">
                                        <i class="material-icons md-done_all"></i> Tandai Diambil
                                    </button>
                                @endif
                            
                            @elseif($cucian->status_cucian === 'diambil')
                                <div class="alert alert-secondary">
                                    <i class="material-icons md-done_all"></i>
                                    <strong>Selesai!</strong> Cucian sudah diambil pelanggan.
                                </div>
                            @endif

                        {{-- FLOW UNTUK ONLINE --}}
                        @else
                            @if(!$hasPenjemputan)
                                <div class="alert alert-warning mb-3">
                                    <i class="material-icons md-person_add"></i>
                                    <strong>Langkah 1:</strong> Assign kurir untuk penjemputan
                                </div>
                                <a href="{{ route('staff.penjemputan.assign-form', $cucian->cucian_id) }}" class="btn btn-info btn-lg">
                                    <i class="material-icons md-person_add"></i> Assign Penjemputan
                                </a>
                            
                            @elseif(!$penjemputanSelesai)
                                <div class="alert alert-info mb-3">
                                    <i class="material-icons md-local_shipping"></i>
                                    <strong>Menunggu:</strong> Kurir sedang menjemput cucian
                                    <br><small>Kurir: {{ $cucian->penjemputan->staff->nama ?? '-' }}</small>
                                </div>
                                <a href="{{ route('staff.penjemputan.show', $cucian->penjemputan->penjemputan_id) }}" class="btn btn-outline-info">
                                    <i class="material-icons md-visibility"></i> Lihat Status Penjemputan
                                </a>
                            
                            @elseif($isKiloan && !$hasBerat)
                                <div class="alert alert-success mb-3">
                                    <i class="material-icons md-check_circle"></i>
                                    Cucian sudah dijemput!
                                    <br><strong>Langkah Selanjutnya:</strong> Timbang dan input berat cucian
                                </div>
                                <a href="{{ route('staff.cucian.input-berat', $cucian->cucian_id) }}" class="btn btn-primary btn-lg">
                                    <i class="material-icons md-scale"></i> Input Berat Cucian
                                </a>
                            
                            @elseif(!$isPaid)
                                <div class="alert alert-warning mb-3">
                                    <i class="material-icons md-payment"></i>
                                    @if($isKiloan && $hasBerat)
                                        <strong>Berat sudah diinput:</strong> {{ number_format($cucian->total_berat, 1) }} Kg
                                        <br>Total: {{ $cucian->getFormattedTotalHarga() }}
                                        <br><br>
                                    @endif
                                    <strong>Menunggu:</strong> Pelanggan melakukan pembayaran
                                </div>
                                
                                @if($cucian->pembayaran && $cucian->pembayaran->bukti_bayar)
                                    <a href="{{ route('staff.pembayaran.validate-form', $cucian->pembayaran->pembayaran_id) }}" class="btn btn-warning btn-lg">
                                        <i class="material-icons md-check"></i> Validasi Pembayaran
                                    </a>
                                @else
                                    <a href="{{ route('staff.pembayaran.show', $cucian->pembayaran->pembayaran_id) }}" class="btn btn-outline-warning">
                                        <i class="material-icons md-visibility"></i> Lihat Status Pembayaran
                                    </a>
                                @endif
                            
                            @elseif($cucian->status_cucian === 'menunggu')
                                <div class="alert alert-success mb-3">
                                    <i class="material-icons md-check_circle"></i>
                                    <strong>Pembayaran Lunas!</strong>
                                    <br>Cucian siap diproses.
                                </div>
                                <button type="button" class="btn btn-info btn-lg" onclick="updateStatus('diproses')">
                                    <i class="material-icons md-play_arrow"></i> Mulai Proses Cucian
                                </button>
                            
                            @elseif($cucian->status_cucian === 'diproses')
                                <div class="alert alert-info mb-3">
                                    <i class="material-icons md-hourglass_empty"></i>
                                    Cucian sedang diproses...
                                </div>
                                <button type="button" class="btn btn-success btn-lg" onclick="updateStatus('selesai')">
                                    <i class="material-icons md-check_circle"></i> Tandai Selesai
                                </button>
                            
                            @elseif($cucian->status_cucian === 'selesai')
                                @if($needsDelivery && !$hasDelivery)
                                    <div class="alert alert-primary mb-3">
                                        <i class="material-icons md-local_shipping"></i>
                                        <strong>Langkah Selanjutnya:</strong> Assign kurir untuk pengantaran
                                    </div>
                                    <a href="{{ route('staff.pengantaran.assign-form', $cucian->cucian_id) }}" class="btn btn-success btn-lg">
                                        <i class="material-icons md-local_shipping"></i> Assign Pengantaran
                                    </a>
                                @elseif($needsDelivery && $hasDelivery && !$deliveryComplete)
                                    <div class="alert alert-info mb-3">
                                        <i class="material-icons md-local_shipping"></i>
                                        <strong>Menunggu:</strong> Kurir sedang mengantarkan cucian
                                    </div>
                                    <a href="{{ route('staff.pengantaran.show', $cucian->pengantaran->pengantaran_id) }}" class="btn btn-outline-success">
                                        <i class="material-icons md-visibility"></i> Lihat Status Pengantaran
                                    </a>
                                @else
                                    <div class="alert alert-success mb-3">
                                        <i class="material-icons md-check"></i>
                                        Cucian selesai dan siap diambil pelanggan
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-lg" onclick="updateStatus('diambil')">
                                        <i class="material-icons md-done_all"></i> Tandai Diambil   
                                    </button>
                                @endif
                            
                            @elseif($cucian->status_cucian === 'diambil')
                                <div class="alert alert-secondary">
                                    <i class="material-icons md-done_all"></i>
                                    <strong>Selesai!</strong> Cucian sudah diambil pelanggan.
                                </div>
                            @endif
                        @endif

                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('staff.cucian.edit', $cucian->cucian_id) }}" class="btn btn-outline-primary">
                                <i class="material-icons md-edit"></i> Edit Data
                            </a>
                            
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="material-icons md-print"></i> Cetak
                            </button>
                            
                            @if($cucian->status_cucian == 'menunggu')
                            <button class="btn btn-outline-danger" onclick="confirmDelete()">
                                <i class="material-icons md-delete"></i> Hapus
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Hidden Forms --}}
                    <form id="delete-form" action="{{ route('staff.cucian.destroy', $cucian->cucian_id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <form id="status-form" action="{{ route('staff.status-cucian.update-status', $cucian->cucian_id) }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="status" id="status-value">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-marker {
        position: absolute;
        left: -26px;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px currentColor;
    }
    .timeline-content {
        padding-left: 10px;
    }

    @media print {
        .btn, .content-header, .card:has(h4:contains('Aksi')) {
            display: none !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Data Cucian?',
            text: 'Yakin ingin menghapus cucian {{ $cucian->getNoOrder() }}?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }

    function updateStatus(newStatus) {
        const statusLabels = {
            'diproses': {
                title: 'Proses Cucian?',
                text: 'Cucian akan dipindah ke status "Diproses"',
                icon: 'info',
                confirmText: 'Ya, Proses!',
                confirmColor: '#17a2b8'
            },
            'selesai': {
                title: 'Tandai Selesai?',
                text: 'Cucian akan ditandai sebagai "Selesai"',
                icon: 'success',
                confirmText: 'Ya, Selesai!',
                confirmColor: '#28a745'
            },
            'diambil': {
                title: 'Tandai Diambil?',
                text: 'Cucian akan ditandai sebagai "Diambil"',
                icon: 'question',
                confirmText: 'Ya, Sudah Diambil!',
                confirmColor: '#6c757d'
            }
        };

        const config = statusLabels[newStatus];

        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            showCancelButton: true,
            confirmButtonColor: config.confirmColor,
            cancelButtonColor: '#d33',
            confirmButtonText: config.confirmText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('status-value').value = newStatus;
                document.getElementById('status-form').submit();
            }
        });
    }
</script>
@endsection