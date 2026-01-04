@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Cucian</h2>
            <p>Edit data cucian {{ $cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('staff.cucian.update', $cucian->cucian_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Info Pelanggan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Informasi Pelanggan</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pilih Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select @error('pelanggan_id') is-invalid @enderror" 
                                    id="pelanggan_id" name="pelanggan_id" required onchange="loadPelangganInfo(this)">
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->pelanggan_id }}" 
                                        data-nama="{{ $p->nama }}"
                                        data-telp="{{ $p->no_telp }}"
                                        data-alamat="{{ $p->alamat }}"
                                        {{ $cucian->pelanggan_id == $p->pelanggan_id ? 'selected' : '' }}>
                                    {{ $p->nama }} - {{ $p->no_telp }}
                                </option>
                                @endforeach
                            </select>
                            @error('pelanggan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="display_telp" 
                                       value="{{ $cucian->pelanggan->no_telp ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="display_alamat" 
                                       value="{{ $cucian->pelanggan->alamat ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Layanan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Informasi Layanan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="layanan_id" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-select @error('layanan_id') is-invalid @enderror" 
                                        id="layanan_id" name="layanan_id" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach($layanan as $l)
                                    <option value="{{ $l->layanan_id }}" {{ $cucian->layanan_id == $l->layanan_id ? 'selected' : '' }}>
                                        {{ $l->nama_layanan }} ({{ $l->durasi_hari }} hari)
                                    </option>
                                    @endforeach
                                </select>
                                @error('layanan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jenis_ambil" class="form-label">Jenis Pengambilan <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_ambil') is-invalid @enderror" 
                                        id="jenis_ambil" name="jenis_ambil" required>
                                    <option value="diantar" {{ $cucian->jenis_ambil == 'diantar' ? 'selected' : '' }}>Diantar</option>
                                    <option value="ambil_sendiri" {{ $cucian->jenis_ambil == 'ambil_sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                                </select>
                                @error('jenis_ambil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status_cucian" class="form-label">Status Cucian <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_cucian') is-invalid @enderror" 
                                        id="status_cucian" name="status_cucian" required>
                                    <option value="menunggu" {{ $cucian->status_cucian == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ $cucian->status_cucian == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $cucian->status_cucian == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="diambil" {{ $cucian->status_cucian == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                </select>
                                @error('status_cucian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Order</label>
                                <input type="text" class="form-control" value="{{ ucfirst($cucian->jenis_order) }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                      placeholder="Catatan tambahan (opsional)">{{ $cucian->catatan }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Detail Item (Read-only) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Detail Item Cucian</h4>
                        <small class="text-muted">Detail item tidak dapat diubah. Untuk mengubah item, silakan buat cucian baru.</small>
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
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cucian->detail as $index => $detail)
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
                                            @if($detail->berat_kg)
                                                Rp {{ number_format($detail->berat_kg * $detail->harga_kiloan, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>{{ $cucian->getFormattedTotalHarga() }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Ringkasan -->
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-white mb-0">Ringkasan</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">No Order</small>
                            <h5>{{ $cucian->getNoOrder() }}</h5>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Status Saat Ini</small>
                            <p>
                                <span class="badge rounded-pill {{ $cucian->getStatusBadge() }}">
                                    {{ $cucian->getStatusLabel() }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Total Item</small>
                            <p class="mb-0"><strong>{{ $cucian->total_item }} item</strong></p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Total Berat</small>
                            <p class="mb-0"><strong>{{ $cucian->total_berat ? number_format($cucian->total_berat, 1) . ' Kg' : '-' }}</strong></p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <small class="text-muted">Total Harga</small>
                            <h4 class="text-primary">{{ $cucian->getFormattedTotalHarga() }}</h4>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="material-icons md-save"></i> Update Data
                        </button>
                        <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light w-100">
                            <i class="material-icons md-close"></i> Batal
                        </a>
                    </div>
                </div>

                <!-- Info Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h4>Riwayat</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i class="material-icons md-access_time small"></i> 
                            <strong>Tanggal Order:</strong><br>
                            {{ $cucian->tgl_order->format('d M Y H:i') }}
                        </p>
                        
                        @if($cucian->estimasi)
                        <p class="text-muted small mb-2">
                            <i class="material-icons md-event small"></i> 
                            <strong>Estimasi Selesai:</strong><br>
                            {{ $cucian->estimasi->format('d M Y') }}
                        </p>
                        @endif

                        @if($cucian->tgl_selesai)
                        <p class="text-muted small mb-2">
                            <i class="material-icons md-check_circle small"></i> 
                            <strong>Tanggal Selesai:</strong><br>
                            {{ $cucian->tgl_selesai->format('d M Y H:i') }}
                        </p>
                        @endif

                        @if($cucian->tgl_diambil)
                        <p class="text-muted small mb-0">
                            <i class="material-icons md-done_all small"></i> 
                            <strong>Tanggal Diambil:</strong><br>
                            {{ $cucian->tgl_diambil->format('d M Y H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
function loadPelangganInfo(select) {
    const option = select.options[select.selectedIndex];
    document.getElementById('display_telp').value = option.dataset.telp || '-';
    document.getElementById('display_alamat').value = option.dataset.alamat || '-';
}
</script>
@endsection