<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    /**
     * Show the logged-in student's own result.
     */
    public function myResult(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            abort(404, 'Student record not found for this account.');
        }

        $marks = $student->marks()
            ->with('subject')
            ->latest()
            ->get();

        $totalObtained = $marks->sum('marks');
        $totalMax = $marks->sum('total_marks');

        $overallPercentage = $totalMax > 0
            ? round(($totalObtained / $totalMax) * 100, 2)
            : 0;

        $attendanceSummary = [
            'present' => $student->attendances()->where('status', 'Present')->count(),
            'absent'  => $student->attendances()->where('status', 'Absent')->count(),
            'leave'   => $student->attendances()->where('status', 'Leave')->count(),
        ];

        return view('students.result', compact(
            'student',
            'marks',
            'totalObtained',
            'totalMax',
            'overallPercentage',
            'attendanceSummary'
        ));
    }
}