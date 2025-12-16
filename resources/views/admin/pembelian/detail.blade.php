@extends('admin.layouts.app')
@section('content')

@php
use Illuminate\Support\Facades\Storage;
@endphp

<section class="content-main">
	<div class="content-header">
		<a href="{{ route('admin.pembelian') }}"><i class="material-icons md-arrow_back"></i> Go back</a>
		<div>
			<h3 class="content-title card-title">Detail Pembelian Bahan</h3>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-header bg-primary" style="height: 150px"></div>
		<div class="card-body">
			<div class="row">
				<!-- Gambar Bukti -->
				<div class="col-xl col-lg flex-grow-0" style="flex-basis: 230px">
					<div class="img-thumbnail shadow w-100 bg-white position-relative text-center" style="height: 190px; width: 200px; margin-top: -120px">
						@if ($detail->bukti)
							<img src="{{ Storage::url($detail->bukti) }}" style="max-height: 190px; max-width: 200px; object-fit: cover;" class="center-xy img-fluid" alt="Bukti Pembelian" />
						@else
							<img src="{{ asset('assets/imgs/theme/upload.svg') }}" style="max-height: 190px; max-width: 200px;" class="center-xy img-fluid" alt="Bukti Tidak Ada" />
						@endif
					</div>
				</div>

				<!-- Info Kode & Jenis -->
				<div class="col-xl col-lg">
					<h3>{{ $detail->kode_beli }}</h3>
					<p class="text-muted">{{ ucfirst($detail->jenis_bahan) }}</p>
				</div>

				<!-- Tombol Edit & Hapus -->
				<div class="col-xl-6 text-md-end">
					<a href="{{ route('admin.pembelian.edit', $detail->kode_beli) }}" class="btn btn-warning text-white me-2">
						<i class="material-icons md-edit"></i> Edit
					</a>
					<a data-confirm="Pembelian" onclick="hapus(this, event)" href="{{ route('admin.pembelian.hapus', $detail->kode_beli) }}" class="btn btn-danger">
						<i class="material-icons md-delete"></i> Hapus
					</a>
				</div>
			</div>

			<hr class="my-4" />

			<!-- Detail Informasi -->
			<div class="row g-4">
				<!-- Jumlah & Total Harga -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Jumlah Beli/Liter</small>
						</p>
						<h5 class="text-bold mt-2">{{ $detail->jumlah_beli }}</h5>
						<hr class="my-3" />
						<p class="mb-0 text-muted">
							<small>Total Harga</small>
						</p>
						<h5 class="text-bold mt-2">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</h5>
					</article>
				</div>

				<!-- Jenis Bahan -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Jenis Bahan</small>
						</p>
						<h5 class="text-bold mt-2">{{ ucfirst($detail->jenis_bahan) }}</h5>
					</article>
				</div>

				<!-- Merk -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Merk</small>
						</p>
						<h5 class="text-bold mt-2">{{ $detail->merk }}</h5>
					</article>
				</div>

				<!-- Tanggal Beli -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Tanggal Beli</small>
						</p>
						<h5 class="text-bold mt-2">
							@if ($detail->tanggal_beli)
								{{ \Carbon\Carbon::parse($detail->tanggal_beli)->format('d/m/Y') }}
							@else
								<span class="text-muted">-</span>
							@endif
						</h5>
					</article>
				</div>

				<!-- Jam Beli -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Jam Beli</small>
						</p>
						<h5 class="text-bold mt-2">
							{{ $detail->jam_beli ?? '-' }}
						</h5>
					</article>
				</div>

				<!-- Waktu Input -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Waktu Input</small>
						</p>
						<h5 class="text-bold mt-2">
							{{ $detail->wkt_beli->format('d/m/Y H:i') }}
						</h5>
					</article>
				</div>

				<!-- Kode Pembelian -->
				<div class="col-md-12 col-lg-6 col-xl-3">
					<article class="box">
						<p class="mb-0 text-muted">
							<small>Kode Pembelian</small>
						</p>
						<h5 class="text-bold mt-2">{{ $detail->kode_beli }}</h5>
					</article>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection