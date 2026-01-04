@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Cucian Baru (Offline)</h2>
            <p>Tambahkan data cucian untuk pelanggan walk-in</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ✅ INFO ALERT --}}
    <div class="alert alert-info mb-4">
        <i class="material-icons md-info"></i>
        <strong>Informasi:</strong> 
        Form ini untuk membuat order <strong>Offline</strong> (pelanggan datang langsung ke toko). 
        Order <strong>Online</strong> dibuat oleh pelanggan melalui website/aplikasi.
    </div>

    <form action="{{ route('staff.cucian.store') }}" method="POST" id="formCucian">
        @csrf
        
        {{-- ✅ HIDDEN FIELD: Jenis Order SELALU OFFLINE --}}
        <input type="hidden" name="jenis_order" value="offline">
        
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
                                    id="pelanggan_id" name="pelanggan_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->pelanggan_id }}" 
                                        data-nama="{{ $p->nama }}"
                                        data-telp="{{ $p->no_telp }}"
                                        data-alamat="{{ $p->alamat }}"
                                        {{ old('pelanggan_id') == $p->pelanggan_id ? 'selected' : '' }}>
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
                                <input type="text" class="form-control bg-light" id="display_telp" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control bg-light" id="display_alamat" readonly>
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
                        <div class="mb-3">
                            <label for="layanan_id" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                            <select class="form-select @error('layanan_id') is-invalid @enderror" 
                                    id="layanan_id" name="layanan_id" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanan as $l)
                                <option value="{{ $l->layanan_id }}" 
                                        data-jenis="{{ $l->jenis_cucian }}"
                                        data-durasi="{{ $l->durasi_hari }}"
                                        {{ old('layanan_id') == $l->layanan_id ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }} ({{ $l->durasi_hari }} hari)
                                </option>
                                @endforeach
                            </select>
                            @error('layanan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ TAMBAHAN BARU: Dropdown Jenis Cucian -->
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
                                Pilih kiloan untuk cucian ditimbang per Kg, atau satuan untuk per item (kaos, celana, dll)
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_ambil" class="form-label">Jenis Pengambilan <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_ambil') is-invalid @enderror" 
                                        id="jenis_ambil" name="jenis_ambil" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="diantar" {{ old('jenis_ambil') == 'diantar' ? 'selected' : '' }}>Diantar</option>
                                    <option value="ambil_sendiri" {{ old('jenis_ambil') == 'ambil_sendiri' ? 'selected' : '' }}>Ambil Sendiri</option>
                                </select>
                                @error('jenis_ambil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="metode_bayar" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('metode_bayar') is-invalid @enderror" 
                                        id="metode_bayar" name="metode_bayar" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="e-wallet" {{ old('metode_bayar') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                                @error('metode_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="2" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- FORM KILOAN OFFLINE -->
                <div class="card mb-4 card-kiloan-offline" style="display:none;">
                    <div class="card-header bg-warning text-white">
                        <h4 class="text-white mb-0">
                            <i class="material-icons md-scale"></i>
                            Input Berat Cucian (Kiloan)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="material-icons md-info"></i>
                            <strong>Cucian Offline - Kiloan:</strong> 
                            Silakan timbang cucian dan input beratnya di bawah. Total harga akan otomatis dihitung.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="berat_kiloan" class="form-label">Berat Cucian (Kg) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" 
                                           class="form-control @error('berat_kiloan') is-invalid @enderror" 
                                           id="berat_kiloan" 
                                           name="berat_kiloan" 
                                           step="0.1" 
                                           min="0.1"
                                           placeholder="0.0"
                                           value="{{ old('berat_kiloan') }}">
                                    <span class="input-group-text">Kg</span>
                                </div>
                                @error('berat_kiloan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan berat hasil timbangan</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga per Kg</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control bg-light" id="harga_per_kg" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-2">Preview Kalkulasi</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Berat:</span>
                                    <strong id="preview-berat">0.0 Kg</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Harga per Kg:</span>
                                    <strong id="preview-harga">Rp 0</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5>Total Harga:</h5>
                                    <h5 class="text-primary" id="estimasi_kiloan">Rp 0</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM ITEM SATUAN -->
                <div class="card mb-4 card-item-satuan" style="display:none;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Item Cucian (Satuan)</h4>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                            <i class="material-icons md-add"></i> Tambah Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="items-container">
                            <div class="item-row border rounded p-3 mb-3" data-index="0">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Item <span class="text-danger">*</span></label>
                                        <select class="form-select item-select" name="items[0][list_harga_id]">
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
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control item-jumlah" name="items[0][jumlah]" min="1" value="1">
                                    </div>

                                    <div class="col-md-3 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger w-100" onclick="removeItem(0)" disabled>
                                            <i class="material-icons md-delete"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi (Opsional)</label>
                                        <input type="text" class="form-control" name="items[0][deskripsi]" 
                                               placeholder="Contoh: Warna biru, ada noda">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-white mb-0">Ringkasan</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jenis Order:</span>
                            <strong class="badge bg-info">Offline</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jenis Cucian:</span>
                            <strong id="summary-jenis">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong id="summary-items">0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Berat:</span>
                            <strong id="summary-weight">-</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total Harga:</h5>
                            <h5 class="text-primary" id="summary-total">Rp 0</h5>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="material-icons md-save"></i> Simpan Cucian
                        </button>
                        <a href="{{ route('staff.cucian.index') }}" class="btn btn-light w-100">
                            <i class="material-icons md-close"></i> Batal
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Panduan</h4>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2"><strong>Jenis Cucian:</strong></p>
                        <ul class="small text-muted mb-3">
                            <li><strong>Kiloan:</strong> Per Kg, berat diinput langsung saat order offline</li>
                            <li><strong>Satuan:</strong> Per item (kaos, celana, dll)</li>
                        </ul>

                        <p class="small text-muted mb-2"><strong>Order Offline:</strong></p>
                        <ul class="small text-muted mb-0">
                            <li>Pelanggan datang langsung</li>
                            <li>Semua data diisi saat ini</li>
                            <li>Metode bayar wajib dipilih</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
let itemIndex = 0;
let hargaPerKgGlobal = 0;

// Load pelanggan info saat dipilih
document.getElementById('pelanggan_id')?.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    document.getElementById('display_telp').value = option.dataset.telp || '-';
    document.getElementById('display_alamat').value = option.dataset.alamat || '-';
});

// ✅ Handle jenis cucian change (BARU)
document.getElementById('jenis_cucian')?.addEventListener('change', handleJenisCucianChange);

// Handle input berat untuk kiloan
document.getElementById('berat_kiloan')?.addEventListener('input', calculateKiloan);

function handleJenisCucianChange() {
    const jenisCucianSelect = document.getElementById('jenis_cucian');
    const jenisCucian = jenisCucianSelect.value;

    // Hide all sections first
    const cardKiloan = document.querySelector('.card-kiloan-offline');
    const cardSatuan = document.querySelector('.card-item-satuan');
    
    if (cardKiloan) cardKiloan.style.display = 'none';
    if (cardSatuan) cardSatuan.style.display = 'none';

    if (!jenisCucian) {
        document.getElementById('summary-jenis').textContent = '-';
        resetSummary();
        return;
    }

    // Update summary jenis
    document.getElementById('summary-jenis').textContent = jenisCucian.charAt(0).toUpperCase() + jenisCucian.slice(1);

    // Show form berdasarkan jenis cucian
    if (jenisCucian === 'kiloan') {
        // KILOAN OFFLINE: Tampilkan form input berat
        if (cardKiloan) cardKiloan.style.display = 'block';
        loadHargaKiloan();
        
        // Reset summary
        document.getElementById('summary-items').textContent = '1 batch';
        document.getElementById('summary-weight').textContent = '0 Kg';
        document.getElementById('summary-total').textContent = 'Rp 0';
        
        // Trigger calculate jika ada nilai
        const beratInput = document.getElementById('berat_kiloan');
        if (beratInput && beratInput.value) {
            calculateKiloan();
        }
    } else if (jenisCucian === 'satuan') {
        // SATUAN: Tampilkan form item
        if (cardSatuan) cardSatuan.style.display = 'block';
        updateSummary();
    }
}

function loadHargaKiloan() {
    // Ambil harga kiloan dari list harga pertama yang punya harga kiloan
    const listHarga = @json($listHarga->where('harga_kiloan', '>', 0)->first());
    if (listHarga) {
        hargaPerKgGlobal = listHarga.harga_kiloan;
        document.getElementById('harga_per_kg').value = new Intl.NumberFormat('id-ID').format(hargaPerKgGlobal);
        document.getElementById('preview-harga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaPerKgGlobal);
    }
}

function calculateKiloan() {
    const beratInput = document.getElementById('berat_kiloan');
    const berat = parseFloat(beratInput?.value) || 0;
    const total = berat * hargaPerKgGlobal;
    
    // Update preview
    document.getElementById('preview-berat').textContent = berat.toFixed(1) + ' Kg';
    document.getElementById('estimasi_kiloan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    
    // Update summary
    document.getElementById('summary-weight').textContent = berat.toFixed(1) + ' Kg';
    document.getElementById('summary-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    document.getElementById('summary-items').textContent = '1 batch';
}

function addItem() {
    itemIndex++;
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'item-row border rounded p-3 mb-3';
    newItem.setAttribute('data-index', itemIndex);
    
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Pilih Item <span class="text-danger">*</span></label>
                <select class="form-select item-select" name="items[${itemIndex}][list_harga_id]">
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
                <label class="form-label">Jumlah</label>
                <input type="number" class="form-control item-jumlah" name="items[${itemIndex}][jumlah]" min="1" value="1">
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100" onclick="removeItem(${itemIndex})">
                    <i class="material-icons md-delete"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <label class="form-label">Deskripsi (Opsional)</label>
                <input type="text" class="form-control" name="items[${itemIndex}][deskripsi]" 
                       placeholder="Contoh: Warna biru, ada noda">
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    
    // Add event listeners untuk update summary
    const select = newItem.querySelector('.item-select');
    const jumlah = newItem.querySelector('.item-jumlah');
    
    select.addEventListener('change', updateSummary);
    jumlah.addEventListener('input', updateSummary);
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

    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('select[name^="items"]');
        const option = select?.options[select.selectedIndex];
        
        if (option && option.value) {
            totalItems++;
            const hargaSatuan = parseFloat(option.dataset.satuan) || 0;
            const jumlahInput = row.querySelector('input[name$="[jumlah]"]');
            const jumlah = parseInt(jumlahInput?.value) || 1;
            totalPrice += jumlah * hargaSatuan;
        }
    });

    document.getElementById('summary-items').textContent = totalItems;
    document.getElementById('summary-weight').textContent = '-';
    document.getElementById('summary-total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
}

function resetSummary() {
    document.getElementById('summary-items').textContent = '0';
    document.getElementById('summary-weight').textContent = '-';
    document.getElementById('summary-total').textContent = 'Rp 0';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const jenisCucianSelect = document.getElementById('jenis_cucian');
    if (jenisCucianSelect?.value) {
        handleJenisCucianChange();
    }
    
    // Add event listeners untuk items yang sudah ada (dari old input)
    document.querySelectorAll('.item-select').forEach(select => {
        select.addEventListener('change', updateSummary);
    });
    
    document.querySelectorAll('.item-jumlah').forEach(input => {
        input.addEventListener('input', updateSummary);
    });
    
    // Load pelanggan info if already selected
    const pelangganSelect = document.getElementById('pelanggan_id');
    if (pelangganSelect?.value) {
        const option = pelangganSelect.options[pelangganSelect.selectedIndex];
        document.getElementById('display_telp').value = option.dataset.telp || '-';
        document.getElementById('display_alamat').value = option.dataset.alamat || '-';
    }
});
</script>
@endsection