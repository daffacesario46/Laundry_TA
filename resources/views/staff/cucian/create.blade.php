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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Informasi Pelanggan</h4>
                        {{-- ✅ BUTTON TAMBAH PELANGGAN --}}
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
                            <i class="material-icons md-add"></i> Tambah Pelanggan
                        </button>
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

                        {{-- Display Info --}}
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
                                <label for="layanan_id" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-select @error('layanan_id') is-invalid @enderror" 
                                        id="layanan_id" name="layanan_id" required disabled>
                                    <option value="">-- Pilih Jenis Cucian Dulu --</option>
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

                            {{-- ✅ 3. METODE CUCI (BARU) --}}
                            <div class="mb-3">
                                <label for="metode_cuci" class="form-label">Metode Cuci <span class="text-danger">*</span></label>
                                <select class="form-select @error('metode_cuci') is-invalid @enderror" 
                                        id="metode_cuci" name="metode_cuci" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="normal" {{ old('metode_cuci') == 'normal' ? 'selected' : '' }}>
                                        Normal (Harga Standar)
                                    </option>
                                    <option value="express" {{ old('metode_cuci') == 'express' ? 'selected' : '' }}>
                                        Express (+50% dari harga)
                                    </option>
                                </select>
                                @error('metode_cuci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="material-icons md-info" style="font-size: 14px;"></i>
                                    Express akan menambah biaya 50% dari total harga layanan
                                </small>
                            </div>

                            {{-- Row untuk Jenis Ambil dan Metode Bayar --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_ambil" class="form-label">Jenis Pengambilan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_ambil') is-invalid @enderror" 
                                            id="jenis_ambil" name="jenis_ambil" required>
                                        <option value="ambil_sendiri" {{ old('jenis_ambil') == 'ambil_sendiri' ? 'selected' : '' }}>
                                            Ambil Sendiri
                                        </option>
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
                            <i class="material-icons md-scale"></i> Input Berat Cucian (Kiloan)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="material-icons md-info"></i>
                            <strong>Cucian Offline - Kiloan:</strong> Silakan timbang cucian dan input beratnya di bawah. Total harga akan otomatis dihitung.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="berat_kiloan" class="form-label">Berat Cucian (Kg) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="form-control @error('berat_kiloan') is-invalid @enderror" 
                                        id="berat_kiloan" name="berat_kiloan" 
                                        step="0.1" min="0.1" placeholder="0.0" 
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

                        {{-- ✅ PREVIEW KALKULASI DENGAN BIAYA EXPRESS --}}
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
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong id="preview-subtotal">Rp 0</strong>
                                </div>
                                
                                {{-- ✅ Biaya Express (Conditional) --}}
                                <div class="d-flex justify-content-between mb-2" id="preview-express-row" style="display: none;">
                                    <span class="text-warning">
                                        <i class="material-icons md-flash_on" style="font-size: 14px; vertical-align: middle;"></i>
                                        Biaya Express (50%):
                                    </span>
                                    <strong class="text-warning" id="preview-express">Rp 0</strong>
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
    {{-- ✅ MODAL TAMBAH PELANGGAN --}}
        <div class="modal fade" id="modalTambahPelanggan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formTambahPelanggan">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="material-icons md-person_add"></i>
                                Tambah Pelanggan Baru
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="material-icons md-info"></i>
                                <small>Pelanggan baru akan langsung ditambahkan ke sistem dan dapat dipilih untuk order ini.</small>
                            </div>

                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama" required>
                                <div class="invalid-feedback" id="error-nama"></div>
                            </div>

                            <div class="mb-3">
                                <label for="no_telp_pelanggan" class="form-label">
                                    No Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="no_telp_pelanggan" name="no_telp" required 
                                    placeholder="08xxxxxxxxxx">
                                <div class="invalid-feedback" id="error-no_telp"></div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat_pelanggan" class="form-label">
                                    Alamat <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="alamat_pelanggan" name="alamat" rows="3" required 
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                <div class="invalid-feedback" id="error-alamat"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="material-icons md-close"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success" id="btnSimpanPelanggan">
                                <i class="material-icons md-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</section>

<script> 
let itemIndex = 0;
let hargaPerKgGlobal = 0;

// Simpan semua opsi layanan
const layananSelect = document.getElementById('layanan_id');
const allLayananOptions = Array.from(layananSelect.querySelectorAll('option'));

// ✅ Event listener untuk metode cuci
document.getElementById('metode_cuci')?.addEventListener('change', function() {
    // Re-calculate harga saat metode cuci berubah
    const jenisCucian = document.getElementById('jenis_cucian')?.value;
    
    if (jenisCucian === 'kiloan') {
        calculateKiloan();
    } else if (jenisCucian === 'satuan') {
        updateSummary();
    }
});

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

// Load pelanggan info
document.getElementById('pelanggan_id')?.addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    document.getElementById('display_telp').value = option.dataset.telp || '-';
    document.getElementById('display_alamat').value = option.dataset.alamat || '-';
});

// Handle input berat
document.getElementById('berat_kiloan')?.addEventListener('input', calculateKiloan);

// ✅ FUNGSI GET EXPRESS MULTIPLIER
function getExpressMultiplier() {
    const metodeCuci = document.getElementById('metode_cuci')?.value;
    return metodeCuci === 'express' ? 1.5 : 1; // Express = 150% (1 + 0.5)
}

// ✅ FUNGSI SHOW/HIDE EXPRESS INFO
function updateExpressDisplay(subtotal, isExpress) {
    const expressRow = document.getElementById('preview-express-row');
    const expressAmount = document.getElementById('preview-express');
    
    if (isExpress && subtotal > 0) {
        const biayaExpress = subtotal * 0.5; // 50% dari subtotal
        expressAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(biayaExpress);
        if (expressRow) expressRow.style.display = 'flex';
    } else {
        if (expressRow) expressRow.style.display = 'none';
    }
}

function handleJenisCucianChange() {
    const jenisCucianSelect = document.getElementById('jenis_cucian');
    const jenisCucian = jenisCucianSelect?.value;

    const cardKiloan = document.querySelector('.card-kiloan-offline');
    const cardSatuan = document.querySelector('.card-item-satuan');
    
    if (cardKiloan) cardKiloan.style.display = 'none';
    if (cardSatuan) cardSatuan.style.display = 'none';

    if (!jenisCucian) {
        document.getElementById('summary-jenis').textContent = '-';
        resetSummary();
        return;
    }

    document.getElementById('summary-jenis').textContent = 
        jenisCucian.charAt(0).toUpperCase() + jenisCucian.slice(1);

    if (jenisCucian === 'kiloan') {
        if (cardKiloan) cardKiloan.style.display = 'block';
        loadHargaKiloan();
        
        document.getElementById('summary-items').textContent = '1 batch';
        document.getElementById('summary-weight').textContent = '0 Kg';
        document.getElementById('summary-total').textContent = 'Rp 0';
        
        const beratInput = document.getElementById('berat_kiloan');
        if (beratInput && beratInput.value) {
            calculateKiloan();
        }
    } else if (jenisCucian === 'satuan') {
        if (cardSatuan) cardSatuan.style.display = 'block';
        updateSummary();
    }
}

function loadHargaKiloan() {
    const listHarga = @json($listHarga->where('harga_kiloan', '>', 0)->first());
    
    if (listHarga) {
        hargaPerKgGlobal = listHarga.harga_kiloan;
        document.getElementById('harga_per_kg').value = 
            new Intl.NumberFormat('id-ID').format(hargaPerKgGlobal);
        document.getElementById('preview-harga').textContent = 
            'Rp ' + new Intl.NumberFormat('id-ID').format(hargaPerKgGlobal);
    }
}

// ✅ CALCULATE KILOAN DENGAN EXPRESS
function calculateKiloan() {
    const beratInput = document.getElementById('berat_kiloan');
    const berat = parseFloat(beratInput?.value) || 0;
    const subtotal = berat * hargaPerKgGlobal;
    
    // Apply express multiplier
    const multiplier = getExpressMultiplier();
    const total = subtotal * multiplier;
    const isExpress = multiplier > 1;

    // Update preview
    document.getElementById('preview-berat').textContent = berat.toFixed(1) + ' Kg';
    document.getElementById('preview-subtotal').textContent = 
        'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    
    // Show/hide biaya express
    updateExpressDisplay(subtotal, isExpress);
    
    document.getElementById('estimasi_kiloan').textContent = 
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);

    // Update summary
    document.getElementById('summary-weight').textContent = berat.toFixed(1) + ' Kg';
    document.getElementById('summary-total').textContent = 
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    document.getElementById('summary-items').textContent = '1 batch';
}

