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
						<li class="breadcrumb-item active" aria-current="page">Question for {{ $position_name }}</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
			</div>
		</div>
		<!--end breadcrumb-->


		<h6 class="mb-0 text-uppercase">Question for {{ $position_name }}</h6>
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
								<th>Question</th>
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
	<div class="modal-dialog modal-xl">
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

							<div class="row"> 
								<div class="col-md-1">                           
								</div>
								<div class="col-md-2">
									<label>Question</label>                            
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<select id="questions" name="questions[]" class="form-control form-control-sm" multiple="multiple" placeholder="Questions.. "></select>
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
	var titleAdd = "Add Questions to Position {{ $position_name }}";
	var titleEdit = "Update Question";

	var requestId = "{{ Request::segment(4) }}";
	let baseUrl = "{{ url('dashboard/positions/questions/fetch') }}";
	let fullUrl = baseUrl + "/" + requestId;

	$(document).ready(function() {
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
		var data;

		$('#btn-save').prop('hidden', true);
		$('#btn-loading').prop('hidden', false);

		if (method == 'add') {
			var requestId = "{{ Request::segment(4) }}";
			data = constructData(requestId);
			url = "{{ url('dashboard/positions/questions/store') }}";
		} else {
			var requestId = $('#existing_id').val();
			data = constructData(requestId);
			url = "{{ url('dashboard/positions/questions/destroy') }}";
			
		}
		httpPost(url, data);
		
	}

	
	function constructData(id) {
		
		let questions = $('#questions').val(); 

		var data = {
			id: id,
			questions: questions || []
		};

		return JSON.stringify(data);
	}


	function constructDataForm(){
		var formData = new FormData();

		var id = $('#existing_id').val();
		var name = $('#name').val();


		formData.append('id', id);
		formData.append('name', name);

		let positions = $('#positions').val(); // array id posisi
		if (positions) {
			positions.forEach(function (posId) {
				formData.append('positions[]', posId);
			});
		}


		var file = $('#file')[0].files[0];
		if (file) {
			formData.append('file', file); 
		}
		return formData;
	}

	


</script>
<script type="text/javascript">
	$(document).ready(function() {

		var requestId = "{{ Request::segment(4) }}";
		let baseUrl = "{{ url('dashboard/questions/list') }}";
		let fullUrl = baseUrl + "/" + requestId;

		$('#questions').select2({
			placeholder: "Select Questions",
			allowClear: true,
			ajax: {
				url: fullUrl,
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								id: item.id,
								text: item.question
							};
						})
					};
				},
				cache: true
			}
		});
	});
</script>


@include('layouts.footer')