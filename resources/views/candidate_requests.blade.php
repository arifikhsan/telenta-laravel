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
						<li class="breadcrumb-item active" aria-current="page">Candidate Requests</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
			</div>
		</div>
		<!--end breadcrumb-->


		<h6 class="mb-0 text-uppercase">Candidate Requests</h6>
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
								<th>Position</th>
								<th>Department</th>
								<th>Level</th>
								<th>Salary</th>
								<th>Status</th>
								<th>Requested Count</th>
								<th>Fullfilled Count</th>
								<th>Date Requested</th>
								<th>Category</th>
								<th>Detail</th>
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
									<label>Positions</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="positions" name="positions" class="form-select mb-3"></select>
									</div>
								</div>        
							</div>

							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Level</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="level" name="level" class="form-select mb-3">
											<option value='0' disabled selected>Level Candidate</option>
											<option value = "junior">Junior</option>
											<option value = "middle">Middle</option>
											<option value = "senior">Senior</option>
										</select>
									</div>
								</div>        
							</div>

							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Detail Recruitment</label>                            
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<textarea id="detail" class="form-control mb-3" placeholder="detail recruitment.. "></textarea>
									</div>
								</div>
							</div>

							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Salary</label>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										Minimum <input id="salary_min" class="form-control mb-3" type="text" placeholder="0" min="1">
									</div>

								</div>
								<div class="col-md-4">
									<div class="form-group">
										Maximum <input id="salary_max" class="form-control mb-3" type="text" placeholder="0" min="1">
									</div>

								</div>
							</div>



							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Number of Candidates</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input id="count" class="form-control mb-3" type="number" placeholder="0" min="1" max="20">
									</div>

								</div>
							</div>

							<div class="row mb-2"> 
								<div class="col-md-3">
									<label>Category</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="category" name="category" class="form-select mb-3">
											<option value = "new">New Recruitement</option>
											<option value = "replacement">Replacement</option>
										</select>
									</div>
								</div>        
							</div>

							<div id="replacement_employee" hidden> 
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
	var titleAdd = "Candidate Request";
	var titleEdit = "Update Candidate";

	$(document).ready(function() {

		table = $('#table').DataTable({
			"processing": true, 
			"serverSide": true, 
			"scrollX": true,
			"order": [], 

			"ajax": {
				"url": "{{ url('dashboard/candidate-requests/fetch') }}",
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

	function processAbort(id) {
		method = 'update';
		var data = JSON.stringify({id : id});

		var url = "{{ url('dashboard/candidate-requests/reject') }}";
		httpPost(url, data);
	}

	function detail(id) {
		let baseUrl = "{{ url('dashboard/candidate-requests/fulfill') }}";
        let fullUrl = baseUrl + "/" + id;
        document.location = fullUrl;
	}

	function processAssest(id) {
		method = 'update';
		var data = JSON.stringify({id : id});

		var url = "{{ url('dashboard/candidate-requests/push') }}";
		httpPost(url, data);
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
			data = constructData();
			url = "{{ url('dashboard/candidate-requests/store') }}";
			httpPost(url, data);
		} else if(method == 'update') {
			data = constructData();
			url = "{{ url('dashboard/candidate-requests/update') }}";
			httpPost(url, data);
		} else {
			data = constructData();
			url = "{{ url('dashboard/candidate-requests/destroy') }}";
			httpPost(url, data);
		}	
		
	}

	function constructData() {
		let id = $('#existing_id').val();
		let positions = $('#positions').val(); 
		let level = $('#level').val();
		let detail = $('#detail').val();
		let salary_min = $('#salary_min').val();
		let salary_max = $('#salary_max').val();
		let count = $('#count').val();
		let category = $('#category').val();
		let replacementEmployees = [];

		$('input[name="replacement_names[]"]').each(function () {
			replacementEmployees.push($(this).val());
		});

		let data = {
			id: id,
			position: positions,
			level: level,
			detail: detail,
			salary_min: salary_min,
			salary_max: salary_max,
			count: count,
			category: category,
			replacement_employees: replacementEmployees
		};

		return JSON.stringify(data);
	}


	function organizeData(data, httpCode){
		// used to organize data after click edit and get response from api
		if(httpCode == 200){
			console.log(data);
			$('#btn-save').prop('hidden', false);
			$('#btn-loading').prop('hidden', true);

			$('#existing_id').val(data.body.id);
			$('#name').val(data.body.name);

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

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const categorySelect = document.getElementById("category");
		const countInput = document.getElementById("count");
		const replacementContainer = document.getElementById("replacement_employee");

		categorySelect.addEventListener("change", handleReplacement);
		countInput.addEventListener("input", handleReplacement);

		function handleReplacement() {
			const category = categorySelect.value;
			const count = parseInt(countInput.value) || 0;

        // reset container
        replacementContainer.innerHTML = "";

        if (category === "replacement" && count > 0) {
        	replacementContainer.removeAttribute("hidden");

        	for (let i = 1; i <= count; i++) {
        		replacementContainer.innerHTML += `
        		<div class="row mb-2">
        		<div class="col-md-3">
        		<label>Employee Name ${i}</label>
        		</div>
        		<div class="col-md-6">
        		<div class="form-group">
        		<input name="replacement_names[]" class="form-control mb-3" type="text" placeholder="employee name ${i}..">
        		</div>
        		</div>
        		</div>
        		`;
        	}
        } else {
        	replacementContainer.setAttribute("hidden", "true");
        }
    }
});
</script>

<script>
	$(document).ready(function() {
		$('#positions').select2({
			placeholder: "Select Position",
			allowClear: true,
			ajax: {
				url: "{{ route('dashboard.positions.list') }}",
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
			}
		});
	});
</script>


@include('layouts.footer')