<!--start header -->
<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>
			<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center">
					<li class="nav-item dropdown dropdown-large">
						<div class="dropdown-menu dropdown-menu-end">
							<div class="header-notifications-list">
							</div>
						</div>
					</li>
					<li class="nav-item dropdown dropdown-large">
						<div class="dropdown-menu dropdown-menu-end">
							<div class="header-message-list">
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="user-box dropdown">
				<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<!-- <img class="user-img" alt="user avatar" src="{{ asset('assets/images/avatars/avatar-1.png') }}"> -->
					<div class="user-info ps-3">
						<p class="user-name mb-0"></p>
						<p class="designattion mb-0">{{ $role }}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li>
						<a class="dropdown-item" href="#" onclick="getClientAndDepartment()"><i class="bx bx-user"></i><span>Update Department and Client</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li>
						<a class="dropdown-item" href="#" onclick="popUpModal()"><i class="bx bx-key"></i><span>Update Password</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('logout') }}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!--end header -->

<!-- Modal Add Client and Department -->
<div class="modal fade" id="modal-new-manager">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h3 class="modal-title"></h3>
			</div>
			<div class="modal-body" id="modal-body">
				<form action="#" id="form" class="form-horizontal">
					<div class="form-body">
						<div class="col-md-12">

							<div class="row mb-2">
								<div class="col-md-1"></div>
								<div class="col-md-3">
									<label>Department</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="department" name="department" class="form-select mb-3"></select>
									</div>
								</div>        
							</div>

							<div class="row mb-2">
								<div class="col-md-1"></div>
								<div class="col-md-3">
									<label>Client</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="client" name="client" class="form-select mb-3"></select>
									</div>
								</div>        
							</div>

						</div>


					</div>
				</form>

				
				

			</div>

			<div class="modal-footer">
				<button id="btn-close-cd" type="button" class="btn btn-secondary" data-bs-dismiss="modal" hidden>Close</button>
				<a id="btn-logout-cd" class="btn btn-secondary" href="{{ route('logout') }}" hidden><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
				<button id="btn-save-cd" type="button" class="btn btn-primary" onclick="setClientAndDepartment()" hidden>Save</button>
				<button id="btn-loading-cd" class="btn btn-primary" type="button" hidden disabled> 
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					Loading...
				</button>
			</div>

		</div>
	</div>
</div>
<!-- End Modal Add Client and Department -->

<!-- Modal Update Password -->
<div class="modal fade" id="modal-password">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h3 class="modal-title-password"></h3>
			</div>
			<div class="modal-body" id="modal-body-password">
				<form action="#" id="form-password" class="form-horizontal">
					<div class="form-body">
						<div class="col-md-12">

							<div class="row mb-2">
								<div class="col-md-1"></div>
								<div class="col-md-3">
									<label>Password</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group" id="show_hide_password">
											<input type="password" class="form-control border-end-0" id="password" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
										</div>
									</div>
								</div>        
							</div>

							<div class="row mb-2">
								<div class="col-md-1"></div>
								<div class="col-md-3">
									<label>New Password</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group" id="show_hide_new_password">
											<input type="password" class="form-control border-end-0" id="new_password" placeholder="New Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
										</div>
									</div>
								</div>        
							</div>

							<div class="row mb-2">
								<div class="col-md-1"></div>
								<div class="col-md-3">
									<label>Confirm Password</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group" id="show_hide_confirm_password">
											<input type="password" class="form-control border-end-0" id="confirm_password" placeholder="Confirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
										</div>
									</div>
								</div>        
							</div>

						</div>


					</div>
				</form>

				
				

			</div>

			<div class="modal-footer">
				<button id="btn-close-password" type="button" class="btn btn-secondary" data-bs-dismiss="modal" hidden>Close</button>
				<button id="btn-save-password" type="button" class="btn btn-primary" onclick="updatePassword()" hidden>Save</button>
				<button id="btn-loading-password" class="btn btn-primary" type="button" hidden disabled> 
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					Loading...
				</button>
			</div>

		</div>
	</div>
