<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeacherWelcomeMail;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teachers.index');
    }

    /**
     * Server-side AJAX source for the Teachers DataTable.
     */
    public function datatable(Request $request)
    {
        $columns = ['id', 'name', 'email', 'subject', 'salary', 'action'];

        $query = Teacher::query()->with('user');

        $recordsTotal = (clone $query)->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        if ($orderColumn === 'action') {
            $orderColumn = 'id';
        }

        if (in_array($orderColumn, ['name', 'email'])) {
            $query->join('users', 'users.id', '=', 'teachers.user_id')
                ->orderBy("users.{$orderColumn}", $orderDir)
                ->select('teachers.*');
        } else {
            $query->orderBy($orderColumn, $orderDir);
        }

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $teachers = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();

        $data = $teachers->map(function ($teacher) {
            $actions = '
                <a href="' . route('teachers.show', $teacher->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('teachers.edit', $teacher->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('teachers.destroy', $teacher->id) . '"
                    data-confirm="Move this teacher to Trash?">
                    Delete
                </button>
            ';

            return [
                'id' => $teacher->id,
                'name' => e(optional($teacher->user)->name),
                'email' => e(optional($teacher->user)->email),
                'subject' => e($teacher->subject),
                'salary' => e($teacher->salary),
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
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'subject' => 'required',
            'salary' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'salary' => $request->salary,
        ]);
        $mailWarning = null;

        try {
            Mail::to($user->email)->send(new TeacherWelcomeMail($user));
        } catch (\Throwable $e) {
            report($e);
            $mailWarning = 'Teacher was saved, but the welcome email could not be sent. (' . $e->getMessage() . ')';
        }

        ActivityLogController::log(
            'Teacher',
            'Create',
            'Teacher "' . $user->name . '" has been created.'
        );

        $redirect = redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher Added Successfully');

        return $mailWarning ? $redirect->with('warning', $mailWarning) : $redirect;
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'subject' => 'required',
            'salary' => 'required|numeric',
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $teacher->update([
            'subject' => $request->subject,
            'salary' => $request->salary,
        ]);

        ActivityLogController::log(
            'Teacher',
            'Update',
            'Teacher "' . $teacher->user->name . '" has been updated.'
        );

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher Updated Successfully');
    }

    /**
     * Soft Delete Teacher
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        ActivityLogController::log(
            'Teacher',
            'Delete',
            'Teacher "' . $teacher->user->name . '" moved to trash.'
        );

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher moved to Trash Successfully',
            ]);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher moved to Trash Successfully');
    }

    /**
     * Show Deleted Teachers
     */
    public function trash()
    {
        $teachers = Teacher::onlyTrashed()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('teachers.trash', compact('teachers'));
    }

    /**
     * Restore Deleted Teacher
     */
    public function restore($id)
    {
        $teacher = Teacher::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $teacher->restore();

        ActivityLogController::log(
            'Teacher',
            'Restore',
            'Teacher "' . $teacher->user->name . '" restored from trash.'
        );

        return redirect()
            ->route('teachers.trash')
            ->with('success', 'Teacher Restored Successfully');
    }

    /**
     * Permanently Delete Teacher
     */
    public function forceDelete($id)
    {
        $teacher = Teacher::onlyTrashed()
            ->with('user')
            ->findOrFail($id);

        $name = $teacher->user?->name;

        if ($teacher->user) {
            $teacher->user->delete();
        }

        $teacher->forceDelete();

        ActivityLogController::log(
            'Teacher',
            'Force Delete',
            'Teacher "' . $name . '" permanently deleted.'
        );

        return redirect()
            ->route('teachers.trash')
            ->with('success', 'Teacher Permanently Deleted Successfully');
    }
}