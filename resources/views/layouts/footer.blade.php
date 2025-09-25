<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
<footer class="page-footer">
	<input type="hidden" name="idDelete" id="idDelete">
	<p class="mb-0">Copyright © <span id="year"></span> <a href="#"><span id="footer-title">Telenta</span></a>. All right reserved.</p>
</footer>
</div>
<!--end wrapper-->

@include('layouts.switcher')

<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>


<script>

	function haspa(text1) {
		var text = '' + text1;
		var CHARSET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		let encoded = "";
		for (let i = 0; i < text.length; i++) {
			let charCode = text.charCodeAt(i);
			encoded += CHARSET[charCode % CHARSET.length]; 
			encoded += CHARSET[Math.floor(charCode / CHARSET.length)]; 
		}
		return encoded;
	}


	function apsah(encoded1) {
		var encoded = '' + encoded1;
		var CHARSET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		let decoded = "";
		for (let i = 0; i < encoded.length; i += 2) {
			let index1 = CHARSET.indexOf(encoded[i]);
			let index2 = CHARSET.indexOf(encoded[i + 1]);
			let charCode = index1 + index2 * CHARSET.length; 
			decoded += String.fromCharCode(charCode);
		}
		console.log(decoded);
		return decoded;
	}
</script>
<script>

	$(document).ready(function() {
		document.getElementById("year").innerHTML = new Date().getFullYear();
		document.querySelectorAll(".year_dashboard").innerHTML = new Date().getFullYear();

		var monthName = [
		"Januari", "Februari", "Maret", "April", "Mei", "Juni",
		"Juli", "Agustus", "September", "Oktober", "November", "Desember"
		];

		var thisMonth = new Date().getMonth();

		document.querySelectorAll(".month").forEach(function(el) {
			el.innerHTML = monthName[thisMonth];
		});
	});


	function httpPost(url, data) {
		$.ajax({
			method: "POST",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			data: data,
			contentType: "application/json",
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				messageAlert(data, jqXHR.status);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				messageAlert(jqXHR.responseJSON);
			}
		});
	}

	function httpPostFormData(url, data) {
		$.ajax({
			method: "POST",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			data: data,
			processData: false,
			contentType: false,
			success: function(data, textStatus, jqXHR) {
				messageAlert(data, jqXHR.status);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				messageAlert(jqXHR.responseJSON);
			}
		});
	}

	function httpGet(url, data) {
		$.ajax({
			method: "POST",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			data: data,
			contentType: "application/json",
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				organizeData(data, jqXHR.status);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				organizeData(jqXHR.responseJSON);
			}
		});
	}


	function messageAlert(data, httpCode){

		if(httpCode == 200){
			$('#modal-add').modal('hide');
			$('#modal-upload').modal('hide');
			$('#modal-edit').modal('hide');

			reload_table();
			swal("Success", data.message, "success")
			.then( function() {
				reload_table();
			});

		} else {
			$('#btn-save').prop('hidden', false);
			$('#btn-loading').prop('hidden', true);
			
			$('#btn-save-upload').prop('hidden', false);
			$('#btn-loading-upload').prop('hidden', true);
			swal("Failed!", data.message, "error");

		}
	}

	function messageAlertUserProfile(data){

		if(data.code == 200){
			$('#modal-add').modal('hide');
			$('#modal-upload').modal('hide');
			$('#modal-edit').modal('hide');

			swal("Berhasil", data.message, "success")
			.then( function() {
				location.reload();
			});


		} else {
			$('#btn-save').prop('hidden', false);
			$('#btn-loading').prop('hidden', true);
			
			$('#btn-save-upload').prop('hidden', false);
			$('#btn-loading-upload').prop('hidden', true);
			swal("Terjadi kesalahan!", data.message, "error");

		}
	}


	function reload_table() {
		table.ajax.reload(null, false);
	}

	function blockChar(evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))

			return false;
		return true;
	}

	function isEmpty(value) {
		if (typeof value === "undefined" || value === null || value === "null" || value === "") {
			return true;
		} else {
			return false;
		}
	}

	function isZero(value) {
		if (value === "0" || value === 0) {
			return true;
		} else {
			return false;
		}
	}

	$('#filter-button-plus').on('click', function () {
		$('#filter-button-plus').prop('hidden', true);
		$('#filter-button-minus').prop('hidden', false);
		$('.filter-content').prop('hidden', false);

		callCondition();
	});

	$('#filter-button-minus').on('click', function () {
		$('#filter-button-plus').prop('hidden', false);
		$('#filter-button-minus').prop('hidden', true);
		$('.filter-content').prop('hidden', true);

	});

	$('#btn-filter').on('click', function () {
		var month = $('#date').val();
		reload_table();

		// console.log(month);
	});

	$('#btn-reset').click(function() {
		$('#form-filter')[0].reset();

		callCondition();

		reload_table();
	});


	$(document).ready(function() {
		

		$('.single-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});
	});



	
	function showOverlay() {
		document.getElementById("loadingOverlay").style.display = "flex";
	}

	function hideOverlay() {
		document.getElementById("loadingOverlay").style.display = "none";
	}

</script>
<!--app JS-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- <script src="{{ asset('assets/plugins/apexcharts-bundle/dist/apexcharts.min.js') }}"></script> -->
</body>

</html>