// Item satuan functions
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
                    <option value="{{ $lh->list_harga_id }}" data-satuan="{{ $lh->harga_satuan }}">
                        {{ $lh->nama_item }} - Rp {{ number_format($lh->harga_satuan, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" class="form-control item-jumlah" 
                       name="items[${itemIndex}][jumlah]" min="1" value="1">
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

// ✅ UPDATE SUMMARY UNTUK SATUAN DENGAN EXPRESS
function updateSummary() {
    let totalItems = 0;  // Total jumlah pieces
    let subtotal = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('select[name*="list_harga_id"]');
        const option = select?.options[select.selectedIndex];
        
        if (option && option.value) {
            const hargaSatuan = parseFloat(option.dataset.satuan) || 0;
            const jumlahInput = row.querySelector('input[name*="jumlah"]');
            const jumlah = parseInt(jumlahInput?.value) || 1;
            
            // ✅ PERBAIKAN: Tambahkan jumlah sebenarnya, bukan increment
            totalItems += jumlah;  // Sekarang: 12 + 1 = 13
            subtotal += jumlah * hargaSatuan;
        }
    });

    // Apply express multiplier
    const multiplier = getExpressMultiplier();
    const total = subtotal * multiplier;

    // ✅ Tampilkan dengan satuan
    document.getElementById('summary-items').textContent = totalItems + ' pcs';
    document.getElementById('summary-weight').textContent = '-';
    document.getElementById('summary-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
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
        jenisCucianSelect.dispatchEvent(new Event('change'));
    }
    
    document.querySelectorAll('.item-select').forEach(select => {
        select.addEventListener('change', updateSummary);
    });
    
    document.querySelectorAll('.item-jumlah').forEach(input => {
        input.addEventListener('input', updateSummary);
    });
    
    const pelangganSelect = document.getElementById('pelanggan_id');
    if (pelangganSelect?.value) {
        const option = pelangganSelect.options[pelangganSelect.selectedIndex];
        document.getElementById('display_telp').value = option.dataset.telp || '-';
        document.getElementById('display_alamat').value = option.dataset.alamat || '-';
    }
});

