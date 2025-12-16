@extends('users.layouts.app')
@section('content')

<main class="main">
	<div class="page-content pt-150 pb-150">
		 <div class="container">
			  <div class="row">
					<div class="col-xl-8 col-lg-10 col-md-12 m-auto text-center">
						 <h1 class="display-2 mb-30 text-success">Pembayaran Sukses</h1>
						 <p class="font-lg mb-30">
							pembayaran anda berhasil dengan nomor order <b class="fw-bold"> {{ $no_order ?? '-' }} </b>
						 </p>
						 <a class="btn btn-default hover-up mt-30 text-primary" href="{{ route('user.order.detail', $no_order) }}"> Kembali Ke Detail Order</a>
					</div>
			  </div>
		 </div>
	</div>
</main>

@endsection