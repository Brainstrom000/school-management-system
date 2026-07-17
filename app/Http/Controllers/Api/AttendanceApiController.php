<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends Controller
{
    /**
     * Display a listing of the resource (supports ?student_id= & ?date=).
     */
    public function index(Request $request)
    {
        $query = Attendance::with('student.user');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
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
            'student_id' => 'required|exists:students,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $attendance = Attendance::create($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Attendance recorded successfully',
            'data'    => $attendance->load('student.user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(
            Attendance::with('student.user')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date'   => 'sometimes|required|date',
            'status' => 'sometimes|required|in:Present,Absent,Leave',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $attendance->update($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Attendance updated successfully',
            'data'    => $attendance->load('student.user'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Attendance deleted successfully',
        ]);
    }
}
