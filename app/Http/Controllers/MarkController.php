<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marks = Mark::with(['student.user', 'subject'])
            ->latest()
            ->paginate(10);

        return view('marks.index', compact('marks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $subjects = Subject::all();

        return view('marks.create', compact('students', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks'      => 'required|numeric|min:0',
            'total_marks'=> 'required|numeric|min:1',
        ]);

        // Grade Calculate
        $percentage = ($request->marks / $request->total_marks) * 100;

        if ($percentage >= 80) {
            $grade = 'A+';
        } elseif ($percentage >= 70) {
            $grade = 'A';
        } elseif ($percentage >= 60) {
            $grade = 'B';
        } elseif ($percentage >= 50) {
            $grade = 'C';
        } elseif ($percentage >= 40) {
            $grade = 'D';
        } else {
            $grade = 'F';
        }

        Mark::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'marks'      => $request->marks,
            'total_marks'=> $request->total_marks,
            'grade'      => $grade,
        ]);

        return redirect()
            ->route('marks.index')
            ->with('success', 'Marks Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mark $mark)
    {
        return view('marks.show', compact('mark'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mark $mark)
    {
        $students = Student::with('user')->get();
        $subjects = Subject::all();

        return view('marks.edit', compact(
            'mark',
            'students',
            'subjects'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mark $mark)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks'      => 'required|numeric|min:0',
            'total_marks'=> 'required|numeric|min:1',
        ]);

        $percentage = ($request->marks / $request->total_marks) * 100;

        if ($percentage >= 80) {
            $grade = 'A+';
        } elseif ($percentage >= 70) {
            $grade = 'A';
        } elseif ($percentage >= 60) {
            $grade = 'B';
        } elseif ($percentage >= 50) {
            $grade = 'C';
        } elseif ($percentage >= 40) {
            $grade = 'D';
        } else {
            $grade = 'F';
        }

        $mark->update([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'marks'      => $request->marks,
            'total_marks'=> $request->total_marks,
            'grade'      => $grade,
        ]);

        return redirect()
            ->route('marks.index')
            ->with('success', 'Marks Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mark $mark)
    {
        $mark->delete();

        return redirect()
            ->route('marks.index')
            ->with('success', 'Marks Deleted Successfully');
    }
}