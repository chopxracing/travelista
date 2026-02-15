<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заселись</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="{{asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback')}}">

    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    @vite(['resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60"
             width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                   aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">{{ $bookings->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @foreach($bookings as $booking)
                        <a href="{{ route('booking.show', $booking->id) }}" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Новое бронирование
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">ID: {{ $booking->id }}</p>
                                    <p class="text-sm text-muted"><i
                                            class="far fa-clock mr-1"></i> {{ $booking->created_at  }}</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.index') }}" class="brand-link">
            <span class="brand-text font-weight-light ml-2"><b>Заселись</b>Soft</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">{{$user?->surname}} {{$user?->name}}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @if($user->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('country.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-globe-europe"></i>
                                <p>
                                    Страны
                                </p>
                            </a>
                        </li>
                    @endif
                    @if($user->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('city.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-city"></i>
                                <p>
                                    Города
                                </p>
                            </a>
                        </li>
                    @endif
                    @if($user->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Пользователи
                                </p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-asdfasd-h"></i>
                            <p>
                                Удобства
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('room_amenity.index') }}" class="nav-link">
                                    <i class="fas fa-bed nav-icon"></i>
                                    <p>Для номеров</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hotel_amenity.index') }}" class="nav-link">
                                    <i class="fas fa-swimming-pool nav-icon"></i>
                                    <p>Для отелей</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('hotel.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-hotel"></i>
                            <p> @if($user->role == 'admin')
                                    Отели
                                @else
                                    Мои отели
                                @endif
                            </p>
                        </a>
                    </li>
                    @if($user->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('tour.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-hotel"></i>
                                <p>
                                    Туры
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('booking.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-bookmark"></i>
                                <p>
                                    Бронирования
                                </p>
                            </a>
                        </li>
                    @endif
                    @if($user->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('tourist.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>
                                    Информация о туристе
                                </p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.2.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
{{--<script>--}}
{{--    $('.tags').select2()--}}
{{--    $('.colors').select2()--}}
{{--</script>--}}
<script>
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
</script>
@yield('scripts')
</body>
</html>
