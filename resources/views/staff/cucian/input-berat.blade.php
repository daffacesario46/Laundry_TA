@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Input Berat Cucian</h2>
            <p>Update berat cucian kiloan {{ $cucian->getNoOrder() }}</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('staff.cucian.update-berat', $cucian->cucian_id) }}" method="POST" id="formUpdateBerat">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Info Cucian -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="text-white mb-0">Informasi Cucian</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">No Order</label>
                                <p class="fw-bold">{{ $cucian->getNoOrder() }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Pelanggan</label>
                                <p class="fw-bold">{{ $cucian->pelanggan->nama }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Layanan</label>
                                <p class="fw-bold">{{ $cucian->layanan->nama_layanan }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Status</label>
                                <p>
                                    <span class="badge {{ $cucian->getStatusBadge() }}">
                                        {{ $cucian->getStatusLabel() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <i class="material-icons md-info"></i>
                            <strong>Petunjuk:</strong> Masukkan berat actual setelah cucian dijemput. Total harga akan otomatis dihitung berdasarkan berat yang diinput.
                        </div>
                    </div>
                </div>

                <!-- Form Input Berat -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Input Berat Per Item</h4>
                    </div>
                    <div class="card-body">
                        @if($cucian->detail->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Item</th>
                                        <th>Harga per Kg</th>
                                        <th>Berat (Kg) <span class="text-danger">*</span></th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cucian->detail as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $detail->listHarga->nama_item }}</strong>
                                            @if($detail->deskripsi)
                                                <br><small class="text-muted">{{ $detail->deskripsi }}</small>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($detail->harga_kiloan ?? $detail->listHarga->harga_kiloan, 0, ',', '.') }}/Kg</td>
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][cucian_detail_id]" value="{{ $detail->cucian_detail_id }}">
                                            <div class="input-group" style="max-width: 200px;">
                                                <input type="number" 
                                                       step="0.1" 
                                                       min="0.1"
                                                       class="form-control berat-input" 
                                                       name="items[{{ $index }}][berat_kg]" 
                                                       value="{{ old('items.'.$index.'.berat_kg', $detail->berat_kg ?? '') }}"
                                                       data-harga="{{ $detail->harga_kiloan ?? $detail->listHarga->harga_kiloan }}"
                                                       data-index="{{ $index }}"
                                                       required
                                                       onchange="hitungSubtotal({{ $index }})">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                            @error('items.'.$index.'.berat_kg')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <strong class="subtotal-{{ $index }}">Rp 0</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Berat:</th>
                                        <th>
                                            <span id="total-berat" class="text-primary">0</span> Kg
                                        </th>
                                        <th>
                                            <h5 class="text-primary mb-0" id="total-harga">Rp 0</h5>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <!-- Fallback jika tidak ada detail -->
                        <div class="alert alert-warning">
                            <i class="material-icons md-warning"></i>
                            <strong>Perhatian:</strong> Tidak ada detail cucian yang ditemukan. 
                            <br>Silakan hubungi admin untuk menambahkan item cucian terlebih dahulu.
                        </div>
                        @endif
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
                            <small class="text-muted">Total Item</small>
                            <p class="mb-0"><strong>{{ $cucian->detail->count() }} item</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Berat</small>
                            <h4 class="text-primary mb-0"><span id="summary-berat">0</span> Kg</h4>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted">Total Harga</small>
                            <h3 class="text-success mb-0" id="summary-harga">Rp 0</h3>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <i class="material-icons md-warning small"></i>
                                Pastikan berat sudah sesuai sebelum menyimpan!
                            </small>
                        </div>

                        @if($cucian->detail->count() > 0)
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="material-icons md-save"></i> Simpan & Update Harga
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary w-100 mb-2" disabled>
                            <i class="material-icons md-save"></i> Tidak Ada Item
                        </button>
                        @endif
                        
                        <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light w-100">
                            <i class="material-icons md-close"></i> Batal
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi</h4>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">
                            <i class="material-icons md-info small"></i>
                            Setelah berat diinput, sistem akan:
                        </p>
                        <ol class="small text-muted mb-0">
                            <li>Menghitung ulang total harga</li>
                            <li>Update status cucian ke "Diproses"</li>
                            <li>Update data pembayaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
function hitungSubtotal(index) {
    const input = document.querySelector(`input[data-index="${index}"]`);
    const berat = parseFloat(input.value) || 0;
    const harga = parseFloat(input.dataset.harga) || 0;
    const subtotal = berat * harga;
    
    // Update subtotal di tabel
    const subtotalEl = document.querySelector(`.subtotal-${index}`);
    if (subtotalEl) {
        subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }
    
    // Hitung total
    hitungTotal();
}

function hitungTotal() {
    let totalBerat = 0;
    let totalHarga = 0;
    
    document.querySelectorAll('.berat-input').forEach(input => {
        const berat = parseFloat(input.value) || 0;
        const harga = parseFloat(input.dataset.harga) || 0;
        
        totalBerat += berat;
        totalHarga += (berat * harga);
    });
    
    // Update UI
    const totalBeratEl = document.getElementById('total-berat');
    const totalHargaEl = document.getElementById('total-harga');
    const summaryBeratEl = document.getElementById('summary-berat');
    const summaryHargaEl = document.getElementById('summary-harga');
    
    if (totalBeratEl) totalBeratEl.textContent = totalBerat.toFixed(1);
    if (totalHargaEl) totalHargaEl.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
    if (summaryBeratEl) summaryBeratEl.textContent = totalBerat.toFixed(1);
    if (summaryHargaEl) summaryHargaEl.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Hitung semua subtotal jika ada
    document.querySelectorAll('.berat-input').forEach((input, index) => {
        if (input.value && parseFloat(input.value) > 0) {
            hitungSubtotal(parseInt(input.dataset.index));
        }
    });
});

// Validate form
const form = document.getElementById('formUpdateBerat');
if (form) {
    form.addEventListener('submit', function(e) {
        const inputs = document.querySelectorAll('.berat-input');
        let hasError = false;
        
        inputs.forEach(input => {
            if (!input.value || parseFloat(input.value) < 0.1) {
                hasError = true;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('Semua berat harus diisi minimal 0.1 Kg!');
        }
    });
}
</script>
@endsection