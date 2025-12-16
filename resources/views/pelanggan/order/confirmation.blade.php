@extends('users.layouts.app')
@section('content')

<section class="content-main">
	<div class="content-header">
		 <div>
			  <h2 class="content-title card-title">Konfirmasi Order</h2>
		 </div>
	</div>
	<div class="card">
		 <header class="card-header">
			  <div class="row align-items-center">
					<div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
						<b>Nama User: {{ auth()->user()->nama }}</b><br />
						 <b>Email: {{ auth()->user()->email }}</b><br />
						<hr>
						 <b>Atas Nama: {{ $request['atas_nama'] }}</b><br />
						 <b>Alamat Antar: {{ $request['alamat_antar'] }}</b><br />
						 <small class="text-muted">(wa) {{ $request['no_wa'] ?? '-' }}</small> <br>
						 <small class="text-muted">(telp) {{ $request['no_telp'] ?? '-' }}</small>
					</div>
					<div class="col-lg-6 col-md-6 ms-auto text-md-end">
						 <button class="btn btn-primary" id="konfirmasiButton" href="#">Konfirmasi</button>
					</div>
			  </div>
		 </header>
		 <!-- card-header end// -->
		 <div class="card-body">
			  <!-- row // -->
			  <div class="row justify-content-center">
					<div class="col-lg-8">
						 <div class="table-responsive">
							  <table class="table">
									<thead>
										 <tr>
											  <th width="40%">Kategori Cucian</th>
											  <th width="20%">Jenis Ambil</th>
											  <th width="20%">Harga/KG</th>
											  <th width="20%">Estimasi Selesai</th>
											  @if ($request['jenis_ambil'] == 'diantar')
											  <th width="20%">Ongkir Antar</th>
											  @endif
											  <th width="20%">Ongkir Jemput</th>
										 </tr>
									</thead>
									<tbody>
										 <tr>
											  <td>{{ $request['kategori']['nama'] }}</td>
											  <td>{{ $request['jenis_ambil'] }}</td>
											  <td>{{ $request['kategori']['harga'] }}</td>
											  <td>{{ $request['kategori']['estimasi_hari'] }} Hari</td>
											  @if ($request['jenis_ambil'] == 'diantar')
											  <td>{{ $request['ongkir_antar'] }}</td>
											  @endif
											  <td>{{ $request['ongkir_jemput'] }}</td>
										 </tr>
									</tbody>
							  </table>
						 </div>
					</div>
			  </div>
		 </div>
		 <!-- card-body end// -->
	</div>
	<!-- card end// -->
</section>

<form onsubmit="submitOrderConfirmation(this, event)" id="konfirmasiForm" action="{{ route('user.order.store') }}" method="POST" style="display: none;">
	@csrf
	<input type="hidden" name="atas_nama" value="{{ $request['atas_nama'] }}">
	<input type="hidden" name="alamat_antar" value="{{ $request['alamat_antar'] }}">
	<input type="hidden" name="no_wa" value="{{ $request['no_wa'] ?? null }}">
	<input type="hidden" name="no_telp" value="{{ $request['no_telp'] ?? null }}">
	<input type="hidden" name="kategori_id" value="{{ $request['kategori']['id'] }}">
	<input type="hidden" name="jenis_ambil" value="{{ $request['jenis_ambil'] }}">
	<button type="submit" id="buttonHidden">Store</button>
</form>

<script>
	document.getElementById('konfirmasiButton').addEventListener('click', function() {
		 document.getElementById('buttonHidden').click();
	});
	function submitOrderConfirmation(element, event){
		event.preventDefault()
		Swal.fire({
			title: `Konfirmasi Order`,
			text: "Apakah Data Sudah Benar?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya!'
		}).then((result) => {
			if (result.isConfirmed) {
				element.submit();
			}
		});
	}
</script>


@endsection
