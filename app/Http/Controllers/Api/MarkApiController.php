<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarkApiController extends Controller
{
    /**
     * Display a listing of the resource (supports ?student_id= & ?subject_id=).
     */
    public function index(Request $request)
    {
        $query = Mark::with(['student.user', 'subject']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return response()->json(
            $query->latest()->paginate($request->get('per_page', 10))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id'  => 'required|exists:students,id',
            'subject_id'  => 'required|exists:subjects,id',
            'marks'       => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $mark = Mark::create(array_merge(
            $validator->validated(),
            ['grade' => $this->calculateGrade($request->marks, $request->total_marks)]
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Marks recorded successfully',
            'data'    => $mark->load(['student.user', 'subject']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Mark::with(['student.user', 'subject'])->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mark = Mark::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'marks'       => 'sometimes|required|numeric|min:0',
            'total_marks' => 'sometimes|required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $marksValue = $request->get('marks', $mark->marks);
        $totalMarks = $request->get('total_marks', $mark->total_marks);

        $mark->update(array_merge(
            $validator->validated(),
            ['grade' => $this->calculateGrade($marksValue, $totalMarks)]
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Marks updated successfully',
            'data'    => $mark->load(['student.user', 'subject']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mark = Mark::findOrFail($id);
        $mark->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Marks deleted successfully',
        ]);
    }

    /**
     * Calculate letter grade from marks / total_marks percentage.
     */
    protected function calculateGrade($marks, $totalMarks): string
    {
        $percentage = ($marks / $totalMarks) * 100;

        return match (true) {
            $percentage >= 80 => 'A+',
            $percentage >= 70 => 'A',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C',
            $percentage >= 40 => 'D',
            default => 'F',
        };
    }
}
