<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentWelcomeMail;
use App\Http\Controllers\ActivityLogController;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('user');

        if ($request->search) {
            $students->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->class) {
            $students->where('class', $request->class);
        }

        $students = $students->latest()
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = \App\Models\SchoolClass::all();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'required',
            'address' => 'required',
            'class' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $image = null;

        if ($request->hasFile('profile_image')) {
            $image = time() . '.' . $request->profile_image->extension();
            $request->profile_image->move(public_path('students'), $image);
        }

        Student::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'class' => $request->class,
            'profile_image' => $image,
        ]);

        $mailWarning = null;

        try {
            Mail::to($user->email)->send(
                new StudentWelcomeMail($user)
            );
        } catch (\Throwable $e) {
            report($e);
            $mailWarning = 'Student was saved, but the welcome email could not be sent. (' . $e->getMessage() . ')';
        }

        ActivityLogController::log(
            'Student',
            'Create',
            'Student "' . $user->name . '" has been created.'
        );

        $redirect = redirect()
            ->route('students.index')
            ->with('success', 'Student Added Successfully');

        return $mailWarning ? $redirect->with('warning', $mailWarning) : $redirect;
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = \App\Models\SchoolClass::all();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->user->id,
            'phone' => 'required',
            'address' => 'required',
            'class' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $image = $student->profile_image;

        if ($request->hasFile('profile_image')) {

            if ($image && File::exists(public_path('students/' . $image))) {
                File::delete(public_path('students/' . $image));
            }

            $image = time() . '.' . $request->profile_image->extension();
            $request->profile_image->move(public_path('students'), $image);
        }

        $student->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'class' => $request->class,
            'profile_image' => $image,
        ]);

        ActivityLogController::log(
            'Student',
            'Update',
            'Student "' . $student->user->name . '" has been updated.'
        );

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Updated Successfully');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        ActivityLogController::log(
            'Student',
            'Delete',
            'Student "' . $student->user->name . '" moved to trash.'
        );

        return redirect()
            ->route('students.index')
            ->with('success', 'Student moved to Trash Successfully');
    }

    public function trash()
    {
        $students = Student::onlyTrashed()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('students.trash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $student->restore();

        ActivityLogController::log(
            'Student',
            'Restore',
            'Student "' . $student->user->name . '" restored from trash.'
        );

        return redirect()
            ->route('students.trash')
            ->with('success', 'Student Restored Successfully');
    }

    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $name = $student->user?->name;

        if (
            $student->profile_image &&
            File::exists(public_path('students/' . $student->profile_image))
        ) {
            File::delete(public_path('students/' . $student->profile_image));
        }

        if ($student->user) {
            $student->user->delete();
        }

        $student->forceDelete();

        ActivityLogController::log(
            'Student',
            'Force Delete',
            'Student "' . $name . '" permanently deleted.'
        );

        return redirect()
            ->route('students.trash')
            ->with('success', 'Student Permanently Deleted Successfully');
    }
}