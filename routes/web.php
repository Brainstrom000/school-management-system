<?php

use Illuminate\Support\Facades\Route;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Mark;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    // ---- Global stats (same for every admin/teacher/student, changes
    // rarely from second to second) — cached for 60s so 50k+ rows worth
    // of counts/sums aren't recomputed on every single page load.
    $globalStats = \Illuminate\Support\Facades\Cache::remember('dashboard_global_stats', 60, function () {

        // One grouped query instead of 21 separate COUNT queries.
        $trendRows = Attendance::selectRaw('date, status, COUNT(*) as total')
            ->where('date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('date', 'status')
            ->get()
            ->groupBy('date');

        $attendanceTrend = collect(range(6, 0))->map(function ($daysAgo) use ($trendRows) {
            $date = now()->subDays($daysAgo)->toDateString();
            $rows = $trendRows->get($date, collect());

            return [
                'label'   => now()->subDays($daysAgo)->format('D'),
                'present' => (int) optional($rows->firstWhere('status', 'Present'))->total,
                'absent'  => (int) optional($rows->firstWhere('status', 'Absent'))->total,
                'leave'   => (int) optional($rows->firstWhere('status', 'Leave'))->total,
            ];
        });

        // Fee collection for last 12 months (single grouped query) —
        // used by the "Fee Collection (Last 12 Months)" chart.
        $feeRows = \App\Models\Fee::selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(amount) as total")
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $monthlyFees = collect(range(11, 0))->map(function ($monthsAgo) use ($feeRows) {
            $month = now()->subMonths($monthsAgo);

            return [
                'label' => $month->format('M'),
                'total' => (float) ($feeRows[$month->format('Y-m')] ?? 0),
            ];
        });

        return [
            'studentsCount'      => Student::count(),
            'teachersCount'      => Teacher::count(),
            'classesCount'       => SchoolClass::count(),
            'subjectsCount'      => Subject::count(),
            'attendanceCount'    => Attendance::count(),
            'marksCount'         => Mark::count(),
            'presentCount'       => Attendance::where('status', 'Present')->count(),
            'absentCount'        => Attendance::where('status', 'Absent')->count(),
            'leaveCount'         => Attendance::where('status', 'Leave')->count(),
            'totalFeesCollected' => \App\Models\Fee::where('status', 'paid')->sum('amount'),
            'totalFeesPending'   => \App\Models\Fee::where('status', 'unpaid')->sum('amount'),
            'monthlyFees'        => $monthlyFees,
            'attendanceTrend'    => $attendanceTrend,
        ];
    });

    extract($globalStats);

    // ---- Student-specific dashboard data ----
    $myStudent = null;
    $myAttendanceSummary = null;
    $myOverallPercentage = null;
    $myRecentAttendance = null;
    $myRecentMarks = null;
    $myFees = null;

    if (auth()->user()->role === 'student') {
        $myStudent = auth()->user()->student;

        if ($myStudent) {
            $myAttendanceSummary = [
                'present' => $myStudent->attendances()->where('status', 'Present')->count(),
                'absent'  => $myStudent->attendances()->where('status', 'Absent')->count(),
                'leave'   => $myStudent->attendances()->where('status', 'Leave')->count(),
            ];

            $marks = $myStudent->marks;
            $totalObtained = $marks->sum('marks');
            $totalMax = $marks->sum('total_marks');
            $myOverallPercentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

            $myRecentAttendance = $myStudent->attendances()->latest('date')->take(5)->get();
            $myRecentMarks = $myStudent->marks()->with('subject')->latest()->take(5)->get();
            $myFees = $myStudent->fees()->latest()->take(5)->get();
        }
    }

    // ---- Teacher-specific dashboard data ----
    $todayAttendanceCount = null;
    $recentMarksAdded = null;
    $recentAttendanceMarked = null;

    if (auth()->user()->role === 'teacher') {
        $todayAttendanceCount = Attendance::whereDate('date', now()->toDateString())->count();
        $recentMarksAdded = Mark::with(['student.user', 'subject'])->latest()->take(5)->get();
        $recentAttendanceMarked = Attendance::with('student.user')->latest('date')->take(5)->get();
    }

    return view('dashboard', compact(
        'studentsCount',
        'teachersCount',
        'classesCount',
        'subjectsCount',
        'attendanceCount',
        'marksCount',
        'presentCount',
        'absentCount',
        'leaveCount',
        'attendanceTrend',
        'totalFeesCollected',
        'totalFeesPending',
        'monthlyFees',
        'myStudent',
        'myAttendanceSummary',
        'myOverallPercentage',
        'myRecentAttendance',
        'myRecentMarks',
        'myFees',
        'todayAttendanceCount',
        'recentMarksAdded',
        'recentAttendanceMarked'
    ));

})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Notices — everyone (logged in) can view; only admin can post/delete.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('notices', [NoticeController::class, 'index'])->name('notices.index');

    Route::middleware(['role:admin'])->group(function () {
        Route::post('notices', [NoticeController::class, 'store'])->name('notices.store');
        Route::delete('notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    });
});



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::get('students-data', [StudentController::class, 'datatable'])
        ->name('students.datatable');

    Route::resource('students', StudentController::class);

    Route::get('students-trash', [StudentController::class, 'trash'])
        ->name('students.trash');

    Route::put('students/{id}/restore', [StudentController::class, 'restore'])
        ->name('students.restore');

    Route::delete('students/{id}/force-delete', [StudentController::class, 'forceDelete'])
        ->name('students.forceDelete');


    /*
    |--------------------------------------------------------------------------
    | Teachers
    |--------------------------------------------------------------------------
    */

    Route::get('teachers-data', [TeacherController::class, 'datatable'])
        ->name('teachers.datatable');

    Route::resource('teachers', TeacherController::class);

    Route::get('teachers-trash', [TeacherController::class, 'trash'])
        ->name('teachers.trash');

    Route::put('teachers/{id}/restore', [TeacherController::class, 'restore'])
        ->name('teachers.restore');

    Route::delete('teachers/{id}/force-delete', [TeacherController::class, 'forceDelete'])
        ->name('teachers.forceDelete');


    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    Route::get('classes-data', [SchoolClassController::class, 'datatable'])
        ->name('classes.datatable');

    Route::resource('classes', SchoolClassController::class);


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */

    Route::get('subjects-data', [SubjectController::class, 'datatable'])
        ->name('subjects.datatable');

    Route::resource('subjects', SubjectController::class);


    /*
    |--------------------------------------------------------------------------
    | Activity Logs
    |--------------------------------------------------------------------------
    */

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity.logs');

    Route::get('/activity-logs-data', [ActivityLogController::class, 'datatable'])
        ->name('activity.logs.datatable');


    /*
    |--------------------------------------------------------------------------
    | Fees (Admin management)
    |--------------------------------------------------------------------------
    */

    Route::get('fees/create', [FeeController::class, 'create'])->name('fees.create');
    Route::post('fees', [FeeController::class, 'store'])->name('fees.store');
    Route::get('fees/{fee}/edit', [FeeController::class, 'edit'])->name('fees.edit');
    Route::put('fees/{fee}', [FeeController::class, 'update'])->name('fees.update');
    Route::delete('fees/{fee}', [FeeController::class, 'destroy'])->name('fees.destroy');

});


