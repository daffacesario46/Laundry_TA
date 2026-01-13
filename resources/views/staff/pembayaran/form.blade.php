@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Proses Pembayaran</h2>
            <p>Order: <strong>{{ $cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="icon material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Form Pembayaran --}}
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Form Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.pembayaran.process', $cucian->cucian_id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_bayar') is-invalid @enderror" name="metode_bayar" required>
                                <option value="">Pilih Metode</option>
                                <option value="cash">Cash</option>
                            </select>
                            @error('metode_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total yang Harus Dibayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" value="{{ number_format($cucian->total_harga, 0, ',', '.') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                       name="jumlah_bayar" 
                                       id="jumlah_bayar"
                                       value="{{ old('jumlah_bayar', $cucian->total_harga) }}" 
                                       step="1" 
                                       min="0"
                                       required>
                                @error('jumlah_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Masukkan jumlah yang dibayar pelanggan</small>
                        </div>

                        <div class="mb-3" id="kembalian-section" style="display: none;">
                            <label class="form-label">Kembalian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" id="kembalian" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan pembayaran (opsional)">{{ old('catatan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="icon material-icons md-check"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Detail Cucian --}}
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Order</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="40%">No. Order</td>
                            <td><strong>{{ $cucian->getNoOrder() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td><strong>{{ $cucian->pelanggan->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                            <td>{{ $cucian->layanan->nama_layanan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Order</td>
                            <td>
                                <span class="badge rounded-pill alert-info">
                                    {{ ucfirst($cucian->jenis_order) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="badge rounded-pill {{ $cucian->getStatusBadge() }}">
                                    {{ $cucian->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6>Detail Item:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cucian->detail as $item)
                                <tr>
                                    <td>{{ $item->listHarga->nama_item }}</td>
                                    <td class="text-center">
                                        @if($item->berat_kg)
                                            {{ $item->berat_kg }} kg
                                        @else
                                            {{ $item->jumlah }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($item->berat_kg)
                                            Rp {{ number_format($item->listHarga->harga_kiloan * $item->berat_kg, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($item->listHarga->harga_satuan * $item->jumlah, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">TOTAL</th>
                                    <th class="text-end">{{ $cucian->getFormattedTotalHarga() }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('jumlah_bayar').addEventListener('input', function() {
    const totalHarga = {{ $cucian->total_harga }};
    const jumlahBayar = parseFloat(this.value) || 0;
    const kembalian = jumlahBayar - totalHarga;
    
    const kembalianSection = document.getElementById('kembalian-section');
    const kembalianInput = document.getElementById('kembalian');
    
    if (kembalian > 0) {
        kembalianSection.style.display = 'block';
        kembalianInput.value = new Intl.NumberFormat('id-ID').format(kembalian);
    } else {
        kembalianSection.style.display = 'none';
    }
});
</script>
@endsection