		@include('layouts.header')

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<meta name="csrf-token" content="{{ csrf_token() }}">
				<span id="hidden_fulfilled" hidden></span>
				<div class="row">
					<div class="col-12 col-lg-12 col-xl-12">
						<div class="row row-cols-1 row-cols-lg-2">
							<div class="col">
								<div class="card radius-10 overflow-hidden">
									<div class="card-body">
										<div class="font-35 text-warning"><i class='bx bx-group'></i></div>
										<h3 class="mb-0 mt-0" id="fulfilled_count"></h3>
										<p class="mb-0">Total CV Fulfilled in <span class="month"></span></p>
									</div>
									<div id="fulfilled-chart"></div>
								</div>
							</div>
							<div class="col">
								<div class="card radius-10 overflow-hidden">
									<div class="card-body">
										<div class="font-35 text-primary"><i class='bx bx-list-check'></i></div>
										<h3 class="mb-0 mt-0" id="approved_count"></h3>
										<p class="mb-0 mt-1">Total CV Approved in <span class="month"></span></p>
									</div>
									<div id="approved-chart"></div>
								</div>
							</div>
						</div>

						<div class="row row-cols-1 row-cols-lg-2">
							<div class="col">
								<div class="card radius-10 overflow-hidden">
									<div class="card-body">
										<div class="font-35 text-danger"><i class='bx bx-user-voice'></i></div>
										<h3 class="mb-0 mt-0" id="internal_count"></h3>
										<p class="mb-0 mt-1">Candidates in Internal Interview in <span class="month"></span></p>
									</div>
									<div id="internal-chart"></div>
								</div>
							</div>

							<div class="col">
								<div class="card radius-10 overflow-hidden">
									<div class="card-body">
										<div class="font-35 text-success"><i class='bx bx-buildings'></i></div>
										<h3 class="mb-0 mt-0" id="user_count"></h3>
										<p class="mb-1">Candidates in User Interview in <span class="month"></span></p>
									</div>
									<div id="user-chart"></div>
								</div>
							</div>
						</div>
					</div>
				</div><!--end row-->

				<div class="row">

					<div class="col-12 col-lg-4 col-xl-12 d-flex aproved_rejected">
						<div class="card radius-10 overflow-hidden w-100">
							<div class="card-body">
								<div class="d-flex align-items-center mb-3">
									<h5 class="mb-0">CV Approved and Rejected <span class="year_dashboard"></span></h5>
									<div class="dropdown options ms-auto">
										<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
											
										</div>

									</div>
								</div>
								<div class="chart-js-container2 mt-4">
									<div class="piechart-legend">
										<h2 class="mb-1" id="fulfilled_count_year"></h2>
										<h6 class="mb-0">Total</h6>
									</div>
									<canvas id="comparison"></canvas>
								</div>
							</div>
							<ul class="list-group list-group-flush">
								<li class="list-group-item d-flex justify-content-between align-items-center">
									Approved
									<span class="badge bg-success rounded-pill" id="hidden_approved"></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									Rejected
									<span class="badge bg-danger rounded-pill" id="hidden_rejected"></span>
								</li>
							</ul>
						</div>
					</div>
				</div><!--end row-->

				<div class="card radius-10 w-100">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<h5 class="mb-0">Candidate in <span class="month"></span></h5>
							</div>
							<div class="dropdown options ms-auto">
								<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">

								</div>
							</div>
						</div>
						<div class="chart-js-container3">
							<canvas id="chart3"></canvas>
						</div>
					</div>
				</div>

			</div>
		</div>
		<!--end page wrapper -->

		<style type="text/css">
			.chart-js-container2 {
				position: relative;
				height: 400px;  
				width: 100%;
			}

			.aproved_rejected {
				position: relative;
				height: 700px;  
				width: 100%;
			}
		</style>
		
		<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>

		<script src="{{ asset('assets/plugins/chartjs/js/chart.js') }}"></script>
		<!-- <script src="{{ asset('assets/js/dashboard-digital-marketing.js') }}"></script> -->

		<!-- <script src="{{ asset('assets/js/index2.js') }}"></script> -->
