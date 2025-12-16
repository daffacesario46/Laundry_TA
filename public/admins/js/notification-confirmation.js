function sessionSuccess(message){
	Swal.fire({
		position: "center",
		icon: "success",
		title: message,
		showConfirmButton: false,
		timer: 2000
	 });
}

function submitAddForm(element, event){
	event.preventDefault()
	let dataConfirm = element.getAttribute('data-confirm');

	Swal.fire({
		 title: `Konfirmasi Data ${dataConfirm}`,
		 text: "Apakah Anda Yakin Ingin Menambahkan Data?",
		 icon: 'warning',
		 showCancelButton: true,
		 confirmButtonColor: '#3085d6',
		 cancelButtonColor: '#d33',
		 confirmButtonText: 'Ya!'
	}).then((result) => {
		 if (result.isConfirmed) {
			  // Submit the form manually
			  element.submit();
		 }
	});
}

function submitUpdateForm(element, event){
	event.preventDefault()
	let dataConfirm = element.getAttribute('data-confirm');

	Swal.fire({
		 title: `Konfirmasi Update Data ${dataConfirm}`,
		 text: "Apakah Anda Yakin ingin Mengubah Data?",
		 icon: 'warning',
		 showCancelButton: true,
		 confirmButtonColor: '#3085d6',
		 cancelButtonColor: '#d33',
		 confirmButtonText: 'Ya!'
	}).then((result) => {
		 if (result.isConfirmed) {
			  // Submit the form manually
			  element.submit();
		 }
	});
}

function actionConfirmation(element, event, message){
	event.preventDefault()
	let dataConfirm = element.getAttribute('data-confirm');

	Swal.fire({
		 title: `Konfirmasi Update Status ${dataConfirm}`,
		 text: message,
		 icon: 'warning',
		 showCancelButton: true,
		 confirmButtonColor: '#3085d6',
		 cancelButtonColor: '#d33',
		 confirmButtonText: 'Ya!'
	}).then((result) => {
		 if (result.isConfirmed) {
			  window.location.href = element.href;
		 }
	});
}

function hapus(element, event){
	event.preventDefault()
	let dataConfirm = element.getAttribute('data-confirm');
	Swal.fire({
		title: `Hapus Data ${dataConfirm}?`,
		text: "Data yang di hapus tidak bisa dikembalikan!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, Hapus!'
	}).then((result) => {
		if(result.isConfirmed) {
			 window.location.href = element.href;
		}
	});
}
