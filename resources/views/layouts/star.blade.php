<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'School Management System')</title>

    <link rel="stylesheet" href="{{ asset('star/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('star/assets/images/favicon.png') }}">
    @stack('styles')
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
            <ul class="navbar-nav">
                <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text">@yield('page_title', 'Dashboard')</h1>
                    <h3 class="welcome-sub-text">{{ ucfirst(auth()->user()->role) }} &middot; {{ now()->format('l, d M Y') }}</h3>
                </li>
            </ul>
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
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">

        {{-- Sidebar --}}
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="nav-item nav-category">Management</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="menu-icon mdi mdi-account-school-outline"></i>
                            <span class="menu-title">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                            <i class="menu-icon mdi mdi-account-tie-outline"></i>
                            <span class="menu-title">Teachers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                            <i class="menu-icon mdi mdi-google-classroom"></i>
                            <span class="menu-title">Classes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                            <i class="menu-icon mdi mdi-book-open-page-variant-outline"></i>
                            <span class="menu-title">Subjects</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fees.*') ? 'active' : '' }}" href="{{ route('fees.index') }}">
                            <i class="menu-icon mdi mdi-cash-multiple"></i>
                            <span class="menu-title">Fees</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Records</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activity.logs') ? 'active' : '' }}" href="{{ route('activity.logs') }}">
                            <i class="menu-icon mdi mdi-history"></i>
                            <span class="menu-title">Activity Logs</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <li class="nav-item nav-category">Teaching</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                            <i class="menu-icon mdi mdi-calendar-check-outline"></i>
                            <span class="menu-title">Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('marks.*') ? 'active' : '' }}" href="{{ route('marks.index') }}">
                            <i class="menu-icon mdi mdi-clipboard-text-outline"></i>
                            <span class="menu-title">Marks</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'student')
                    <li class="nav-item nav-category">My Records</li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.result') ? 'active' : '' }}" href="{{ route('student.result') }}">
                            <i class="menu-icon mdi mdi-file-chart-outline"></i>
                            <span class="menu-title">My Result</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fees.*') ? 'active' : '' }}" href="{{ route('fees.index') }}">
                            <i class="menu-icon mdi mdi-cash-multiple"></i>
                            <span class="menu-title">My Fees</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item nav-category">Account</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="menu-icon mdi mdi-account-circle-outline"></i>
                        <span class="menu-title">My Profile</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')

            </div>

            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">School Management System</span>
                    <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">&copy; {{ date('Y') }}</span>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="{{ asset('star/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('star/assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('star/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('star/assets/js/template.js') }}"></script>
<script src="{{ asset('star/assets/js/hoverable-collapse.js') }}"></script>
@stack('scripts')
</body>
</html>
