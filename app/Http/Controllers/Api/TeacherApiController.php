<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherApiController extends Controller
{
    /**
     * Display a listing of the resource (supports ?subject=).
     */
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'subject'  => 'required|string',
            'salary'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'salary'  => $request->salary,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Teacher created successfully',
            'data'    => $teacher->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(
            Teacher::with('user')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'subject' => 'sometimes|required|string',
            'salary'  => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $teacher->update($request->only('subject', 'salary'));

        return response()->json([
            'status'  => true,
            'message' => 'Teacher updated successfully',
            'data'    => $teacher->load('user'),
        ]);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Teacher deleted successfully',
        ]);
    }
}
