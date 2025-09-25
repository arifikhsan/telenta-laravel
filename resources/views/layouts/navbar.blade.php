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
						<a class="dropdown-item" href="profil-pengguna"><i class="bx bx-user"></i><span>Profile</span></a>
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


<script type="text/javascript">
	$(document).ready(function() {
		var json;

	});


	// function setUserProfile() {
	// 	// var url = "<?php //echo base_url('get-profil-pengguna') ?>";
	// 	var url = "";

	// 	$.ajax({
	// 		type: "GET",
	// 		url: url,
	// 		dataType: "JSON",
	// 		success: function(response) {
	// 			var fullname = document.getElementsByClassName('user-name');
	// 			var imgElements = document.getElementsByClassName('user-img');

	// 			for (var i = 0; i < fullname.length; i++) {
	// 				fullname[i].innerHTML = response.name;
	// 			}


	// 			for (var i = 0; i < imgElements.length; i++) {
	// 				// imgElements[i].src = "<?php //echo base_url('img/')?>"+response.picture;
	// 			}

				

	// 		},
	// 		error: function(jqXHR, textStatus, errorThrown) {
	// 			console.log('System error');
	// 		}
	// 	});
	// }
</script>