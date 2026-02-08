@extends('staff.layouts.app')

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title">Assign Kurir Jemput</h2>
            <p>Tugaskan kurir untuk menjemput cucian</p>
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

                    <form action="{{ route('staff.penjemputan.assign', $cucian->cucian_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="cucian_id" value="{{ $cucian->cucian_id }}">

                        <div class="mb-4">
                            <label class="form-label">Pilih Kurir <span class="text-danger">*</span></label>
                            <select name="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($kurirList as $kurir)
                                    <option value="{{ $kurir->users_id }}" {{ old('staff_id') == $kurir->users_id ? 'selected' : '' }}>
                                        {{ $kurir->nama }} ({{ ucfirst($kurir->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Jemput <span class="text-danger">*</span></label>
                            <textarea name="alamat_jemput" 
                                      class="form-control @error('alamat_jemput') is-invalid @enderror" 
                                      rows="3" 
                                      required>{{ old('alamat_jemput', $cucian->pelanggan->alamat ?? '') }}</textarea>
                            @error('alamat_jemput')
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
                                <span class="badge bg-info">{{ ucfirst($cucian->status_cucian) }}</span>
                            </td>
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
        </div>
    </div>
</section>
@endsection