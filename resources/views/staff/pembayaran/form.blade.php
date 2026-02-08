@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Proses Pembayaran</h2>
            <p>Order: <strong>{{ $cucian->getNoOrder() }}</strong></p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- FORM PEMBAYARAN -->
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Form Pembayaran</h5>
                    
                    <form action="{{ route('staff.pembayaran.process', $cucian->cucian_id) }}" 
                          method="POST" id="payment-form">
                        @csrf
                        
                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_bayar') is-invalid @enderror" 
                                    name="metode_bayar" 
                                    id="metode_bayar" 
                                    required>
                                <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('metode_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ TOTAL YANG HARUS DIBAYAR (READ-ONLY) -->
                        <div class="mb-4">
                            <label class="form-label">Total yang Harus Dibayar</label>
                            <input type="text" 
                                   class="form-control form-control-lg fw-bold bg-light" 
                                   value="{{ $cucian->getFormattedTotalHarga() }}" 
                                   readonly>
                            <input type="hidden" name="total_harga" id="total_harga" value="{{ $cucian->total_harga }}">
                        </div>

                        <!-- ✅ JUMLAH BAYAR (INPUT - KHUSUS CASH) -->
                        <div class="mb-4" id="jumlah-bayar-container">
                            <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                       name="jumlah_bayar" 
                                       id="jumlah_bayar" 
                                       placeholder="0" 
                                       min="0"
                                       step="1000"
                                       value="{{ old('jumlah_bayar', $cucian->total_harga) }}"
                                       required>
                                @error('jumlah_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Masukkan jumlah uang yang dibayarkan pelanggan</small>
                        </div>

                        <!-- ✅ KEMBALIAN (AUTO-CALCULATE) -->
                        <div class="mb-4" id="kembalian-container" style="display:none;">
                            <label class="form-label">Kembalian</label>
                            <div class="alert alert-success d-flex align-items-center" id="kembalian-box">
                                <i class="material-icons md-check_circle me-2" style="font-size: 32px;"></i>
                                <div>
                                    <h4 class="mb-0" id="kembalian-text">Rp 0</h4>
                                    <small id="kembalian-status">Tidak ada kembalian</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="material-icons md-payment"></i> Proses Pembayaran
                            </button>
                            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DETAIL ORDER -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Detail Order</h5>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">No. Order</td>
                            <td class="text-end"><strong>{{ $cucian->getNoOrder() }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pelanggan</td>
                            <td class="text-end">{{ $cucian->pelanggan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Layanan</td>
                            <td class="text-end">{{ $cucian->layanan->nama_layanan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Order</td>
                            <td class="text-end">{{ ucfirst($cucian->jenis_order) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="text-end">{!! $cucian->getStatusLabel() !!}</td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mb-3">Detail Item:</h6>
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
                            <tfoot class="table-light">
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

{{-- ✅ JAVASCRIPT UNTUK AUTO-CALCULATE KEMBALIAN --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const metodeBayarSelect = document.getElementById('metode_bayar');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const totalHargaInput = document.getElementById('total_harga');
    const kembalianContainer = document.getElementById('kembalian-container');
    const kembalianBox = document.getElementById('kembalian-box');
    const kembalianText = document.getElementById('kembalian-text');
    const kembalianStatus = document.getElementById('kembalian-status');
    const jumlahBayarContainer = document.getElementById('jumlah-bayar-container');
    const submitBtn = document.getElementById('submit-btn');

    // Handle metode bayar change
    metodeBayarSelect.addEventListener('change', function() {
        if (this.value === 'cash') {
            jumlahBayarInput.removeAttribute('readonly');
            jumlahBayarInput.value = totalHargaInput.value;
            calculateKembalian();
        } else if (this.value === 'transfer') {
            jumlahBayarInput.value = totalHargaInput.value;
            jumlahBayarInput.setAttribute('readonly', true);
            kembalianContainer.style.display = 'none';
        }
    });

    // Handle jumlah bayar input
    jumlahBayarInput.addEventListener('input', function() {
        if (metodeBayarSelect.value === 'cash') {
            calculateKembalian();
        }
    });

    // Function to calculate kembalian
    function calculateKembalian() {
        const totalHarga = parseInt(totalHargaInput.value) || 0;
        const jumlahBayar = parseInt(jumlahBayarInput.value) || 0;
        const kembalian = jumlahBayar - totalHarga;

        if (jumlahBayar === 0) {
            kembalianContainer.style.display = 'none';
            submitBtn.disabled = true;
            return;
        }

        kembalianContainer.style.display = 'block';

        if (kembalian < 0) {
            // Kurang bayar
            kembalianBox.className = 'alert alert-danger d-flex align-items-center';
            kembalianText.textContent = 'Rp ' + formatNumber(Math.abs(kembalian));
            kembalianStatus.textContent = 'Uang kurang!';
            submitBtn.disabled = true;
        } else if (kembalian === 0) {
            // Pas
            kembalianBox.className = 'alert alert-success d-flex align-items-center';
            kembalianText.textContent = 'Rp 0';
            kembalianStatus.textContent = 'Uang pas';
            submitBtn.disabled = false;
        } else {
            // Ada kembalian
            kembalianBox.className = 'alert alert-success d-flex align-items-center';
            kembalianText.textContent = 'Rp ' + formatNumber(kembalian);
            kembalianStatus.textContent = 'Kembalikan ke pelanggan';
            submitBtn.disabled = false;
        }
    }

    // Format number with thousand separator
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Initial check
    if (metodeBayarSelect.value === 'cash') {
        calculateKembalian();
    }
});
</script>
@endsection
