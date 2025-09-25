<!doctype html>
<!-- <html lang="en" class="<?php //echo $themes ?>"> -->
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
	<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" />

	
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"  />

	<script
	src="https://code.jquery.com/jquery-3.6.0.js"
	integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
	crossorigin="anonymous"></script>
	<title>Telenta</title>

	<script type="text/javascript">
		var table;
	</script>

	<style>
		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.5); /* Grey transparent overlay */
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 999; /* Ensure the overlay is on top of other elements */
			display: none; /* Initially hidden */
		}

		.spinner {
			border: 8px solid #f3f3f3; /* Light grey */
			border-top: 8px solid #3498db; /* Blue */
			border-radius: 50%;
			width: 50px;
			height: 50px;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>

</head>

<body>
	<div class="overlay" id="loadingOverlay">
		<div class="spinner"></div>
	</div>

	<!--wrapper-->
	<div class="wrapper">

		@include('layouts.sidebar')
		@include('layouts.navbar')
