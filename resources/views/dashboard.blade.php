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
            <ul class="navbar-nav">
                <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                    @php
                        $hour = now()->format('H');
                        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    <h1 class="welcome-text">{{ $greeting }}, <span class="text-black fw-bold">{{ auth()->user()->name }}</span></h1>
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
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="menu-icon mdi mdi-account-circle-outline"></i>
                        <span class="menu-title">My Profile</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-panel">
            <div class="content-wrapper">

                @if(auth()->user()->role === 'admin')
                    {{-- Stat cards --}}
                    <div class="row">
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Students</p>
                                            <h2 class="mb-0">{{ $studentsCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-account-school-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('students.index') }}" class="small text-primary">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Teachers</p>
                                            <h2 class="mb-0">{{ $teachersCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-account-tie-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('teachers.index') }}" class="small text-success">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Classes</p>
                                            <h2 class="mb-0">{{ $classesCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-warning rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-google-classroom text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('classes.index') }}" class="small text-warning">View all <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="card-title mb-1">Total Subjects</p>
                                            <h2 class="mb-0">{{ $subjectsCount }}</h2>
                                        </div>
                                        <div class="bg-gradient-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-book-open-page-variant-outline text-white" style="font-size:1.5rem;"></i></div>
                                    </div>
                                    <a href="{{ route('subjects.index') }}" class="small text-danger">View all <i class="mdi mdi-arrow-right"></i></a>
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
                                    <div class="chartjs-bar-wrapper mt-3">
                                        <canvas id="attendanceTrendChart" height="110"></canvas>
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
                                        <canvas id="attendanceSummaryChart"></canvas>
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

                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="card-title mb-1">Total Attendance Records</p>
                                        <h3 class="mb-0">{{ $attendanceCount }}</h3>
                                    </div>
                                    <div class="bg-gradient-info rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-calendar-check-outline text-white" style="font-size:1.5rem;"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="card-title mb-1">Total Marks Records</p>
                                        <h3 class="mb-0">{{ $marksCount }}</h3>
                                    </div>
                                    <div class="bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-clipboard-text-outline text-white" style="font-size:1.5rem;"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="card-title mb-1">Fees Collected</p>
                                        <h3 class="mb-0 text-success">Rs {{ number_format($totalFeesCollected, 0) }}</h3>
                                    </div>
                                    <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-cash-check text-white" style="font-size:1.5rem;"></i></div>
                                </div>
                                <a href="{{ route('fees.index') }}?status=paid" class="small text-success">View all <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="card-title mb-1">Fees Pending</p>
                                        <h3 class="mb-0 text-danger">Rs {{ number_format($totalFeesPending, 0) }}</h3>
                                    </div>
                                    <div class="bg-gradient-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="mdi mdi-cash-remove text-white" style="font-size:1.5rem;"></i></div>
                                </div>
                                <a href="{{ route('fees.index') }}?status=unpaid" class="small text-danger">View all <i class="mdi mdi-arrow-right"></i></a>
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
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
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
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endif
</body>
</html>