<!-- 
		<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script> -->

		<script type="text/javascript">

			$(document).ready(function() {
				summaryFulfilled();
				summaryApproved();
				summaryInternal();
				summaryUser();
				summaryApplicant();
			});



			function summaryFulfilled() {

				var url = "{{ route('dashboard.summary.fulfilled') }}";

				$.ajax({
					method: "GET",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					success: function(data, textStatus, jqXHR) {
						// console.log(data);

						var now = new Date().getMonth() + 1;
						var record = data.body.find(item => item.month === now);

						var totalThisMonth = record ? record.total : 0;
						// hidden_fulfilled.innerText = totalThisMonth;
						var body = data.body;
						var listTotal = body.map(item => item.total);
						var totalByYear = listTotal.reduce((sum, value) => sum + value, 0);

						fulfilled_count.innerText = totalThisMonth;
						fulfilled_count_year.innerText = totalByYear;

						// console.log(listTotal);


						var options1 = {
							chart: {
								type: 'area', height: 110, sparkline: { enabled: true }
							},
							dataLabels: { enabled: false },
							fill: {
								type: 'gradient',
								gradient: {
									shade: 'light',
									shadeIntensity: 1,
									type: 'vertical',
									opacityFrom: 0.7,
									opacityTo: 0.2,
									stops: [0, 100, 100, 100]
								},
							},
							colors: ["#f7971e"],
							series: [{
								name: 'New Users',
								data: listTotal
							}],
							stroke: {
								width: 2.5, 
								curve: 'smooth',
								dashArray: [0]
							},
							tooltip: {
								theme: 'dark',
								fixed: {
									enabled: false
								},
								x: {
									show: false
								},
								y: {
									formatter: function (val) {
										return val.toFixed(0); 
									},
									title: {
										formatter: function (seriesName) {
											return ''
										}
									}
								},
								marker: {
									show: false
								}
							}
						}

						new ApexCharts(document.querySelector("#fulfilled-chart"), options1).render();

					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error');
					}
				});
			}


			function summaryApproved() {

				var url = "{{ route('dashboard.summary.approved') }}";

				$.ajax({
					method: "GET",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					success: function(data, textStatus, jqXHR) {
						// console.log(data);

						var now = new Date().getMonth() + 1;
						var record = data.body.find(item => item.month === now);

						var totalThisMonth = record ? record.total : 0;
						var body = data.body;
						var listTotal = body.map(item => item.total);
						var totalByYear = listTotal.reduce((sum, value) => sum + value, 0);


						// console.log(listTotal);

						approved_count.innerText = totalThisMonth;
						hidden_approved.innerText = totalByYear;
						chartComparison();

						var approved = {
							chart: {
								type: 'area',
								height: 110,
								sparkline: {
									enabled: true
								}
							},
							dataLabels: {
								enabled: false
							},
							fill: {
								type: 'gradient',
								gradient: {
									shade: 'light',
									shadeIntensity: 1,
									type: 'vertical',
									opacityFrom: 0.7,
									opacityTo: 0.2,
									stops: [0, 100, 100, 100]
								},
							},
							colors: ["#0072ff"],
							series: [{
								data: listTotal
							}],
							stroke: {
								width: 2.5, 
								curve: 'smooth',
								dashArray: [0]
							},
							tooltip: {
								theme: 'dark',
								fixed: {
									enabled: false
								},
								x: {
									show: false
								},
								y: {
									formatter: function (val) {
										return val.toFixed(0); 
									},
									title: {
										formatter: function (seriesName) {
											return ''
										}
									}
								},
								marker: {
									show: false
								}
							}
						}

						new ApexCharts(document.querySelector("#approved-chart"), approved).render();
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error');
					}
				});
			}

			function summaryInternal() {

				var url = "{{ route('dashboard.summary.internal') }}";

				$.ajax({
					method: "GET",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					success: function(data, textStatus, jqXHR) {
						// console.log(data);

						var now = new Date().getMonth() + 1;
						var record = data.body.find(item => item.month === now);

						var totalThisMonth = record ? record.total : 0;
						var body = data.body;
						var listTotal = body.map(item => item.total);

						internal_count.innerText = totalThisMonth;

						var internal = {
							chart: {
								type: 'area',
								height: 110,
								sparkline: {
									enabled: true
								}
							},
							dataLabels: {
								enabled: false
							},
							fill: {
								type: 'gradient',
								gradient: {
									shade: 'light',
									shadeIntensity: 1,
									type: 'vertical',
									opacityFrom: 0.7,
									opacityTo: 0.2,
									stops: [0, 100, 100, 100]
								},
							},
							colors: ["#f1076f"],
							series: [{
								data: listTotal
							}],
							stroke: {
								width: 2.5, 
								curve: 'smooth',
								dashArray: [0]
							},
							tooltip: {
								theme: 'dark',
								fixed: {
									enabled: false
								},
								x: {
									show: false
								},
								y: {
									formatter: function (val) {
										return val.toFixed(0); 
									},
									title: {
										formatter: function (seriesName) {
											return ''
										}
									}
								},
								marker: {
									show: false
								}
							}
						}

						new ApexCharts(document.querySelector("#internal-chart"), internal).render();

					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error');
					}
				});
			}

			function summaryUser() {

				var url = "{{ route('dashboard.summary.user') }}";

				$.ajax({
					method: "GET",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					success: function(data, textStatus, jqXHR) {
						// console.log(data);

						var now = new Date().getMonth() + 1;
						var record = data.body.find(item => item.month === now);

						var totalThisMonth = record ? record.total : 0;
						var body = data.body;
						var listTotal = body.map(item => item.total);

						user_count.innerText = totalThisMonth;

						var user = {
							chart: {
								type: 'area',
								height: 110,
								sparkline: {
									enabled: true
								}
							},
							dataLabels: {
								enabled: false
							},
							fill: {
								type: 'gradient',
								gradient: {
									shade: 'light',
									shadeIntensity: 1,
									opacityFrom: 0.7,
									opacityTo: 0.2,
									stops: [0, 100, 100, 100]
								},
							},
							colors: ["#08a50e"],
							series: [{
								data: listTotal
							}],
							stroke: {
								width: 2.5, 
								curve: 'smooth',
								dashArray: [0]
							},
							tooltip: {
								theme: 'dark',
								fixed: {
									enabled: false
								},
								x: {
									show: false
								},
								y: {
									formatter: function (val) {
										return val.toFixed(0); 
									},
									title: {
										formatter: function (seriesName) {
											return ''
										}
									}
								},
								marker: {
									show: false
								}
							}
						}

						new ApexCharts(document.querySelector("#user-chart"), user).render();

					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error');
					}
				});
			}

			function chartComparison() {
				var ctx2 = document.getElementById('comparison').getContext('2d');
				var total = document.getElementById('fulfilled_count_year').innerText;
				var approved = document.getElementById('hidden_approved').innerText;
				var rejected = total - approved;

				document.getElementById('hidden_rejected').innerText = rejected;

				var gradientApproved = ctx2.createLinearGradient(0, 0, 0, 300);
				gradientApproved.addColorStop(0, '#00b09b');
				gradientApproved.addColorStop(1, '#96c93d');

				var gradientRejected = ctx2.createLinearGradient(0, 0, 0, 300);
				gradientRejected.addColorStop(0, '#ff416c');
				gradientRejected.addColorStop(1, '#ff4b2b');

				var cvChart = new Chart(ctx2, {
					type: 'doughnut', 
					data: {
						labels: ['CV Approved', 'CV Rejected'],
						datasets: [{
							data: [approved, rejected], 
							backgroundColor: [
							gradientApproved,
							gradientRejected
							],
							borderWidth: 1
						}]
					},
					options: {
						maintainAspectRatio: false,
						cutout: 100, 
						plugins: {
							legend: {
								display: true,
								position: 'bottom',
								labels: {
									usePointStyle: true,
									padding: 20
								}
							}
						}
					}
				});
			}
		</script>


		<script type="text/javascript">

			function summaryApplicant() {
				var url = "{{ route('dashboard.summary.applicant') }}";

				$.ajax({
					method: "GET",
					url: url,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
					},
					success: function(data, textStatus, jqXHR) {
						// console.log(data);

						var labels = Object.keys(data.body);
						var dataValues = Object.values(data.body);

						var ctx = document.getElementById('chart3').getContext('2d');

						var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
						gradientStroke1.addColorStop(0, '#5e72e4');  
						gradientStroke1.addColorStop(1, '#5e72e4');


						var myChart = new Chart(ctx, {
							type: 'line',
							data: {
								labels: labels,
								datasets: [{
									label: 'Visitors',
									data: dataValues,
									backgroundColor: [
									gradientStroke1
									],
									fill: {
										target: 'origin',
										above: 'rgb(94 114 228 / 12%)',
										below: 'rgb(94 114 228 / 12%)'
									},
									tension: 0.4,
									borderColor: [
									gradientStroke1
									],
									borderWidth: 4
								}]
							},
							options: {
								maintainAspectRatio: false,
								plugins: {
									legend: { display: false }
								},
								scales: {
									y: {
										beginAtZero: true,
										ticks: {

											callback: function(value) {
												if (Number.isInteger(value)) {
													return value;
												}
											},
											stepSize: 1 
										}
									}
								}
							}
						});
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('Error');
					}
				});
			}

			

		</script>
		@include('layouts.footer')