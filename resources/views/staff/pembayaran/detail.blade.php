@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Detail Pembayaran</h2>
            <p>Pembayaran Order: <strong>{{ $pembayaran->cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('staff.pembayaran.index') }}" class="btn btn-light">
                <i class="icon material-icons md-arrow_back"></i> Kembali
            </a>
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

    <div class="row">
        <!-- Detail Pembayaran -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="icon material-icons md-receipt"></i>
                        Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">No. Order</p>
                            <h6>{{ $pembayaran->cucian->getNoOrder() }}</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Status Pembayaran</p>
                            <h6>
                                <span class="badge rounded-pill {{ $pembayaran->getStatusBadge() }}">
                                    {{ $pembayaran->getStatusLabel() }}
                                </span>
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Metode Pembayaran</p>
                            <h6>
                                <span class="badge rounded-pill {{ $pembayaran->metode_bayar == 'cash' ? 'alert-success' : 'alert-info' }}">
                                    {{ $pembayaran->getMetodeBayarLabel() }}
                                </span>
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Jumlah Bayar</p>
                            <h5 class="text-primary mb-0">{{ $pembayaran->getFormattedJumlahBayar() }}</h5>
                        </div>

                        @if($pembayaran->tgl_bayar)
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Tanggal Bayar</p>
                            <h6>{{ $pembayaran->tgl_bayar->format('d M Y, H:i') }}</h6>
                        </div>
                        @endif

                        @if($pembayaran->catatan)
                        <div class="col-12">
                            <p class="mb-1 text-muted">Catatan</p>
                            <div class="alert alert-light">
                                {{ $pembayaran->catatan }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        @if($pembayaran->metode_bayar == 'transfer' && $pembayaran->status_bayar == 'belum' && $pembayaran->bukti_bayar)
                            <a href="{{ route('staff.pembayaran.validate-form', $pembayaran->pembayaran_id) }}" 
                               class="btn btn-warning">
                                <i class="icon material-icons md-check"></i>
                                Validasi Pembayaran
                            </a>
                        @endif

                        <a href="{{ route('staff.cucian.show', $pembayaran->cucian_id) }}" 
                           class="btn btn-info">
                            <i class="icon material-icons md-local_laundry_service"></i>
                            Lihat Cucian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bukti Transfer (jika ada) -->
            @if($pembayaran->bukti_bayar)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Bukti Pembayaran</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ Storage::url($pembayaran->bukti_bayar) }}" 
                         alt="Bukti Pembayaran" 
                         class="img-fluid rounded border"
                         style="max-height: 500px; cursor: pointer;"
                         onclick="window.open('{{ Storage::url($pembayaran->bukti_bayar) }}', '_blank')">
                    <p class="mt-3 text-muted">
                        <small>Klik gambar untuk memperbesar</small>
                    </p>
                </div>
            </div>
            @endif

            <!-- Detail Item Cucian -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Item Cucian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Item</th>
                                    <th>Jumlah/Berat</th>
                                    <th>Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaran->cucian->detail as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->listHarga->nama_item }}</strong>
                                        @if($item->deskripsi)
                                            <br><small class="text-muted">{{ $item->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->berat_kg)
                                            {{ $item->berat_kg }} kg
                                        @else
                                            {{ $item->jumlah }} pcs
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->berat_kg)
                                            Rp {{ number_format($item->listHarga->harga_kiloan, 0, ',', '.') }}/kg
                                        @else
                                            Rp {{ number_format($item->listHarga->harga_satuan, 0, ',', '.') }}/pcs
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>
                                            @if($item->berat_kg)
                                                Rp {{ number_format($item->berat_kg * $item->listHarga->harga_kiloan, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($item->jumlah * $item->listHarga->harga_satuan, 0, ',', '.') }}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada detail item</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th class="text-end">
                                        <h5 class="text-primary mb-0">{{ $pembayaran->cucian->getFormattedTotalHarga() }}</h5>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Info Pelanggan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="40%">Nama</td>
                            <td><strong>{{ $pembayaran->cucian->pelanggan->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td>No. Telepon</td>
                            <td>{{ $pembayaran->cucian->pelanggan->no_telp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>No. WhatsApp</td>
                            <td>{{ $pembayaran->cucian->pelanggan->no_wa ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $pembayaran->cucian->pelanggan->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Info Order -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Order</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="40%">Layanan</td>
                            <td>{{ $pembayaran->cucian->layanan->nama_layanan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Order</td>
                            <td>
                                <span class="badge rounded-pill alert-info">
                                    {{ ucfirst($pembayaran->cucian->jenis_order) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis Ambil</td>
                            <td>{{ $pembayaran->cucian->jenis_ambil == 'diantar' ? 'Diantar' : 'Ambil Sendiri' }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="badge rounded-pill {{ $pembayaran->cucian->getStatusBadge() }}">
                                    {{ $pembayaran->cucian->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tgl Order</td>
                            <td>{{ $pembayaran->cucian->tgl_order->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($pembayaran->cucian->estimasi)
                        <tr>
                            <td>Estimasi</td>
                            <td>{{ $pembayaran->cucian->estimasi->format('d M Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Item</span>
                        <strong>{{ $pembayaran->cucian->total_item }} item</strong>
                    </div>
                    @if($pembayaran->cucian->total_berat)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Berat</span>
                        <strong>{{ $pembayaran->cucian->total_berat }} kg</strong>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <strong>Total Bayar</strong>
                        <h5 class="text-primary mb-0">{{ $pembayaran->getFormattedJumlahBayar() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Delete Pembayaran -->
<form id="delete-form" action="{{ route('staff.pembayaran.destroy', $pembayaran->pembayaran_id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deletePembayaran() {
    Swal.fire({
        title: 'Batalkan Pembayaran?',
        text: 'Pembayaran ini akan dibatalkan. Tindakan ini tidak dapat diurungkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endsection