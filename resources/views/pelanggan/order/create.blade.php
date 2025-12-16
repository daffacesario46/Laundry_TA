@extends('pelanggan.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Buat Order Baru</h2>
            <p>Isi form di bawah untuk membuat order laundry</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pelanggan.order.store') }}" method="POST" id="orderForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Pilih Layanan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_layanan') is-invalid @enderror" 
                                    name="jenis_layanan" 
                                    id="jenis_layanan"
                                    required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanan as $item)
                                <option value="{{ $item->id }}" 
                                        data-harga="{{ $item->harga }}" 
                                        data-durasi="{{ $item->durasi }}"
                                        {{ old('jenis_layanan') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }} - Rp {{ number_format($item->harga, 0, ',', '.') }}/kg ({{ $item->durasi }} hari)
                                </option>
                                @endforeach
                            </select>
                            @error('jenis_layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Berat Cucian (kg) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('berat') is-invalid @enderror" 
                                   name="berat" 
                                   id="berat"
                                   placeholder="Contoh: 3.5" 
                                   value="{{ old('berat') }}"
                                   step="0.1"
                                   min="0.5"
                                   required />
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 0.5 kg</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Jenis Pengambilan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_pengambilan') is-invalid @enderror" 
                                    name="jenis_pengambilan" 
                                    id="jenis_pengambilan"
                                    required>
                                <option value="">-- Pilih Jenis Pengambilan --</option>
                                <option value="diantar" {{ old('jenis_pengambilan') == 'diantar' ? 'selected' : '' }}>Diantar (Gratis)</option>
                                <option value="ambil_sendiri" {{ old('jenis_pengambilan') == 'ambil_sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                            </select>
                            @error('jenis_pengambilan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="alamat_div" style="display: none;">
                            <label class="form-label">Alamat Pengambilan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_pengambilan') is-invalid @enderror" 
                                      name="alamat_pengambilan" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap untuk pengantaran">{{ old('alamat_pengambilan') }}</textarea>
                            @error('alamat_pengambilan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                    name="metode_pembayaran"
                                    required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" 
                                      name="catatan" 
                                      rows="3"
                                      placeholder="Contoh: Tolong hati-hati dengan baju putih">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-shopping_cart"></i> Buat Order
                            </button>
                            <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Ringkasan Order</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Layanan</p>
                        <p id="summary_layanan">-</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Berat</p>
                        <p id="summary_berat">0 kg</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Harga per kg</p>
                        <p id="summary_harga_per_kg">Rp 0</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Estimasi Selesai</p>
                        <p id="summary_estimasi">-</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Harga:</strong>
                        <strong class="text-primary" id="summary_total">Rp 0</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Informasi</h6>
                    <ul class="text-muted small">
                        <li class="mb-2">Pastikan berat cucian sudah sesuai</li>
                        <li class="mb-2">Pilih jenis pengambilan sesuai kebutuhan</li>
                        <li class="mb-2">Pembayaran dilakukan saat pengambilan untuk metode Cash</li>
                        <li>Untuk Transfer, kami akan kirimkan detail rekening</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisLayanan = document.getElementById('jenis_layanan');
    const berat = document.getElementById('berat');
    const jenisPengambilan = document.getElementById('jenis_pengambilan');
    const alamatDiv = document.getElementById('alamat_div');
    
    // Show/hide alamat based on jenis pengambilan
    jenisPengambilan.addEventListener('change', function() {
        if (this.value === 'diantar') {
            alamatDiv.style.display = 'block';
        } else {
            alamatDiv.style.display = 'none';
        }
    });
    
    // Calculate total
    function calculateTotal() {
        const selectedOption = jenisLayanan.options[jenisLayanan.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        const durasi = parseInt(selectedOption.getAttribute('data-durasi')) || 0;
        const beratValue = parseFloat(berat.value) || 0;
        const total = harga * beratValue;
        
        document.getElementById('summary_layanan').textContent = selectedOption.text.split(' - ')[0] || '-';
        document.getElementById('summary_berat').textContent = beratValue + ' kg';
        document.getElementById('summary_harga_per_kg').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('summary_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        if (durasi > 0) {
            const today = new Date();
            today.setDate(today.getDate() + durasi);
            document.getElementById('summary_estimasi').textContent = durasi + ' hari (' + today.toLocaleDateString('id-ID') + ')';
        } else {
            document.getElementById('summary_estimasi').textContent = '-';
        }
    }
    
    jenisLayanan.addEventListener('change', calculateTotal);
    berat.addEventListener('input', calculateTotal);
    
    // Trigger on page load if old values exist
    if (jenisPengambilan.value === 'diantar') {
        alamatDiv.style.display = 'block';
    }
    calculateTotal();
});
</script>

@endsection