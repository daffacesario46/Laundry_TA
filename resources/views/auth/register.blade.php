<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>Daftar - Washwes Laundry</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="" />
        <meta property="og:url" content="" />
        <meta property="og:image" content="" />
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('admins') }}/imgs/theme/washwes.png" />
        <!-- Template CSS -->
        <link href="{{ asset('admins') }}/css/main.css?v=1.1" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <main>
			
            <section class="content-main mt-40 mb-80">
					<a href="/" class="p-0 m-0">
						<h1 class="p-0 m-0">Washwes Laundry</h1>
					 </a>
                <div class="card mx-auto card-login">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Buat Akun Baru</h4>
                        <form onsubmit="registerConfirmation(this, event)" action="{{ route('register.post') }}" method="POST">
									@csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input class="form-control" name="nama" placeholder="masukkan nama lengkapmu" type="text" value="{{ old('nama') }}" required />
										  @error('nama')
											<p class="text-danger">{{ $message }}</p>
										  @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input class="form-control" name="alamat" placeholder="masukkan alamat rumahmu" type="text" value="{{ old('alamat') }}" required />
										  @error('alamat')
										  <p class="text-danger">{{ $message }}</p>
										 @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control" name="email" placeholder="emailmu@mail.com" type="email" value="{{ old('email') }}" required />
										  @error('email')
										  <p class="text-danger">{{ $message }}</p>
										 @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telepon</label>
                                <div class="row gx-2">
                                    <div class="col-4"><input class="form-control" name="kode_telp" value="62" type="text" readonly/></div>
                                    <div class="col-8"><input class="form-control" name="no_telp" placeholder="812345xxx" type="text" value="{{ old('no_telp') }}" required /></div>
												@error('no_telp')
												<p class="text-danger">{{ $message }}</p>
											  @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Whatsapp</label>
                                <div class="row gx-2">
                                    <div class="col-4"><input class="form-control" name="kode_wa" value="62" type="text" readonly/></div>
                                    <div class="col-8"><input class="form-control" name="no_wa" placeholder="812345xxx" type="text" value="{{ old('no_wa') }}" required /></div>
												@error('no_wa')
												<p class="text-danger">{{ $message }}</p>
											  @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control" name="password" placeholder="******" type="password" minlength="6" required />
										  @error('password')
										  <p class="text-danger">{{ $message }}</p>
										 @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input class="form-control" name="password_confirmation" placeholder="******" type="password" minlength="6" required />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Konfirmasi</button>
                            </div>
									 <div class="mb-3 mt-30">
										<p class="small text-muted">Dengan mendaftar, anda mengonfirmasi bahwa anda telah membaca dan menerima pemberitahuan pengguna dan kebijakan privasi kami.</p>
								  </div>
                        </form>
								<p class="text-center mb-2">Sudah punya akun? <a href="{{ route('login') }}">masuk</a></p>
                    </div>
                </div>
            </section>
        </main>
        <script src="{{ asset('admins') }}/js/vendors/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('admins') }}/js/vendors/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('admins') }}/js/vendors/jquery.fullscreen.min.js"></script>
        <!-- Main Script -->
        <script src="{{ asset('admins') }}/js/main.js?v=1.1" type="text/javascript"></script>
		  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		 <script>
			function registerConfirmation(element, event){
				event.preventDefault()
				Swal.fire({
					title: `Konfirmasi Registrasi?`,
					text: "Apakah Data Anda Sudah Benar?",
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

    </body>
</html>