</div>
<!-- End Modal Update Password -->


<script type="text/javascript">
	$(document).ready(function() {

		setDepartment(0);

		var json;

		var url = "{{ route('dashboard.managers.verify') }}";

		$.ajax({
			method: "GET",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			success: function(data, textStatus, jqXHR) {
				//ignored

			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('#modal-new-manager').modal({backdrop: 'static', keyboard: false});
				$('#modal-new-manager').modal('show');
				$('.modal-title').text("Please Fill Department and Client");

				$('#btn-close-cd').prop('hidden', true);
				$('#btn-logout-cd').prop('hidden', false);
				$('#btn-save-cd').prop('hidden', false);
				$('#btn-loading-cd').prop('hidden', true);
			}
		});

		// $('#department').select2({
		// 	placeholder: "Select Department",
		// 	allowClear: true,
		// 	ajax: {
		// 		url: "{{ route('dashboard.departments.list') }}",
		// 		dataType: 'json',
		// 		delay: 250,
		// 		processResults: function (data) {
		// 			return {
		// 				results: $.map(data, function (item) {
		// 					return {
		// 						id: item.id,
		// 						text: item.name
		// 					};
		// 				})
		// 			};
		// 		},
		// 		cache: true
		// 	}
		// });

		$('#client').select2({
			placeholder: "Select or type Client",
			allowClear: true,
			tags: true, 
			ajax: {
				url: "{{ route('dashboard.clients.list') }}",
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								id: item.id,
								text: item.name
							};
						})
					};
				},
				cache: true
			},
			createTag: function (params) {
				var term = $.trim(params.term);

				if (term === '') {
					return null;
				}

				return {
					id: term,
					text: term,
					newOption: true
				}
			},
			templateResult: function (data) {
				var $result = $("<span></span>");

				$result.text(data.text);

				if (data.newOption) {
					$result.append(" <em>(Add new Client)</em>");
				}

				return $result;
			}
		});



	});


	function setClientAndDepartment() {
		var data;

		var department = $('#department').val();


		var clientData = $('#client').select2('data')[0]; 

		var clientPayload;

		if (!clientData.newOption) {
			clientPayload = clientData.id;  

			data = JSON.stringify({
				department_id : department,
				client_id : clientPayload
			});

		} else {
			clientPayload = clientData.text; 

			data = JSON.stringify({
				department_id : department,
				client : clientPayload
			});
		}


		var url = "{{ url('dashboard/managers/update') }}";

		$('#btn-save').prop('hidden', true);
		$('#btn-loading').prop('hidden', false);

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
				if(jqXHR.status == 200){
					$('#modal-new-manager').modal('hide');

					swal("Success", data.message, "success");

				} else {
					$('#btn-save').prop('hidden', false);
					$('#btn-loading').prop('hidden', true);

					$('#btn-save-upload').prop('hidden', false);
					$('#btn-loading-upload').prop('hidden', true);
					swal("Failed!", data.message, "error");

				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(jqXHR.status == 200){
					$('#modal-new-manager').modal('hide');

					swal("Success", jqXHR.responseJSON.message, "success");

				} else {
					$('#btn-save').prop('hidden', false);
					$('#btn-loading').prop('hidden', true);

					$('#btn-save-upload').prop('hidden', false);
					$('#btn-loading-upload').prop('hidden', true);
					swal("Failed!", jqXHR.responseJSON.message, "error");

				}
			}
		});
		
	}

	function getClientAndDepartment() {

		$('#modal-new-manager').modal({backdrop: 'static', keyboard: false});
		$('#modal-new-manager').modal('show');
		$('.modal-title').text("Update Department and Client");

		$('#btn-close').prop('hidden', false);
		$('#btn-logout').prop('hidden', true);
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);

		var url = "{{ route('dashboard.managers.show') }}";

		$.ajax({
			method: "GET",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			success: function(data, textStatus, jqXHR) {
				let departmentId = data.body.department_id;
				let clientId = data.body.client_id;

				setDepartment(departmentId);
				setClient(clientId);

			},
			error: function(jqXHR, textStatus, errorThrown) {

			}
		});

	}

	function setDepartment(deptId) {
		$.getJSON("{{ route('dashboard.departments.list') }}", function(data) {
			$('#department').empty();

			$.each(data, function(key, value) {
				if (deptId == value.id) {
					$('#department').append("<option value='" + value.id + "' selected>" + value.name + "</option>");
				} else {
					$('#department').append("<option value='" + value.id + "'>" + value.name + "</option>");
				}
			});

			$('#department').trigger('change');
		});
	}

	function setClient(clientId) {
		$.getJSON("{{ route('dashboard.clients.list') }}", function(data) {
			$('#client').empty();

			$.each(data, function(key, value) {
				if (clientId == value.id) {
					$('#client').append("<option value='" + value.id + "' selected>" + value.name + "</option>");
				} else {
					$('#client').append("<option value='" + value.id + "'>" + value.name + "</option>");
				}
			});

			$('#client').trigger('change');
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

		$("#show_hide_new_password a").on('click', function (event) {
			event.preventDefault();
			if ($('#show_hide_new_password input').attr("type") == "text") {
				$('#show_hide_new_password input').attr('type', 'password');
				$('#show_hide_new_password i').addClass("bx-hide");
				$('#show_hide_new_password i').removeClass("bx-show");
			} else if ($('#show_hide_new_password input').attr("type") == "password") {
				$('#show_hide_new_password input').attr('type', 'text');
				$('#show_hide_new_password i').removeClass("bx-hide");
				$('#show_hide_new_password i').addClass("bx-show");
			}
		});

		$("#show_hide_confirm_password a").on('click', function (event) {
			event.preventDefault();
			if ($('#show_hide_confirm_password input').attr("type") == "text") {
				$('#show_hide_confirm_password input').attr('type', 'password');
				$('#show_hide_confirm_password i').addClass("bx-hide");
				$('#show_hide_confirm_password i').removeClass("bx-show");
			} else if ($('#show_hide_confirm_password input').attr("type") == "password") {
				$('#show_hide_confirm_password input').attr('type', 'text');
				$('#show_hide_confirm_password i').removeClass("bx-hide");
				$('#show_hide_confirm_password i').addClass("bx-show");
			}
		});
	});

	function popUpModal() {

		$('#modal-password').modal({backdrop: 'static', keyboard: false});
		$('#modal-password').modal('show');
		$('.modal-title-password').text("Update Password");

		$('#btn-close-password').prop('hidden', false);
		$('#btn-save-password').prop('hidden', false);
		$('#btn-loading-password').prop('hidden', true);

	}

	function updatePassword() {

		$('#btn-save-password').prop('hidden', true);
		$('#btn-loading-password').prop('hidden', false);

		var password = $('#password').val();
		var newPassword = $('#new_password').val();
		var confirmPassword = $('#confirm_password').val();

		if(password == ""){
			swal("Failed!", "Password cannot be null", "error");
			return false;
		} 

		if(newPassword == ""){
			swal("Failed!", "New Password cannot be null", "error");
			return false;
		} else if(newPassword.length < 8) {
			swal("Failed!", "New Password must be at least 8 characters", "error");
			return false;
		} else if(password === newPassword) {
			swal("Failed!", "New Password cannot be same with current password", "error");
			return false;
		}

		if(confirmPassword == ""){
			swal("Failed!", "Confirm Password cannot be null", "error");
			return false;
		} else if(confirmPassword.length < 8) {
			swal("Failed!", "Confirm Password must be at least 8 characters", "error");
			return false;
		} else if(newPassword !== confirmPassword) {
			swal("Failed!", "Passwords do not match", "error");
			return false;
		} 

		var url = "{{ url('account/update-password') }}";
		$.ajax({
			method: "POST",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			data: JSON.stringify({
				password: password,
				newPassword : newPassword
			}),
			contentType: "application/json",
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				console.log(data);
				if(jqXHR.status === 200){

					$('#modal-password').modal('hide');

					swal("Success", data.message, "success")
					.then( function() {
						location.reload();
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