/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teacher'])->group(function () {

    Route::get('attendances-data', [AttendanceController::class, 'datatable'])
        ->name('attendances.datatable');

    Route::resource('attendances', AttendanceController::class);

    Route::get('marks-data', [MarkController::class, 'datatable'])
        ->name('marks.datatable');

    Route::resource('marks', MarkController::class);

});


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->group(function () {

    Route::get('/my-result', [StudentResultController::class, 'myResult'])
        ->name('student.result');

});


/*
|--------------------------------------------------------------------------
| Fees (Shared: Admin can view all, Student can view/pay their own)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('fees', [FeeController::class, 'index'])->name('fees.index');
    Route::get('fees-data', [FeeController::class, 'datatable'])->name('fees.datatable');
    Route::get('fees/{fee}', [FeeController::class, 'show'])->name('fees.show');
    Route::get('fees/{fee}/pay', [FeeController::class, 'pay'])->name('fees.pay');

    // PayPal
    Route::get('fees/{fee}/pay/paypal', [PaypalController::class, 'checkout'])->name('paypal.checkout');
    Route::get('paypal/{fee}/success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('paypal/{fee}/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

    // Stripe
    Route::get('fees/{fee}/pay/stripe', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('stripe/{fee}/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('stripe/{fee}/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

});


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';