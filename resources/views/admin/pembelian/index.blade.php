@extends('admin.layouts.app')
@section('content')

@if ($errors->any())
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			$('#modalCreatePembelian').modal('show');
		});
	</script>
@endif

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Pembelian Bahan</h2>
            <p>Daftar Pembelian Bahan</p>
        </div>
		  <div class="col-lg-4 col-md-6">
				<input type="text" placeholder="Cari Jenis/Merk/Kode..." id="search" class="form-control bg-white" value="{{ request()->query('search') }}" />
		  </div>
    </div>
	 
    <div class="card mb-4">
        <header class="card-header">
            <form id="form-pembelian" action="{{ route('admin.pembelian') }}">
					<div class="row gx-3">
						<div class="col-lg-2 col-6 col-md-3">
							 <select name="paginate" class="form-select">
								  <option {{ request()->query('paginate') == 15 ? 'selected' : '' }} value="15" >Show 15</option>
								  <option {{ request()->query('paginate') == 30 ? 'selected' : '' }} value="30">Show 30</option>
								  <option {{ request()->query('paginate') == 50 ? 'selected' : '' }} value="50">Show 50</option>
							 </select>
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<button class="btn btn-sm btn-primary">Terapkan</button>
					  </div>
					  <div class="col-lg-3 col-md-6 col-10 ">
							<button class="btn btn-success text-white" data-toggle="modal" data-target="#modalCreatePembelian" type="button">Tambah Pembelian</button>
				  		</div>
				  </div>
				</form>
        </header>
        <!-- card-header end// -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Beli</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Merk</th>
                            <th scope="col">Jumlah Beli/Liter</th>
                            <th scope="col">Total</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembelians as $pembelian)
                            <tr>
                                <td>{{ $pembelian->kode_beli }}</td>
                                <td>{{ $pembelian->wkt_beli }}</td>
                                <td>{{ $pembelian->jenis_bahan }}</td>
                                <td>{{ $pembelian->merk }}</td>
                                <td>{{ $pembelian->jumlah_beli }}</td>
                            <td>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-success" href="{{ route('admin.pembelian.detail', $pembelian->kode_beli) }}">Detail</a>
                                            <a class="dropdown-item text-warning" href="{{ route('admin.pembelian.edit', $pembelian->kode_beli) }}">Edit</a>
                                            <a data-confirm="Pembelian" onclick="hapus(this, event)" class="dropdown-item text-danger delete-link" href="{{ route('admin.pembelian.hapus', $pembelian->kode_beli) }}">Hapus</a>
                                        </div>
                                    </div>
                                    <!-- dropdown //end -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->
    </div>
    <!-- card end// -->
    <div class="pagination-area mt-15 mb-50">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-start">
                {{ $pembelians->links() }}
            </ul>
        </nav>
    </div>
</section>

<script>
		document.getElementById('search').addEventListener('keypress', function(e) {
		if(e.key === 'Enter') {
			window.location = window.location.origin + window.location.pathname + '?search=' + this.value
		}
	})
</script>

@include('admin.pembelian.create')

@endsection
