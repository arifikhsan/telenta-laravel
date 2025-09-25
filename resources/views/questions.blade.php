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
					<button type="button" class="btn btn-sm btn-outline-dark px-4 as-create" onclick="add()"><i class="fadeIn animated bx bx-plus-circle mr-1"></i>Add</button>

				</div>

			</div><!-- /.card-header -->
			<div class="card-body">
				<div class="table-responsive">
					<table id="table" class="table table-striped table-bordered" width="100%">
						<thead class="table-secondary">
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>Created At</th>
								<th>Updated At</th>
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
									<label>Questions</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="questions" name="questions" class="form-select mb-3"></select>
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
	var titleAdd = "Add Question";
	var titleEdit = "Update Question";

	$(document).ready(function() {
				table = $('#table').DataTable({
					"processing": true, 
					"serverSide": true, 
					"scrollX": true,
					"order": [], 

					"ajax": {
						"url": "{{ url('dashboard/questions/fetch') }}",
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

		var name = '{{ ucfirst(Request::segment(2)) }}';
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
					console.log(data);
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

	function add() {
		method = 'add';
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);
		$('#form')[0].reset();
		$('.modal-title').text(titleAdd);

		$('#modal-add').modal({backdrop: 'static', keyboard: false});
		$('#modal-add').modal('show');
	}

	function edit(id) {
		method = 'update';
		var data = JSON.stringify({id : id});

		var url = "{{ url('dashboard/questions/show') }}";
		httpGet(url, data);
	}

	function deleteRow(id){
		$('#existing_id').val(id);
		method = 'delete';

		swal({
			title: "Data akan dihapus?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((deleteNow) => {
			if (deleteNow) {
				save();
			} 
		});

	}

	function save() {
		var url;
		var data = constructData();

		$('#btn-save').prop('hidden', true);
		$('#btn-loading').prop('hidden', false);

		if (method == 'add') {
			url = "{{ url('dashboard/questions/store') }}";
		} else if(method == 'update') {
			url = "{{ url('dashboard/questions/update') }}";
		} else {
			url = "{{ url('dashboard/questions/destroy') }}";
		}

		httpPost(url, data);
		
	}

	function constructData(){
		var id = $('#existing_id').val();
		var question = $('#question').val();

		var data = JSON.stringify({
			id : id,
			question : question
		});

		return data;
	}

	function organizeData(data, httpCode){
		// used to organize data after click edit and get response from api
		if(httpCode == 200){
			console.log(data);
			$('#btn-save').prop('hidden', false);
			$('#btn-loading').prop('hidden', true);

			$('#existing_id').val(data.body.id);
			$('#question').val(data.body.question);

			$('#modal-add').modal('show');
			$('#modal-add').modal({backdrop: 'static', keyboard: false});

			$('.modal-title').text(titleEdit);

		} else {
			$('#btn-save').prop('hidden', false);
			$('#btn-loading').prop('hidden', true);
			
			$('#btn-save-upload').prop('hidden', false);
			$('#btn-loading-upload').prop('hidden', true);
			swal("Failed!", data.message, "error");

		}
	}


</script>


@include('layouts.footer')