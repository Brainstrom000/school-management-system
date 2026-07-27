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
    public function index()
    {
        return view('students.index');
    }
 
    /**
     * Server-side AJAX source for the Students DataTable.
     *
     * Handles searching, column sorting, and pagination entirely on the
     * server so the client only ever receives the current page of rows.
     */
    public function datatable(Request $request)
    {
        // Index must match the column order defined in the DataTable JS config
        $columns = ['id', 'image', 'name', 'email', 'phone', 'address', 'class', 'action'];
 
        $query = Student::query()->with('user');
 
        $recordsTotal = (clone $query)->count();
 
        // Global search (DataTables sends it as search[value])
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('class', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
 
        $recordsFiltered = (clone $query)->count();
 
        // Column sorting
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
 
        if (in_array($orderColumn, ['image', 'action'])) {
            $orderColumn = 'id';
        }
 
        if (in_array($orderColumn, ['name', 'email'])) {
            $query->join('users', 'users.id', '=', 'students.user_id')
                ->orderBy("users.{$orderColumn}", $orderDir)
                ->select('students.*');
        } else {
            $query->orderBy($orderColumn, $orderDir);
        }
 
        // Pagination
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
 
        if ($length === -1) {
            $students = $query->get();
        } else {
            $students = $query->skip($start)->take($length)->get();
        }
 
        $data = $students->map(function ($student) {
            $image = $student->profile_image
                ? '<img src="' . asset('students/' . $student->profile_image) . '" width="45" height="45" class="rounded-circle">'
                : '<span class="text-muted">No Image</span>';
 
            $actions = '<div class="action-buttons">
                <a href="' . route('students.show', $student->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('students.edit', $student->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('students.destroy', $student->id) . '"
                    data-confirm="Are you sure you want to delete this student?">
                    Delete
                </button>
            </div>';
 
            return [
                'id' => $student->id,
                'image' => $image,
                'name' => e(optional($student->user)->name),
                'email' => e(optional($student->user)->email),
                'phone' => e($student->phone),
                'address' => e($student->address),
                'class' => e($student->class),
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
 
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student moved to Trash Successfully',
            ]);
        }
 
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