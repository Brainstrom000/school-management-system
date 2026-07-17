<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $header ?? 'School Management System' }}</title>

    <link rel="stylesheet" href="{{ asset('star/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('star/assets/images/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="with-welcome-text">
<div class="container-scroller">

    {{-- Navbar --}}
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                    <span class="fw-bold text-primary fs-4">School MS</span>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                    <span class="fw-bold text-primary">SMS</span>
                </a>
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">
                            <p class="mb-1 mt-3 fw-semibold">{{ auth()->user()->name }}</p>
                            <p class="fw-light text-muted mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">

        {{-- Sidebar --}}
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}"><i class="menu-icon mdi mdi-account-school-outline"></i><span class="menu-title">Students</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('teachers.index') }}"><i class="menu-icon mdi mdi-account-tie-outline"></i><span class="menu-title">Teachers</span></a></li>
                @endif

                @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('attendances.index') }}"><i class="menu-icon mdi mdi-calendar-check-outline"></i><span class="menu-title">Attendance</span></a></li>
                @endif

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profile.edit') }}">
                        <i class="menu-icon mdi mdi-account-circle-outline"></i>
                        <span class="menu-title">My Profile</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">

                @if (isset($header))
                    <div class="page-header">
                        <h3 class="page-title">{{ $header }}</h3>
                    </div>
                @endif

                {{ $slot }}

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('star/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('star/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('star/assets/js/template.js') }}"></script>
<script src="{{ asset('star/assets/js/settings.js') }}"></script>
<script src="{{ asset('star/assets/js/hoverable-collapse.js') }}"></script>
</body>
</html>