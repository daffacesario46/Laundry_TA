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

    <form action="{{ route('pelanggan.order.store') }}" method="POST" id="orderForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Info Layanan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informasi Layanan</h5>
                    </div>
                    <div class="card-body">
                        {{-- 1. JENIS CUCIAN --}}
                        <div class="mb-3">
                            <label for="jenis_cucian" class="form-label">Jenis Cucian <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_cucian') is-invalid @enderror" 
                                    id="jenis_cucian" name="jenis_cucian" required>
                                <option value="">-- Pilih Jenis Cucian --</option>
                                <option value="kiloan" {{ old('jenis_cucian') == 'kiloan' ? 'selected' : '' }}>
                                    Kiloan (Per Kilogram)
                                </option>
                                <option value="satuan" {{ old('jenis_cucian') == 'satuan' ? 'selected' : '' }}>
                                    Satuan (Per Item)
                                </option>
                            </select>
                            @error('jenis_cucian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="material-icons md-info" style="font-size: 14px;"></i>
                                Pilih jenis cucian dulu, lalu layanan akan muncul sesuai jenis
                            </small>
                        </div>

                        {{-- 2. JENIS LAYANAN --}}
                        <div class="mb-3">
                            <label for="layanan_id" class="form-label">Pilih Layanan <span class="text-danger">*</span></label>
                            <select class="form-select @error('layanan_id') is-invalid @enderror" 
                                    name="layanan_id" 
                                    id="layanan_id"
                                    required disabled>
                                <option value="">-- Pilih Jenis Cucian Dulu --</option>
                                @foreach($layanan as $item)
                                <option value="{{ $item->layanan_id }}" 
                                        data-jenis="{{ $item->jenis_cucian }}"
                                        data-durasi="{{ $item->durasi_hari }}"
                                        {{ old('layanan_id') == $item->layanan_id ? 'selected' : '' }}>
                                    {{ $item->nama_layanan }} ({{ $item->durasi_hari }} hari)
                                </option>
                                @endforeach
                            </select>
                            @error('layanan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pengambilan <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_ambil') is-invalid @enderror" 
                                        name="jenis_ambil" 
                                        id="jenis_ambil"
                                        required>
                                    <option value="">-- Pilih --</option>
                                    <option value="diantar" {{ old('jenis_ambil') == 'diantar' ? 'selected' : '' }}>Diantar (Gratis)</option>
                                    <option value="ambil_sendiri" {{ old('jenis_ambil') == 'ambil_sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                                </select>
                                @error('jenis_ambil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('metode_bayar') is-invalid @enderror" 
                                        name="metode_bayar"
                                        required>
                                    <option value="">-- Pilih --</option>
                                    <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                @error('metode_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" 
                                      name="catatan" 
                                      rows="2"
                                      placeholder="Contoh: Tolong hati-hati dengan baju putih">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Item Cucian -->
                <div class="card mb-4" id="items-section" style="display:none;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Item Cucian (Satuan)</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()" id="btn-add-item">
                            <i class="material-icons md-add"></i> Tambah Item
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- Container items --}}
                        <div id="items-container"></div>
                    </div>
                </div>

                {{-- Alert untuk kiloan --}}
                <div class="alert alert-info" id="alert-kiloan" style="display:none;">
                    <i class="material-icons md-info"></i>
                    <strong>Cucian Kiloan:</strong> Anda hanya perlu memilih layanan. 
                    Berat cucian akan diinput oleh staff setelah barang dijemput/diterima.
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="text-white mb-0">Ringkasan Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Jenis Cucian</small>
                            <p id="summary_jenis" class="mb-0"><strong>-</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Layanan</small>
                            <p id="summary_layanan" class="mb-0"><strong>-</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Item</small>
                            <p id="summary_items" class="mb-0"><strong>0</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Berat</small>
                            <p id="summary_weight" class="mb-0"><strong>-</strong></p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Estimasi Selesai</small>
                            <p id="summary_estimasi" class="mb-0"><strong>-</strong></p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total Harga:</h5>
                            <h5 class="text-primary" id="summary_total">Rp 0</h5>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="material-icons md-shopping_cart"></i> Buat Order
                        </button>
                        <a href="{{ route('pelanggan.order.index') }}" class="btn btn-light w-100">
                            Batal
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Informasi</h6>
                        <ul class="text-muted small">
                            <li class="mb-2">Pilih <strong>jenis cucian</strong> terlebih dahulu</li>
                            <li class="mb-2">Kemudian pilih <strong>layanan</strong> sesuai kebutuhan</li>
                            <li class="mb-2"><strong>Kiloan:</strong> Berat diinput staff saat jemput</li>
                            <li><strong>Satuan:</strong> Per piece (kaos, celana, dll)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
let itemIndex = 0;
let currentJenisCucian = '';

// Simpan semua opsi layanan
const layananSelect = document.getElementById('layanan_id');
const allLayananOptions = Array.from(layananSelect.querySelectorAll('option'));

// Filter layanan berdasarkan jenis cucian
document.getElementById('jenis_cucian')?.addEventListener('change', function() {
    const selectedJenis = this.value;
    
    layananSelect.innerHTML = '';
    layananSelect.disabled = !selectedJenis;
    
    if (!selectedJenis) {
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Pilih Jenis Cucian Dulu --';
        layananSelect.appendChild(defaultOption);
        handleJenisCucianChange();
        return;
    }
    
    const placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.textContent = '-- Pilih Layanan --';
    layananSelect.appendChild(placeholderOption);
    
    let hasMatchingLayanan = false;
    allLayananOptions.forEach(option => {
        if (option.value === '') return;
        
        const optionJenis = option.dataset.jenis;
        
        if (optionJenis === selectedJenis) {
            layananSelect.appendChild(option.cloneNode(true));
            hasMatchingLayanan = true;
        }
    });
    
    if (!hasMatchingLayanan) {
        const noDataOption = document.createElement('option');
        noDataOption.value = '';
        noDataOption.textContent = `-- Tidak ada layanan ${selectedJenis} --`;
        layananSelect.appendChild(noDataOption);
    }
    
    handleJenisCucianChange();
});

function handleJenisCucianChange() {
    const select = document.getElementById('jenis_cucian');
    currentJenisCucian = select.value;
    
    const itemsSection = document.getElementById('items-section');
    const itemsContainer = document.getElementById('items-container');
    const alertKiloan = document.getElementById('alert-kiloan');
    
    if (currentJenisCucian === 'kiloan') {
        // Kiloan: hide items, show alert
        itemsSection.style.display = 'none';
        itemsContainer.innerHTML = '';
        alertKiloan.style.display = 'block';
        
        document.getElementById('summary_jenis').innerHTML = '<strong>Kiloan</strong>';
    } else if (currentJenisCucian === 'satuan') {
        // Satuan: show items, hide alert
        itemsSection.style.display = 'block';
        alertKiloan.style.display = 'none';
        
        // Add first item
        if (itemsContainer.children.length === 0) {
            addItem();
        }
        
        document.getElementById('summary_jenis').innerHTML = '<strong>Satuan</strong>';
    } else {
        itemsSection.style.display = 'none';
        alertKiloan.style.display = 'none';
        document.getElementById('summary_jenis').innerHTML = '<strong>-</strong>';
    }
    
    updateSummary();
}

function addItem() {
    if (currentJenisCucian !== 'satuan') {
        alert('Item hanya bisa ditambahkan untuk cucian satuan');
        return;
    }
    
    itemIndex++;
    const container = document.getElementById('items-container');
    const newItem = `
        <div class="item-row border rounded p-3 mb-3" data-index="${itemIndex}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Item <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[${itemIndex}][list_harga_id]" required onchange="updateSummary()">
                        <option value="">-- Pilih Item --</option>
                        @foreach($listHarga->where('harga_satuan', '>', 0) as $lh)
                        <option value="{{ $lh->list_harga_id }}" 
                                data-satuan="{{ $lh->harga_satuan }}">
                            {{ $lh->nama_item }} - Rp {{ number_format($lh->harga_satuan, 0, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" 
                           name="items[${itemIndex}][jumlah]" 
                           min="1" value="1" required
                           onchange="updateSummary()">
                </div>

                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="removeItem(${itemIndex})" ${itemIndex === 1 ? 'disabled' : ''}>
                        <i class="material-icons md-delete"></i>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <input type="text" class="form-control" 
                           name="items[${itemIndex}][deskripsi]" 
                           placeholder="Contoh: Warna biru, ada noda">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    updateSummary();
}

function removeItem(index) {
    const item = document.querySelector(`.item-row[data-index="${index}"]`);
    if (item) {
        item.remove();
        updateSummary();
    }
}

function updateSummary() {
    let totalItems = 0;
    let totalPrice = 0;

    if (currentJenisCucian === 'kiloan') {
        // Untuk kiloan, tidak ada items
        document.getElementById('summary_items').innerHTML = '<strong>-</strong>';
        document.getElementById('summary_weight').innerHTML = '<strong>Akan diinput staff</strong>';
        document.getElementById('summary_total').innerHTML = '<small class="text-muted">Akan dihitung setelah berat diinput</small>';
    } else if (currentJenisCucian === 'satuan') {
        // Untuk satuan, hitung seperti biasa
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('select[name$="[list_harga_id]"]');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                const hargaSatuan = parseFloat(option.dataset.satuan) || 0;
                const jumlah = parseInt(row.querySelector('input[name$="[jumlah]"]').value) || 0;
                
                totalItems += jumlah;
                totalPrice += jumlah * hargaSatuan;
            }
        });
        
        document.getElementById('summary_items').innerHTML = '<strong>' + totalItems + ' pcs</strong>';
        document.getElementById('summary_weight').innerHTML = '<strong>-</strong>';
        document.getElementById('summary_total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    } else {
        document.getElementById('summary_items').innerHTML = '<strong>0</strong>';
        document.getElementById('summary_weight').innerHTML = '<strong>-</strong>';
        document.getElementById('summary_total').textContent = 'Rp 0';
    }
    
    // Update layanan & estimasi
    const layananOption = layananSelect.options[layananSelect.selectedIndex];
    const durasi = parseInt(layananOption?.dataset.durasi) || 0;
    
    document.getElementById('summary_layanan').innerHTML = '<strong>' + (layananOption?.text || '-') + '</strong>';
    
    if (durasi > 0) {
        const today = new Date();
        today.setDate(today.getDate() + durasi);
        document.getElementById('summary_estimasi').innerHTML = '<strong>' + durasi + ' hari (' + today.toLocaleDateString('id-ID') + ')</strong>';
    } else {
        document.getElementById('summary_estimasi').innerHTML = '<strong>-</strong>';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners
    layananSelect.addEventListener('change', updateSummary);
    
    // Trigger jika ada old value
    const jenisCucian = document.getElementById('jenis_cucian');
    if (jenisCucian.value) {
        jenisCucian.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection