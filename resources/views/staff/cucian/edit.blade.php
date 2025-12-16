@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit Cucian</h2>
            <p>Edit data cucian {{ $cucian->no_order }}</p>
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
                    <form action="{{ route('staff.cucian.update', $cucian->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="no_order" class="form-label">No Order <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_order" name="no_order" 
                                   value="{{ $cucian->no_order }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pelanggan_id" name="pelanggan_id" required onchange="loadPelanggan(this)">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" 
                                        data-telp="{{ $p->no_telp }}"
                                        data-alamat="{{ $p->alamat }}"
                                        {{ $cucian->pelanggan_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} - {{ $p->no_telp }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                       value="{{ $cucian->no_telp }}" readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" 
                                       value="{{ $cucian->alamat }}" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_layanan" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_layanan" name="jenis_layanan" required onchange="updateHarga()">
                                <option value="">Pilih Layanan</option>
                                <option value="Cuci + Setrika" data-harga="10000" {{ $cucian->jenis_layanan == 'Cuci + Setrika' ? 'selected' : '' }}>
                                    Cuci + Setrika (Rp 10.000/Kg)
                                </option>
                                <option value="Cuci Kering" data-harga="8000" {{ $cucian->jenis_layanan == 'Cuci Kering' ? 'selected' : '' }}>
                                    Cuci Kering (Rp 8.000/Kg)
                                </option>
                                <option value="Setrika Saja" data-harga="7500" {{ $cucian->jenis_layanan == 'Setrika Saja' ? 'selected' : '' }}>
                                    Setrika Saja (Rp 7.500/Kg)
                                </option>
                                <option value="Cuci + Setrika Express" data-harga="15000" {{ $cucian->jenis_layanan == 'Cuci + Setrika Express' ? 'selected' : '' }}>
                                    Cuci + Setrika Express (Rp 15.000/Kg)
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control" id="berat" name="berat" 
                                       value="{{ $cucian->berat }}" required onchange="hitungTotal()">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="harga_per_kg" class="form-label">Harga per Kg</label>
                                <input type="number" class="form-control" id="harga_per_kg" name="harga_per_kg" 
                                       value="{{ $cucian->harga_per_kg }}" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_harga" name="total_harga" 
                                   value="{{ $cucian->total_harga }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                      placeholder="Catatan tambahan (opsional)">{{ $cucian->catatan }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="menunggu" {{ $cucian->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="proses" {{ $cucian->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ $cucian->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="diambil" {{ $cucian->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Riwayat</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        <i class="material-icons md-access_time"></i> 
                        <strong>Tanggal Masuk:</strong><br>
                        {{ \Carbon\Carbon::parse($cucian->tanggal_masuk)->format('d M Y H:i') }}
                    </p>
                    
                    @if($cucian->tanggal_selesai)
                    <p class="text-muted small mt-3">
                        <i class="material-icons md-check_circle"></i> 
                        <strong>Tanggal Selesai:</strong><br>
                        {{ \Carbon\Carbon::parse($cucian->tanggal_selesai)->format('d M Y H:i') }}
                    </p>
                    @endif

                    <hr>

                    <p class="text-muted small">
                        <i class="material-icons md-info"></i> 
                        <strong>Status Saat Ini:</strong><br>
                        @if($cucian->status == 'menunggu')
                            <span class="badge rounded-pill alert-warning">Menunggu</span>
                        @elseif($cucian->status == 'proses')
                            <span class="badge rounded-pill alert-info">Proses</span>
                        @elseif($cucian->status == 'selesai')
                            <span class="badge rounded-pill alert-success">Selesai</span>
                        @elseif($cucian->status == 'diambil')
                            <span class="badge rounded-pill alert-secondary">Diambil</span>
                        @endif
                    </p>
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