@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Tambah Cucian Baru</h2>
            <p>Tambahkan data cucian pelanggan</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi Cucian</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.cucian.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="no_order" class="form-label">No Order <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_order" name="no_order" 
                                   placeholder="WW001" value="{{ old('no_order') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pelanggan_id" name="pelanggan_id" required onchange="loadPelanggan(this)">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" 
                                        data-telp="{{ $p->no_telp }}"
                                        data-alamat="{{ $p->alamat }}"
                                        {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} - {{ $p->no_telp }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                       placeholder="08xxxxxxxxxx" value="{{ old('no_telp') }}" readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" 
                                       placeholder="Alamat pelanggan" value="{{ old('alamat') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_layanan" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_layanan" name="jenis_layanan" required onchange="updateHarga()">
                                <option value="">Pilih Layanan</option>
                                <option value="Cuci + Setrika" data-harga="10000" {{ old('jenis_layanan') == 'Cuci + Setrika' ? 'selected' : '' }}>
                                    Cuci + Setrika (Rp 10.000/Kg)
                                </option>
                                <option value="Cuci Kering" data-harga="8000" {{ old('jenis_layanan') == 'Cuci Kering' ? 'selected' : '' }}>
                                    Cuci Kering (Rp 8.000/Kg)
                                </option>
                                <option value="Setrika Saja" data-harga="7500" {{ old('jenis_layanan') == 'Setrika Saja' ? 'selected' : '' }}>
                                    Setrika Saja (Rp 7.500/Kg)
                                </option>
                                <option value="Cuci + Setrika Express" data-harga="15000" {{ old('jenis_layanan') == 'Cuci + Setrika Express' ? 'selected' : '' }}>
                                    Cuci + Setrika Express (Rp 15.000/Kg)
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control" id="berat" name="berat" 
                                       placeholder="0.0" value="{{ old('berat') }}" required onchange="hitungTotal()">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="harga_per_kg" class="form-label">Harga per Kg</label>
                                <input type="number" class="form-control" id="harga_per_kg" name="harga_per_kg" 
                                       placeholder="0" value="{{ old('harga_per_kg') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_harga" name="total_harga" 
                                   placeholder="0" value="{{ old('total_harga') }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informasi</h4>
                </div>
                <div class="card-body">
                    <p><i class="material-icons md-info text-primary"></i> <strong>Tips:</strong></p>
                    <ul class="text-muted small">
                        <li>Pastikan data pelanggan sudah terdaftar</li>
                        <li>Pilih jenis layanan sesuai kebutuhan</li>
                        <li>Berat cucian dalam satuan Kg</li>
                        <li>Total harga otomatis terhitung</li>
                    </ul>
                    
                    <hr>
                    
                    <p><strong>Jenis Layanan:</strong></p>
                    <ul class="text-muted small">
                        <li><strong>Cuci + Setrika:</strong> Rp 10.000/Kg</li>
                        <li><strong>Cuci Kering:</strong> Rp 8.000/Kg</li>
                        <li><strong>Setrika Saja:</strong> Rp 7.500/Kg</li>
                        <li><strong>Express:</strong> Rp 15.000/Kg</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function loadPelanggan(select) {
        const selectedOption = select.options[select.selectedIndex];
        const telp = selectedOption.getAttribute('data-telp');
        const alamat = selectedOption.getAttribute('data-alamat');
        
        document.getElementById('no_telp').value = telp || '';
        document.getElementById('alamat').value = alamat || '';
    }

    function updateHarga() {
        const select = document.getElementById('jenis_layanan');
        const selectedOption = select.options[select.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        
        document.getElementById('harga_per_kg').value = harga || 0;
        hitungTotal();
    }

    function hitungTotal() {
        const berat = parseFloat(document.getElementById('berat').value) || 0;
        const hargaPerKg = parseFloat(document.getElementById('harga_per_kg').value) || 0;
        const total = berat * hargaPerKg;
        
        document.getElementById('total_harga').value = total;
    }
</script>
@endsection