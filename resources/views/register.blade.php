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
	<title>Register account</title>

	<script
	src="https://code.jquery.com/jquery-3.6.0.js"
	integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
	crossorigin="anonymous"></script>
</head>

<body class="bg-login">

	<!--wrapper-->
	<div class="wrapper">
		<div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<meta name="csrf-token" content="{{ csrf_token() }}">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
					<div class="col mx-auto">
						<div class="my-4 text-center">
							<img src="{{ asset('telenta.png') }}" width="220" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Register</h3>
										<p>Already have account? <a href="login">Sign in</a>
										</p>
									</div>
									<div class="d-grid">
									</div>
									<div class="login-separater text-center mb-4"> <span>Register an account to continue</span>
										<hr/>
									</div>
									<div class="form-body">
										<div class="row g-3">
											<div class="col-sm-12">
												<label for="name" class="form-label">Name</label>
												<h6 class="text-danger" id="name-validation"></h6>
												<input type="text" class="form-control" id="name" placeholder="Fullname">
											</div>

											<div class="col-12">
												<label for="email" class="form-label">Email</label>
												<h6 class="text-danger" id="email-validation"></h6>
												<input type="email" class="form-control" id="email" placeholder="email@telenta.com">
											</div>

											<div class="col-12">
												<label for="password" class="form-label">Password</label>
												<h6 class="text-danger" id="password-validation"></h6>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="password" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>

											<div class="col-12">
												<label for="confirm_password" class="form-label">Confirm Password</label>
												<h6 class="text-danger" id="confirm-password-validation"></h6>
												<div class="input-group" id="show_hide_password_confirm">
													<input type="password" class="form-control border-end-0" id="confirm_password" placeholder="Confirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>

											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary" onclick="signup()"><i class='bx bx-user'></i>Sign Up</button>
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
	<script type="text/javascript">

		function blockChar(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))

				return false;
			return true;
		}

		function signup() {
			/* initiate variable */
			var name = $('#name').val();
			var email = $('#email').val();
			var password = $('#password').val();
			var confirmPassword = $('#confirm_password').val();

			if(name == ""){
				$("#name-validation").text("Name cannot be empty");
				return false;
			} else {
				$("#name-validation").text("");
			}


			if(email == ""){
				$("#email-validation").text("Email cannot be empty");
				return false;
			} else if(!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
				$("#email-validation").text("Must Email format");
				return false;
			} else {
				$("#email-validation").text("");
			}


			if(password == ""){
				$("#password-validation").text("Password cannot be null");
				return false;
			} else if(password.length < 8) {
				$("#password-validation").text("Password must be at least 8 characters");
				return false;
			} else {
				$("#password-validation").text("");
			}

			if(confirmPassword == ""){
				$("#confirm-password-validation").text("Confirm Password cannot be null");
				return false;
			} else if(confirmPassword.length < 8) {
				$("#confirm-password-validation").text("Confirm Password must be at least 8 characters");
				return false;
			} else if(password !== confirmPassword) {
				$("#confirm-password-validation").text("Passwords do not match");
				return false;
			} else {
				$("#confirm-password-validation").text("");
			}

			var url = "{{ url('register/store') }}";
			$.ajax({
				method: "POST",
				url: url,
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				data: JSON.stringify({
					name: name,
					email: email,
					password: password
				}),
				contentType: "application/json",
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					console.log(data);
					if(jqXHR.status === 200){

						swal(data.message, "Click OK to continue", "success")
						.then( function() {
							document.location = "{{ url('login') }}";;
						});

					} else {
						swal("Failed!", data.message, "error");
					}


				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Error:", jqXHR.responseJSON);
					console.log("HTTP Status:", jqXHR.status);

					swal("Failed!", jqXHR.responseJSON.message, "error");
				}
			});

		}

	</script>
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

			$("#show_hide_password_confirm a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password_confirm input').attr("type") == "text") {
					$('#show_hide_password_confirm input').attr('type', 'password');
					$('#show_hide_password_confirm i').addClass("bx-hide");
					$('#show_hide_password_confirm i').removeClass("bx-show");
				} else if ($('#show_hide_password_confirm input').attr("type") == "password") {
					$('#show_hide_password_confirm input').attr('type', 'text');
					$('#show_hide_password_confirm i').removeClass("bx-hide");
					$('#show_hide_password_confirm i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

</html>