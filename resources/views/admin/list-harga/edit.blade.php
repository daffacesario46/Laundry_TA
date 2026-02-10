@extends('admin.layouts.app')
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Edit List Harga</h2>
            <p>Update data list harga</p>
        </div>
        <div>
            <a href="{{ route('admin.list-harga.index') }}" class="btn btn-light">
                <i class="material-icons md-arrow_back"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.list-harga.update', $listHarga->list_harga_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_item') is-invalid @enderror" 
                                   name="nama_item" 
                                   value="{{ old('nama_item', $listHarga->nama_item) }}"
                                   required />
                            @error('nama_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga_satuan') is-invalid @enderror" 
                                           name="harga_satuan" 
                                           value="{{ old('harga_satuan', $listHarga->harga_satuan) }}"
                                           min="0"
                                           required />
                                    @error('harga_satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga per pcs/item</small>
                            </div>      
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons md-save"></i> Update
                            </button>
                            <a href="{{ route('admin.list-harga.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi Data</h5>
                    <p class="text-muted small mb-2">
                        <strong>ID:</strong> {{ $listHarga->list_harga_id }}
                    </p>
                    <p class="text-muted small mb-2">
                        <strong>Dibuat:</strong><br>
                        {{ \Carbon\Carbon::parse($listHarga->created_at)->format('d M Y H:i') }}
                    </p>
                    <p class="text-muted small">
                        <strong>Terakhir Diupdate:</strong><br>
                        {{ \Carbon\Carbon::parse($listHarga->updated_at)->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Status Penggunaan</h5>
                    @if($listHarga->cucianDetail()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="material-icons md-warning"></i>
                            Item ini sedang digunakan dalam <strong>{{ $listHarga->cucianDetail()->count() }}</strong> transaksi cucian
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="material-icons md-check_circle"></i>
                            Item ini belum pernah digunakan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection