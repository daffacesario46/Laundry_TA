<form data-confirm="Pembelian" onsubmit="submitAddForm(this, event)" action="{{ route('admin.pembelian.store') }}" method="POST" enctype="multipart/form-data" id="form-pembelian">
	@csrf
    <div class="modal fade text-left" id="modalCreatePembelian" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <section class="content-main">
                        <div class="row">
                            <div class="col-12">
                                <div class="content-header">
                                    <h2 class="content-title">Tambah Pembelian</h2>
                                    <div>
                                        <button class="btn btn-md rounded font-sm hover-up">Tambah</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4>Rincian</h4>
                                    </div>
                                    <div class="card-body">
                                        <form>
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">Tanggal Pembelian</label>
                                                <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" value="{{ old('tanggal_beli') }}"/>
																@error('tanggal_beli')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">Waktu Pembelian</label>
                                                <input type="time" placeholder="hrs:mins" class="form-control" id="jam_beli" name="jam_beli" value="{{ old('jam_beli') }}" />
																@error('jam_beli')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Jenis Bahan</label>
                                                <select class="form-select" name="jenis_bahan">
                                                    <option value="" hidden>-- Pilih Jenis Bahan --</option>
                                                    <option {{ old('jenis_bahan') == 'detergen' ? 'selected' : '' }} value="detergen">Deterjen</option>
                                                    <option {{ old('jenis_bahan') == 'pewangi' ? 'selected' : '' }} value="pewangi">Pewangi</option>
                                                    <option {{ old('jenis_bahan') == 'pelembut' ? 'selected' : '' }} value="pelembut">Pelembut</option>
                                                    <option {{ old('jenis_bahan') == 'pemutih' ? 'selected' : '' }} value="pemutih">Pemutih</option>
                                                </select>
																@error('jenis_bahan')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Merk</label>
                                                <input type="text" placeholder="Masukkan Merk Bahan" class="form-control" id="merk" name="merk" value="{{ old('merk') }}"/>
																@error('merk')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Jumlah Beli/Liter</label>
                                                <input type="text" placeholder="Masukkan Jumlah Beli/Liter" class="form-control" id="jumlah_beli" name="jumlah_beli" value="{{ old('jumlah_beli') }}"/>
																@error('jumlah_beli')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Total Harga Pembelian</label>
                                                <input type="text" placeholder="Masukkan Total Harga Pembelian" class="form-control" id="total_harga" name="total_harga" value="{{ old('total_harga') }}"/>
																@error('total_harga')
																	<p class="text-danger">{{ $message }}</p>
																@enderror
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4>Bukti</h4>
                                    </div>
                                    <div class="card-body">
													<div>
														 <div class="ms-3">
															  <img style="width: 300px; height: auto;" src="{{ asset('assets/imgs/theme/upload.svg') }}" id="previewImageBukti" alt="bukti-pembelian" />
														 </div>
														 <input class="form-control" type="file" name="bukti" id="bukti"/>
														 @error('bukti')
															<p class="text-danger">{{ $message }}</p>
														 @enderror
													</div>
											  </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
	document.getElementById('bukti').addEventListener('change', function(event) {
		 var input = event.target;
		 if (input.files && input.files[0]) {
			  var reader = new FileReader();
			  reader.onload = function(e) {
					var previewImage = document.getElementById('previewImageBukti');
					previewImage.src = e.target.result;
			  }
			  reader.readAsDataURL(input.files[0]);
		 }
	});
	</script>