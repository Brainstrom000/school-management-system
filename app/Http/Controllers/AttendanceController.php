<?php

namespace App\Http\Controllers;

use App\Mail\AttendanceMail;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('student.user')
            ->latest()
            ->paginate(10);

        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();

        return view('attendances.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
        ]);

        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date'       => $request->date,
            'status'     => $request->status,
        ]);

        $this->notifyStudent($attendance);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $students = Student::with('user')->get();

        return view(
            'attendances.edit',
            compact('attendance', 'students')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
        ]);

        $attendance->update([
            'student_id' => $request->student_id,
            'date'       => $request->date,
            'status'     => $request->status,
        ]);

        $this->notifyStudent($attendance);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Updated Successfully');
    }

    /**
     * Email the student to let them know their attendance was marked.
     * Failure to send should never block the attendance flow.
     */
    protected function notifyStudent(Attendance $attendance): void
    {
        try {
            $attendance->loadMissing('student.user');

            $email = $attendance->student->user->email ?? $attendance->student->email ?? null;

            if ($email) {
                Mail::to($email)->send(new AttendanceMail($attendance));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Deleted Successfully');
    }
}