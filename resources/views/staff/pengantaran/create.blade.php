@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Assign Kurir Antar</h2>
            <p>Tugaskan kurir untuk mengantarkan cucian</p>
        </div>
        <div>
            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Form Assign Kurir</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('staff.pengantaran.assign', $cucian->cucian_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="cucian_id" value="{{ $cucian->cucian_id }}">

                        <div class="mb-4">
                            <label class="form-label">Pilih Kurir <span class="text-danger">*</span></label>
                            <select name="kurir_id" class="form-select @error('kurir_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($kurirList as $kurir)
                                    <option value="{{ $kurir->users_id }}" {{ old('kurir_id') == $kurir->users_id ? 'selected' : '' }}>
                                        {{ $kurir->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kurir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Antar <span class="text-danger">*</span></label>
                            <textarea name="alamat_antar" 
                                      class="form-control @error('alamat_antar') is-invalid @enderror" 
                                      rows="3" 
                                      required>{{ old('alamat_antar', $cucian->pelanggan->alamat ?? '') }}</textarea>
                            @error('alamat_antar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" 
                                      class="form-control @error('catatan') is-invalid @enderror" 
                                      rows="2" 
                                      placeholder="Contoh: Antar setelah jam 3 sore, pastikan pelanggan ada di rumah">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('staff.cucian.show', $cucian->cucian_id) }}" class="btn btn-light">
                                <i class="material-icons md-close"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-check"></i> Tugaskan Kurir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Cucian</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">No Order:</td>
                            <td><strong>{{ $cucian->getNoOrder() }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pelanggan:</td>
                            <td><strong>{{ $cucian->pelanggan->nama ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Telepon:</td>
                            <td>{{ $cucian->pelanggan->no_telp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. WhatsApp:</td>
                            <td>{{ $cucian->pelanggan->no_wa ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat:</td>
                            <td>{{ $cucian->pelanggan->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($cucian->status_cucian) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Harga:</td>
                            <td><strong>Rp {{ number_format($cucian->total_harga ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($kurirList->isEmpty())
                <div class="alert alert-warning">
                    <i class="material-icons md-warning"></i>
                    Tidak ada kurir yang tersedia saat ini.
                </div>
            @endif

            <div class="alert alert-info">
                <i class="material-icons md-info"></i>
                <strong>Catatan:</strong> Cucian sudah selesai dan siap untuk diantarkan ke pelanggan.
            </div>
        </div>
    </div>
</section>
@endsection