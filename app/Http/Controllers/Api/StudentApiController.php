<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource (supports ?search=name & ?class=).
     */
    public function index(Request $request)
    {
        $query = Student::with('user');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
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
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string',
            'class'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'class'   => $request->class,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Student created successfully',
            'data'    => $student->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(
            Student::with('user')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'    => 'sometimes|required|string|max:255',
            'phone'   => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'class'   => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $student->update($request->only('name', 'phone', 'address', 'class'));

        return response()->json([
            'status'  => true,
            'message' => 'Student updated successfully',
            'data'    => $student->load('user'),
        ]);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Student deleted successfully',
        ]);
    }
}
