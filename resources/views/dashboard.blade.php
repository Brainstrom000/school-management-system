<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard - School Management System</title>

    <link rel="stylesheet" href="{{ asset('star/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('star/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-theme.css') }}">
    <link rel="shortcut icon" href="{{ asset('star/assets/images/favicon.png') }}">
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
            <ul class="navbar-nav"></ul>
            <ul class="navbar-nav ms-auto">
                @php
                    $navNotices = \App\Models\Notice::visibleTo(auth()->user())->latest()->take(5)->get();
                @endphp
                <li class="nav-item dropdown d-none d-lg-block notice-bell-dropdown">
                    <a class="nav-link" id="NoticeBellDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-bell-outline" style="font-size:1.4rem;"></i>
                        @if($navNotices->count())
                            <span class="notice-bell-badge">{{ $navNotices->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown notice-bell-menu" aria-labelledby="NoticeBellDropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <span class="fw-semibold">Notices</span>
                            <a href="{{ route('notices.index') }}" class="small">View all</a>
                        </div>
                        @forelse($navNotices as $notice)
                            <a class="dropdown-item notice-bell-item" href="{{ route('notices.index') }}">
                                <p class="mb-0 fw-semibold">{{ $notice->title }}</p>
                                <p class="mb-0 small text-muted">{{ \Illuminate\Support\Str::limit($notice->message, 60) }}</p>
                                <p class="mb-0 small text-muted">{{ $notice->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <span class="dropdown-item text-muted">No notices yet.</span>
                        @endforelse
                    </div>
                </li>
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
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="nav-item nav-category">Management</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('students.index') }}">
                            <i class="menu-icon mdi mdi-account-school-outline"></i>
                            <span class="menu-title">Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teachers.index') }}">
                            <i class="menu-icon mdi mdi-account-tie-outline"></i>
                            <span class="menu-title">Teachers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classes.index') }}">
                            <i class="menu-icon mdi mdi-google-classroom"></i>
                            <span class="menu-title">Classes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subjects.index') }}">
                            <i class="menu-icon mdi mdi-book-open-page-variant-outline"></i>
                            <span class="menu-title">Subjects</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Records</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('activity.logs') }}">
                            <i class="menu-icon mdi mdi-history"></i>
                            <span class="menu-title">Activity Logs</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <li class="nav-item nav-category">Teaching</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('attendances.index') }}">
                            <i class="menu-icon mdi mdi-calendar-check-outline"></i>
                            <span class="menu-title">Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('marks.index') }}">
                            <i class="menu-icon mdi mdi-clipboard-text-outline"></i>
                            <span class="menu-title">Marks</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item nav-category">Fees</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fees.index') }}">
                        <i class="menu-icon mdi mdi-cash-multiple"></i>
                        <span class="menu-title">Fees</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'student')
                    <li class="nav-item nav-category">My Records</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.result') }}">
                            <i class="menu-icon mdi mdi-file-chart-outline"></i>
                            <span class="menu-title">My Result</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item nav-category">Account</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notices.index') }}">
                        <i class="menu-icon mdi mdi-bullhorn-outline"></i>
                        <span class="menu-title">Notices</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="menu-icon mdi mdi-account-circle-outline"></i>
                        <span class="menu-title">My Profile</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">

                {{-- Recent Notices — visible to every role --}}
                @php
                    $dashboardNotices = \App\Models\Notice::visibleTo(auth()->user())->latest()->take(3)->get();
                @endphp
                @if($dashboardNotices->count())
                    <div class="card card-rounded grid-margin notice-widget">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h4 class="card-title card-title-dash mb-0"><i class="mdi mdi-bullhorn-outline"></i> Recent Notices</h4>
                                <a href="{{ route('notices.index') }}" class="small text-primary">View all <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                            @foreach($dashboardNotices as $notice)
                                <div class="notice-widget-item">
                                    <p class="mb-0 fw-semibold">{{ $notice->title }}</p>
                                    <p class="mb-0 small text-muted">{{ \Illuminate\Support\Str::limit($notice->message, 100) }} &middot; {{ $notice->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(auth()->user()->role === 'admin')
                    {{-- Welcome banner --}}
                    @php
                        $hour = now()->format('H');
                        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    <div class="welcome-banner grid-margin">
                        <div class="welcome-banner-text">
                            <h2>{{ $greeting }}, {{ auth()->user()->name }} <span class="wave">👋</span></h2>
                            <p>Here's what's happening in your school today — {{ now()->format('l, d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Stat cards --}}
                    <div class="row">
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Students</p>
                                            <h2 class="mb-0">{{ $studentsCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;"><i class="mdi mdi-account-school-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('students.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Teachers</p>
                                            <h2 class="mb-0">{{ $teachersCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;"><i class="mdi mdi-account-tie-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('teachers.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Classes</p>
                                            <h2 class="mb-0">{{ $classesCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;"><i class="mdi mdi-google-classroom text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('classes.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Subjects</p>
                                            <h2 class="mb-0">{{ $subjectsCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px;height:50px;"><i class="mdi mdi-book-open-page-variant-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('subjects.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Summary --}}
                    <div class="row">
                        <div class="col-lg-7 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h4 class="card-title card-title-dash">Attendance Trend (Last 7 Days)</h4>
                                    <p class="card-subtitle card-subtitle-dash">Present / Absent / Leave per day</p>
                                    <div class="chart-container" style="position:relative; height:220px;">
                                        <canvas id="attendanceTrendChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h4 class="card-title card-title-dash">Attendance Summary</h4>
                                    <p class="card-subtitle card-subtitle-dash">Overall breakdown ({{ $attendanceCount }} records)</p>
                                    @if($attendanceCount > 0)
                                        <div class="chart-container" style="position:relative; height:180px;">
                                            <canvas id="attendanceSummaryChart"></canvas>
                                        </div>
                                        <div class="mt-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><i class="mdi mdi-circle text-success"></i> Present</span>
                                                <strong>{{ $presentCount }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><i class="mdi mdi-circle text-danger"></i> Absent</span>
                                                <strong>{{ $absentCount }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span><i class="mdi mdi-circle text-warning"></i> Leave</span>
                                                <strong>{{ $leaveCount }}</strong>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No attendance records yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fee Collection --}}
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <h4 class="card-title card-title-dash mb-0">Fee Collection (Last 12 Months)</h4>
                                            <p class="card-subtitle card-subtitle-dash mb-0">Total collected: Rs. {{ number_format($totalFeesCollected, 2) }}</p>
                                        </div>
                                        <span class="text-muted small">Updated {{ now()->format('d M, h:i A') }}</span>
                                    </div>
                                    <div class="chart-container" style="position:relative; height:180px;">
                                        <canvas id="feeCollectionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded stat-card stat-card-blue">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Attendance Records</p>
                                            <h3 class="mb-0">{{ $attendanceCount }}</h3>
                                        </div>
                                        <div class="stat-icon stat-icon-blue"><i class="mdi mdi-calendar-check-outline"></i></div>
                                    </div>
                                    <a href="{{ route('attendances.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded stat-card stat-card-indigo">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Marks Records</p>
                                            <h3 class="mb-0">{{ $marksCount }}</h3>
                                        </div>
                                        <div class="stat-icon stat-icon-indigo"><i class="mdi mdi-clipboard-text-outline"></i></div>
                                    </div>
                                    <a href="{{ route('marks.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded stat-card stat-card-navy">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Fee Summary</p>
                                            <h3 class="mb-0">Rs {{ number_format($totalFeesCollected, 0) }}</h3>
                                            <p class="small text-muted mb-0">Pending: Rs {{ number_format($totalFeesPending, 0) }}</p>
                                        </div>
                                        <div class="stat-icon stat-icon-navy"><i class="mdi mdi-cash-multiple"></i></div>
                                    </div>
                                    <a href="{{ route('fees.index') }}" class="small text-primary mt-auto pt-3">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(auth()->user()->role === 'student')

                    @if(!$myStudent)
                        <div class="alert alert-warning">Your student profile could not be found. Please contact the administration.</div>
                    @else
                        <div class="row">
                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <p class="card-title mb-1">Overall Result</p>
                                        <h2 class="mb-0 text-primary">{{ $myOverallPercentage }}%</h2>
                                        <a href="{{ route('student.result') }}" class="small text-primary">View full result <i class="mdi mdi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <p class="card-title mb-1">Attendance (Present)</p>
                                        <h2 class="mb-0 text-success">{{ $myAttendanceSummary['present'] }}</h2>
                                        <span class="small text-muted">Absent: {{ $myAttendanceSummary['absent'] }} &middot; Leave: {{ $myAttendanceSummary['leave'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <p class="card-title mb-1">Pending Fees</p>
                                        <h2 class="mb-0 text-danger">{{ $myFees->where('status', 'unpaid')->count() }}</h2>
                                        <a href="{{ route('fees.index') }}" class="small text-danger">View / Pay <i class="mdi mdi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <h4 class="card-title card-title-dash">Recent Attendance</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead><tr><th>Date</th><th>Status</th></tr></thead>
                                                <tbody>
                                                    @forelse($myRecentAttendance as $a)
                                                        <tr>
                                                            <td>{{ $a->date->format('d M Y') }}</td>
                                                            <td>
                                                                @if($a->status === 'Present')
                                                                    <span class="badge bg-success">Present</span>
                                                                @elseif($a->status === 'Absent')
                                                                    <span class="badge bg-danger">Absent</span>
                                                                @else
                                                                    <span class="badge bg-warning">Leave</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted">No attendance records yet</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <h4 class="card-title card-title-dash">Recent Marks</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead><tr><th>Subject</th><th>Marks</th><th>Grade</th></tr></thead>
                                                <tbody>
                                                    @forelse($myRecentMarks as $m)
                                                        <tr>
                                                            <td>{{ $m->subject->name ?? 'N/A' }}</td>
                                                            <td>{{ $m->marks }}/{{ $m->total_marks }}</td>
                                                            <td>{{ $m->grade }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="3" class="text-center text-muted">No marks recorded yet</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="{{ route('student.result') }}" class="small">View full result <i class="mdi mdi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <h4 class="card-title card-title-dash">My Fees</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead><tr><th>Title</th><th>Amount</th><th>Due Date</th><th>Status</th><th>Action</th></tr></thead>
                                                <tbody>
                                                    @forelse($myFees as $fee)
                                                        <tr>
                                                            <td>{{ $fee->title }}</td>
                                                            <td>Rs {{ number_format($fee->amount, 0) }}</td>
                                                            <td>{{ $fee->due_date->format('d M Y') }}</td>
                                                            <td>
                                                                @if($fee->status === 'paid')
                                                                    <span class="badge bg-success">Paid</span>
                                                                @else
                                                                    <span class="badge bg-danger">Unpaid</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($fee->status === 'unpaid')
                                                                    <a href="{{ route('fees.pay', $fee->id) }}" class="btn btn-success btn-sm">Pay Now</a>
                                                                @else
                                                                    <a href="{{ route('fees.show', $fee->id) }}" class="btn btn-info btn-sm">View</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="5" class="text-center text-muted">No fee records yet</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="{{ route('fees.index') }}" class="small">View all fees <i class="mdi mdi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @elseif(auth()->user()->role === 'teacher')

                    <div class="row">
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <p class="card-title mb-1">Marked Today</p>
                                    <h2 class="mb-0 text-info">{{ $todayAttendanceCount }}</h2>
                                    <span class="small text-muted">Attendance records for {{ now()->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <p class="card-title mb-1">Mark Attendance</p>
                                    <a href="{{ route('attendances.create') }}" class="btn btn-primary mt-2">
                                        <i class="mdi mdi-calendar-check-outline"></i> Mark Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <p class="card-title mb-1">Add Marks</p>
                                    <a href="{{ route('marks.create') }}" class="btn btn-primary mt-2">
                                        <i class="mdi mdi-clipboard-text-outline"></i> Add Marks
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h4 class="card-title card-title-dash">Recently Marked Attendance</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead><tr><th>Student</th><th>Date</th><th>Status</th></tr></thead>
                                            <tbody>
                                                @forelse($recentAttendanceMarked as $a)
                                                    <tr>
                                                        <td>{{ $a->student->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $a->date->format('d M Y') }}</td>
                                                        <td>
                                                            @if($a->status === 'Present')
                                                                <span class="badge bg-success">Present</span>
                                                            @elseif($a->status === 'Absent')
                                                                <span class="badge bg-danger">Absent</span>
                                                            @else
                                                                <span class="badge bg-warning">Leave</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="text-center text-muted">No attendance marked yet</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{ route('attendances.index') }}" class="small">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h4 class="card-title card-title-dash">Recently Added Marks</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead><tr><th>Student</th><th>Subject</th><th>Marks</th></tr></thead>
                                            <tbody>
                                                @forelse($recentMarksAdded as $m)
                                                    <tr>
                                                        <td>{{ $m->student->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $m->subject->name ?? 'N/A' }}</td>
                                                        <td>{{ $m->marks }}/{{ $m->total_marks }} ({{ $m->grade }})</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="3" class="text-center text-muted">No marks added yet</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{ route('marks.index') }}" class="small">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h4 class="card-title">Welcome, {{ auth()->user()->name }}</h4>
                                    <p class="text-muted mb-0">Use the sidebar to access your tools.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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

@if(auth()->user()->role === 'admin')
<script>
    // Attendance Summary donut chart
    @if($attendanceCount > 0)
    new Chart(document.getElementById('attendanceSummaryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Leave'],
            datasets: [{
                data: [{{ $presentCount }}, {{ $absentCount }}, {{ $leaveCount }}],
                backgroundColor: ['#4caf50', '#f44336', '#ff9800']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
    @endif

    // Attendance Trend chart (last 7 days)
    new Chart(document.getElementById('attendanceTrendChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($attendanceTrend->pluck('label')) !!},
            datasets: [
                { label: 'Present', data: {!! json_encode($attendanceTrend->pluck('present')) !!}, backgroundColor: '#4caf50' },
                { label: 'Absent', data: {!! json_encode($attendanceTrend->pluck('absent')) !!}, backgroundColor: '#f44336' },
                { label: 'Leave', data: {!! json_encode($attendanceTrend->pluck('leave')) !!}, backgroundColor: '#ff9800' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Fee Collection (last 12 months) — blue gradient bars
    (function () {
        var ctx = document.getElementById('feeCollectionChart').getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, 180);
        gradient.addColorStop(0, '#6366f1');
        gradient.addColorStop(1, '#4f46e5');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyFees->pluck('label')) !!},
                datasets: [{
                    label: 'Fees Collected (Rs.)',
                    data: {!! json_encode($monthlyFees->pluck('total')) !!},
                    backgroundColor: gradient,
                    borderRadius: 6,
                    maxBarThickness: 42
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#eef2ff' } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (item) { return 'Rs. ' + item.parsed.y.toLocaleString(); }
                        }
                    }
                }
            }
        });
    })();
</script>
@endif
</body>
</html>
