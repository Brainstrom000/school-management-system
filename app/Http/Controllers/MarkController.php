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
        return view('marks.index');
    }

    /**
     * Server-side AJAX source for the Marks DataTable.
     */
    public function datatable(Request $request)
    {
        $columns = ['id', 'student', 'subject', 'marks', 'total_marks', 'grade', 'action'];

        $query = Mark::query()->with(['student.user', 'subject']);

        $recordsTotal = (clone $query)->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('grade', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subject', function ($s) use ($search) {
                        $s->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumn, ['student', 'subject', 'action'])) {
            $orderColumn = 'id';
        }

        $query->orderBy($orderColumn, $orderDir);

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $marks = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();

        $data = $marks->map(function ($mark) {
            $actions = '
                <a href="' . route('marks.show', $mark->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('marks.edit', $mark->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('marks.destroy', $mark->id) . '"
                    data-confirm="Delete this mark?">
                    Delete
                </button>
            ';

            return [
                'id' => $mark->id,
                'student' => e(optional(optional($mark->student)->user)->name ?? 'N/A'),
                'subject' => e(optional($mark->subject)->name),
                'marks' => e($mark->marks),
                'total_marks' => e($mark->total_marks),
                'grade' => e($mark->grade),
                'action' => $actions,
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
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

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Marks Deleted Successfully',
            ]);
        }

        return redirect()
            ->route('marks.index')
            ->with('success', 'Marks Deleted Successfully');
    }
}