<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('telenta.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<title>Telenta</title>
	<script
	src="https://code.jquery.com/jquery-3.6.0.js"
	integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
	crossorigin="anonymous"></script>
</head>

<body class="bg-login">

	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center">
							<!-- <img src="{{ asset('assets/images/new-logo-img.png') }}" width="180" alt="" /> -->
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Selamat Datang di Aplikasi Telenta</h3>
										<p> Belum punya akun? <a href="#" class="text-primary">Daftar</a>
										</p>
										<p id="login-box-msg"></p>
									</div>

									<div class="login-separater text-center mb-4"> <span>Masuk untuk melanjutkan</span>
										<hr/>
									</div>
									<div class="form-body">
										<div class="row g-3">
											<div class="col-12">
												<label for="email" class="form-label">Email</label>
												<input type="text" class="form-control" id="email" placeholder="user@example.com">
											</div>
											<div class="col-12">
												<label for="password" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="password"  placeholder="Password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<meta name="csrf-token" content="{{ csrf_token() }}">
											<div class="col-md-6">
												<div class="form-check form-switch">

												</div>
											</div>
											<div class="col-md-6 text-end">	<a href="reset-password">Lupa kata sandi?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button class="btn btn-primary" onclick="login()"><i class="bx bxs-lock-open"></i>Masuk</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>		
	<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<script type="text/javascript">

		/* init variable */
		var email = document.getElementById("email");
		var pass = document.getElementById("password");

		email.addEventListener("keyup", function(event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				login();
			}
		});

		pass.addEventListener("keyup", function(event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				login();
			}
		});


		function login() {
			/* initiate variable */
			var email = $('#email').val();
			var password = $('#password').val();

				var url = "{{ url('login') }}";
				$.ajax({
					method: "POST",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					data: JSON.stringify({
						email: email,
						password: password
					}),
					contentType: "application/json",
					dataType: "json",
					success: function(data, textStatus, jqXHR) {

						if (jqXHR.status === 200) {
							console.log("redirect to {{ url('dashboard') }}")
							swal("Login berhasil!", "Klik OK untuk melanjutkan", "success")
							.then(function() {
								document.location = "{{ url('dashboard') }}";
							});
						} else {
							$("#login-box-msg").text(data.message || "Terjadi kesalahan");
							$("#login-box-msg").attr('class', 'login-box-msg text-danger');
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log("Error:", jqXHR.responseJSON);
						console.log("HTTP Status:", jqXHR.status);

						// $("#login-box-msg").text(jqXHR.responseJSON?.message || "System error");
						// $("#login-box-msg").attr('class', 'login-box-msg text-danger');
					}
				});
			}

		</script>
		<!--app JS-->
		<script src="{{ asset('assets/js/app.js') }}"></script>
	</body>

	</html>