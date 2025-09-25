@include('layouts.header')

<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">{{ preg_replace('/(?<!\ )[A-Z]/', ' $0', ucfirst(Request::segment(2))) }}</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
			</div>
		</div>
		<!--end breadcrumb-->


		<h6 class="mb-0 text-uppercase">{{ preg_replace('/(?<!\ )[A-Z]/', ' $0', ucfirst(Request::segment(2))) }}</h6>
		<hr/>
		<div class="card">
			<meta name="csrf-token" content="{{ csrf_token() }}">
			<div class="card-header">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 ms-2"></div>
				</div>

			</div><!-- /.card-header -->
			<div class="card-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" width="100%">
						<thead class="table-secondary">
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>Client</th>
								<th>Department</th>
								<th>Created At</th>
								<th>Updated At</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="page-content">
	</div>
</div>
<!--end page wrapper -->

<!-- javascript -->

<script type="text/javascript">
	var titleAdd = "Add Candidate";
	var titleEdit = "Update Candidate";

	$(document).ready(function() {

				table = $('#table').DataTable({
					"processing": true, 
					"serverSide": true, 
					"scrollX": true,
					"order": [], 

					"ajax": {
						"url": "{{ url('dashboard/managers/fetch') }}",
						"type": "POST",
						"headers": {
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
						},
						"data": function(data) {
						}
					},

					"columnDefs": [{
						"targets": [-1], 
						"orderable": false, 
					}, ],

				}); 

			});
</script>


@include('layouts.footer')