document.getElementById('formTambahPelanggan')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnSimpanPelanggan');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    
    // Clear previous errors
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    const formData = new FormData(this);
    
    fetch('{{ route("staff.pelanggan.store-ajax") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tambahkan pelanggan baru ke dropdown
            const select = document.getElementById('pelanggan_id');
            const option = document.createElement('option');
            option.value = data.pelanggan.pelanggan_id;
            option.textContent = `${data.pelanggan.nama} - ${data.pelanggan.no_telp}`;
            option.setAttribute('data-nama', data.pelanggan.nama);
            option.setAttribute('data-telp', data.pelanggan.no_telp);
            option.setAttribute('data-alamat', data.pelanggan.alamat);
            option.selected = true;
            select.appendChild(option);
            
            // Update display fields
            document.getElementById('display_telp').value = data.pelanggan.no_telp;
            document.getElementById('display_alamat').value = data.pelanggan.alamat;
            
            // Close modal dan reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahPelanggan'));
            modal.hide();
            this.reset();
            
            // Show toast/alert
            showToast('success', 'Pelanggan berhasil ditambahkan!');
        } else {
            // Show validation errors
            if (data.errors) {
                for (let field in data.errors) {
                    const input = document.querySelector(`[name="${field}"]`);
                    const error = document.getElementById(`error-${field}`);
                    if (input && error) {
                        input.classList.add('is-invalid');
                        error.textContent = data.errors[field][0];
                    }
                }
            } else {
                alert('Error: ' + (data.message || 'Gagal menambahkan pelanggan'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan pelanggan');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

// Helper function untuk toast notification (opsional)
function showToast(type, message) {
    // Bisa pakai SweetAlert2, Toastr, atau alert biasa
    alert(message);
}
</script>

@endsection