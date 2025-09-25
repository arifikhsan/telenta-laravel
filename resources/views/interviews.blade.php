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
		</div>
		<!--end breadcrumb-->


		<h6 class="mb-0 text-uppercase">{{ preg_replace('/(?<!\ )[A-Z]/', ' $0', ucfirst(Request::segment(2))) }}</h6>
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
								<th>Internal Interview</th>
								<th>User Interview</th>
								<th>HR Interview</th>
								<th>SLA</th>
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
						<input type="hidden" name="type" id="type">
						<div class="col-md-12">

							<div class="row mb-2" id="scr"> 
								<div class="col-md-3">
									<label>Score</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input id="score" class="form-control mb-3" type="number" placeholder="0" min="1" max="100">
									</div>

								</div>
							</div>

							<div class="row mb-2" id="dtl"> 
								<div class="col-md-3">
									<label>Detail Recruitment</label>                            
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<textarea id="detail" class="form-control mb-3" placeholder="detail recruitment.. "></textarea>
									</div>
								</div>
							</div>

							<div class="row mb-2" id="rslt"> 
								<div class="col-md-3">
									<label>Result</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<select id="result" name="result" class="form-select mb-3">
											<option value='0' disabled selected>Result</option>
											<option value = "approve">Approve</option>
											<option value = "reject">Reject</option>
										</select>
									</div>
								</div>        
							</div>

							<div class="row mb-2" id="user_it"> 
								<div class="col-md-3">
									<label>User Interview</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input id="user_interview" class="form-control mb-3" type="datetime-local" placeholder="user interview.. " >
									</div>

								</div>
							</div>

							<div class="row mb-2" id="hr_it"> 
								<div class="col-md-3">
									<label>HR Interview</label>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input id="hr_interview" class="form-control mb-3" type="datetime-local" placeholder="hr interview.. " >
									</div>

								</div>
							</div>

							
						</div>


					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button id="btn-save" type="button" class="btn btn-primary" onclick="save()" hidden>Confirm</button>
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

	var titleAdd = "Interview Confirmation";
	var titleEdit = "Update Candidate";

	$(document).ready(function() {

		let url = "{{ url('dashboard/interviews/fetch') }}";

		table = $('#table').DataTable({
			"processing": true, 
			"serverSide": true, 
			"scrollX": true,
			"order": [], 

			"ajax": {
				"url": url,
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

	function confirmation(id, type) {
		method = 'update';
		$('#existing_id').val(id);
		$('#type').val(type);
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);
		$('#form')[0].reset();
		$('.modal-title').text(titleAdd);

		$('#modal-add').modal({backdrop: 'static', keyboard: false});
		$('#modal-add').modal('show');
	}

	function setMeeting(id, type) {
		method = 'update';
		$('#existing_id').val(id);
		$('#type').val(type);
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);
		$('#form')[0].reset();
		$('.modal-title').text("Set HR Interview Date");

		const hrInterviewRow = document.getElementById("hr_it");
		hrInterviewRow.style.display = "flex";

		const score = document.getElementById("scr");
		score.style.display = "none";

		const detail = document.getElementById("dtl");
		detail.style.display = "none";

		const result = document.getElementById("rslt");
		result.style.display = "none";

		$('#score').val("0");
		$('#detail').val("0");
		$('#result option[value=approve]').attr('selected','selected');;

		$('#modal-add').modal({backdrop: 'static', keyboard: false});
		$('#modal-add').modal('show');
	}

	function hrConfirmation(id, type) {
		method = 'update';
		$('#existing_id').val(id);
		$('#type').val(type);
		$('#btn-save').prop('hidden', false);
		$('#btn-loading').prop('hidden', true);
		$('#form')[0].reset();
		$('.modal-title').text(titleAdd);

		const score = document.getElementById("scr");
		score.style.display = "none";

		const detail = document.getElementById("dtl");
		detail.style.display = "none";

		$('#score').val("0");
		$('#detail').val("0");

		$('#modal-add').modal({backdrop: 'static', keyboard: false});
		$('#modal-add').modal('show');
	}


	

	function save() {
		var url;
		var data;

		$('#btn-save').prop('hidden', true);
		$('#btn-loading').prop('hidden', false);

		if(method == 'update') {
			data = constructData();
			url = "{{ url('dashboard/interviews/confirm') }}";
			httpPost(url, data);
		}
		
	}

	function constructData(){ 
		var id = $('#existing_id').val();
		var type = $('#type').val();
		var score = $('#score').val();
		var detail = $('#detail').val();
		var result = $('#result').val();

		var userInterview = $('#user_interview').val(); 
		var hrInterview = $('#hr_interview').val(); 

		if (userInterview) {
			userInterview = userInterview.replace("T", " ") + ":00";
		} else {
			userInterview = null;
		}

		if (hrInterview) {
			hrInterview = hrInterview.replace("T", " ") + ":00";
		} else {
			hrInterview = null;
		}

		var data = JSON.stringify({ 
			id : id,
			type: type,
			score: score,
			notes: detail,
			result: result,
			interview_client: userInterview,
			interview_hr: hrInterview
		}); 

		return data; 
	}

	function detail(id) {
		let baseUrl = "{{ url('dashboard/interviews/detail') }}";
        let fullUrl = baseUrl + "/" + id;
        document.location = fullUrl;
	}

</script>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		
		const resultSelect = document.getElementById("result");
		const userInterviewRow = document.getElementById("user_it");
		const hrInterviewRow = document.getElementById("hr_it");
		userInterviewRow.style.display = "none";
		hrInterviewRow.style.display = "none";
		
		resultSelect.addEventListener("change", function() {
			var type = $('#type').val();
			console.log(type);
			if (this.value === "approve") {
				if (type === "1") {
					userInterviewRow.style.display = "flex"; 
				} else if (type === "2"){
					userInterviewRow.style.display = "none";
				}
			} else {
				if (type === "1") {
					userInterviewRow.style.display = "none";
				// } else if (type === "2"){
				// 	hrInterviewRow.style.display = "none"; 
				}				
			}
		});		
	});
</script>


@include('layouts.footer')