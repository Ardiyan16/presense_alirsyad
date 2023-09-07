<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ auth()->user()->remember_token }}">

    <title>Admin | {{ $title }}</title>
    <link href="{{ url('image/logopas.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('admin/assets/css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ url('admin/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<style>
    tr {
        border-color: 1px solid #028000;
    }

    form .error {
        color: red;
        font-size: 15px;
        width: 100%;
    }

    .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }
</style>

<body>
    <div class="main-wrapper">
        <div class="header">
			<div class="header-left">
				<a href="index.html" class="logo">
					<img src="{{ url('image/logopas.png') }}" width="35" height="35" alt=""> <span>LPP AIJ</span>
				</a>
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="{{ url('admin/assets/img/user.jpg') }}" width="24" alt="Admin">
							<span class="status online"></span>
						</span>
						<span>{{ auth()->user()->username }}</span>
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="{{ url('dashboard/profile-saya') }}">Profile Saya</a>
						<a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
					</div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ url('dashboard/profile-saya') }}">Profile Saya</a>
                    <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
                </div>
            </div>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li>
                            <a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
						<li>
                            <a href="{{ url('/dashboard/data-pegawai') }}"><i class="fa fa-user"></i> <span>Data Pegawai</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/dashboard/data-unit') }}"><i class="fa fa-building"></i> <span>Unit Kerja</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="fa fa-file-circle-exclamation"></i> <span> Cuti</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="{{ url('/dashboard/data-cuti') }}">Cuti Pegawai</a></li>
                                <li><a href="{{ url('/dashboard/data-jenis-cuti') }}">Jenis Cuti</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/dashboard/data-presensi') }}"><i class="fa fa-calendar"></i> <span>Presensi</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            @yield('content')
        </div>

    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="{{ url('admin/assets/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ url('assets/plugins/jquery-cookie/index.min.js') }}"></script>
	<script src="{{ url('admin/assets/js/popper.min.js') }}"></script>
    <script src="{{ url('admin/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('admin/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ url('admin/assets/js/Chart.bundle.js') }}"></script>
    <script src="{{ url('admin/assets/js/chart.js') }}"></script>
    <script src="{{ url('admin/assets/js/app.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{url('assets/js/main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="{{ url('pages/sweetalert2-all.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('js')
</body>

</html>
