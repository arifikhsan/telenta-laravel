<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
		</div>
		<div>
			<a href="{{ url('dashboard') }}">
				<img src="{{ asset('telenta.png') }}" alt="logo" height="90px" width="140px">
				<!-- <h4 class="logo-text" id="nav-title">Telenta</h4> -->
			</a>
		</div>
		<div class="toggle-icon ms-auto"><i class='fadeIn animated bx bx-menu'></i>
		</div>
	</div>
	<!--navigation-->
	<ul class="metismenu" id="menu">

		@foreach($menu as $m)  
		<li>
			<a href="{{ $m->url }}">
				<div class="parent-icon"><i class="fadeIn animated {{ $m->icon }}"></i>
				</div>
				<div class="menu-title">{{ $m->name }}</div>
			</a>
		</li>

		@endforeach

	</ul>
	<!--end navigation-->
</div>
<!--end sidebar wrapper -->


