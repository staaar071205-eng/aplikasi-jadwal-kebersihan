<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

	<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
	<link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

	<div class="d-flex" id="wrapper">
		<div class="bg-primary border-right" id="sidebar-wrapper">
			<div class="sidebar-heading text-white">
				JADWAL KEBERSIHAN
				<small>Tuntungan 2</small>
			</div>

			<div class="list-group list-group-flush">
				<a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
					<i class="bi bi-speedometer2"></i>Dashboard
				</a>
				<a href="{{ route('jadwal.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
					<i class="bi bi-calendar-check"></i>Jadwal Kebersihan
				</a>
				<a href="{{ route('wilayah.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('wilayah.*') ? 'active' : '' }}">
					<i class="bi bi-geo-alt"></i>Wilayah
				</a>
				<a href="{{ route('kegiatan.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
					<i class="bi bi-tags"></i>Jenis Kegiatan
				</a>
				<a href="{{ route('petugas.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('petugas.*') ? 'active' : '' }}">
					<i class="bi bi-people"></i>Petugas
				</a>
			</div>
		</div>

		<div id="page-content-wrapper">
			<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
				<div class="container-fluid">
					<button class="btn btn-primary" id="menu-toggle" type="button" aria-label="Menu">
						<i class="bi bi-list"></i>
					</button>

					<div class="d-flex align-items-center gap-3 ms-auto">
						<span class="text-muted d-none d-sm-inline">
							<i class="bi bi-person-circle"></i> {{ auth()->user()->user_nama }}
						</span>
						<form action="{{ route('logout') }}" method="post" class="m-0">
							@csrf
							<button type="submit" class="btn btn-outline-danger btn-sm">
								<i class="bi bi-box-arrow-right"></i> Logout
							</button>
						</form>
					</div>
				</div>
			</nav>

			<div class="container-fluid p-4">

				@include('layouts._alert')

				@yield('content')

			</div>
		</div>
	</div>

	<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
