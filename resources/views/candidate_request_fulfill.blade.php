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
						<li class="breadcrumb-item active" aria-current="page">Candidate Request Fulfillment</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->


		<h6 class="mb-0 text-uppercase">Candidate Request Fulfillment</h6>
		<hr/>
		<div class="card">
			<meta name="csrf-token" content="{{ csrf_token() }}">
			<div class="card-header">
				<div class="d-flex align-items-center" id="detail_requirement">
					
				</div>

			</div><!-- /.card-header -->
			<div class="card-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" width="100%">
						<thead class="table-secondary">
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>Status</th>
								<th>CV</th>
								<th>Position</th>
								<th>Date Filled</th>
								<th>Actions</th>
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

<!-- Modal Tambah Data -->

<div class="modal fade" id="modal-add" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="#" id="form" class="form-horizontal">
					<div class="form-body">
						<input type="hidden" name="existing_id" id="existing_id">
						<div class="col-md-12">

							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Internal Interview</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input id="internal_interview" class="form-control mb-3" type="datetime-local" placeholder="internal interview.. " >
									</div>

								</div>
							</div>

							
						</div>


					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button id="btn-save" type="button" class="btn btn-primary" onclick="save()" hidden>Save</button>
				<button id="btn-loading" class="btn btn-primary" type="button" hidden disabled> 
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					Loading...
				</button>
			</div>
		</div>
	</div>
</div>

<!-- End Modal Tambah Data -->

<!-- javascript -->

<script type="text/javascript">

	var titleAdd = "Set Date Internal Interview";
	var titleEdit = "Update Candidate";

	$(document).ready(function() {
		getRequest();

		var requestId = "{{ Request::segment(4) }}";
		let baseUrl = "{{ url('dashboard/candidate-requests/fulfill/fetch') }}";
		let fullUrl = baseUrl + "/" + requestId;

		table = $('#table').DataTable({
			"processing": true, 
			"serverSide": true, 
			"scrollX": true,
			"order": [], 

			"ajax": {
				"url": fullUrl,
				"type": "POST",
				"headers": {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				"data": function(data) {
					checkUsersAccess();
				}
			},

			"columnDefs": [{
				"targets": [-1], 
				"orderable": false, 
			}, ],

		}); 

	});

	function checkUsersAccess() {

		var name = 'Candidate Requests';
		var payload = JSON.stringify({
			name : name
		});

		var url = "{{ route('dashboard.checkaccess') }}";
		setTimeout(function() { 

			$.ajax({
				method: "POST",
				url: url,
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				data: payload,
				contentType: "application/json",
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					if(data.create != '1'){
						$('.as-create').prop('hidden', true);
					}
					if(data.read != '1'){
						$('.as-detail').prop('hidden', true);
					}
					if(data.update != '1'){
						$('.as-update').prop('hidden', true);
					}
					if(data.delete != '1'){
						$('.as-delete').prop('hidden', true);
					}
					if(data.export != '1'){
						$('.as-export').prop('hidden', true);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('Error');
				}
			});
		}, 500);
	}

	function pushCandidate(id) {
		var requestId = "{{ Request::segment(4) }}";

		var data = JSON.stringify({ 
			candidate_id : id, 
			request_id : requestId 
		}); 

		url = "{{ url('dashboard/candidate-requests/fulfill/store') }}";
		httpPost(url, data);
	}

	function getRequest() {
		var requestId = "{{ Request::segment(4) }}";

		var url = "{{ route('dashboard.candidate-request.show') }}";
		var payload = JSON.stringify({
			request_id : requestId
		});

		$.ajax({
			method: "POST",
			url: url,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			data: payload,
			contentType: "application/json",
			dataType: "json",
			success: function(data, textStatus, jqXHR) {
				const detailRequirement = document.getElementById("detail_requirement");

				detailRequirement.innerHTML = data.body;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('Error');
			}
		});

	}

	function approve(id) {
		method = 'internalInterview';
		$('#existing_id').val(id);
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);
		$('#form')[0].reset();
		$('.modal-title').text(titleAdd);

		$('#modal-add').modal({backdrop: 'static', keyboard: false});
		$('#modal-add').modal('show');
		// var data = JSON.stringify({id : id});

		// var url = "{{ url('dashboard/candidate-requests/fulfill/approve') }}";
		// httpPost(url, data);
	}

	function save() {
		var url;
		var data;

		$('#btn-save').prop('hidden', true);
		$('#btn-loading').prop('hidden', false);

		if(method == 'internalInterview') {
			data = constructData();
			url = "{{ url('dashboard/candidate-requests/fulfill/approve') }}";
			httpPost(url, data);
		} else {
			data = constructData();
			url = "{{ url('dashboard/candidates/destroy') }}";
			httpPost(url, data);
		}	
		
	}

	function constructData(){ 
		var id = $('#existing_id').val();
		var internalInterview = $('#internal_interview').val(); 

		if (internalInterview) {
			internalInterview = internalInterview.replace("T", " ") + ":00";
		} else {
			internalInterview = null;
		}

		var data = JSON.stringify({ 
			id : id, 
			internal_interview: internalInterview
		}); 

		return data; 
	}

	function reject(id) {
		method = 'update';
		var data = JSON.stringify({id : id});

		var url = "{{ url('dashboard/candidate-requests/fulfill/reject') }}";
		httpPost(url, data);
	}

</script>


@include('layouts.footer')