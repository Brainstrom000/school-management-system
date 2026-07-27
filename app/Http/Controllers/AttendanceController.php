<?php
 
namespace App\Http\Controllers;
 
use App\Mail\AttendanceMail;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
 
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('attendances.index');
    }
 
    /**
     * Server-side AJAX source for the Attendances DataTable.
     */
    public function datatable(Request $request)
    {
        $columns = ['id', 'student', 'date', 'status', 'action'];
 
        $query = Attendance::query()->with('student.user');
 
        $recordsTotal = (clone $query)->count();
 
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }
 
        $recordsFiltered = (clone $query)->count();
 
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
 
        if (in_array($orderColumn, ['student', 'action'])) {
            $orderColumn = 'id';
        }
 
        $query->orderBy($orderColumn, $orderDir);
 
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
 
        $attendances = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();
 
        $data = $attendances->map(function ($attendance) {
            $actions = '<div class="action-buttons">
                <a href="' . route('attendances.show', $attendance->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('attendances.edit', $attendance->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('attendances.destroy', $attendance->id) . '"
                    data-confirm="Delete attendance record?">
                    Delete
                </button>
            </div>';
 
            return [
                'id' => $attendance->id,
                'student' => e(optional(optional($attendance->student)->user)->name),
                'date' => e($attendance->date),
                'status' => e($attendance->status),
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
 
        return view('attendances.create', compact('students'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
        ]);
 
        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date'       => $request->date,
            'status'     => $request->status,
        ]);
 
        $this->notifyStudent($attendance);
 
        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Added Successfully');
    }
 
    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $students = Student::with('user')->get();
 
        return view(
            'attendances.edit',
            compact('attendance', 'students')
        );
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
        ]);
 
        $attendance->update([
            'student_id' => $request->student_id,
            'date'       => $request->date,
            'status'     => $request->status,
        ]);
 
        $this->notifyStudent($attendance);
 
        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Updated Successfully');
    }
 
    /**
     * Email the student to let them know their attendance was marked.
     * Failure to send should never block the attendance flow.
     */
    protected function notifyStudent(Attendance $attendance): void
    {
        try {
            $attendance->loadMissing('student.user');
 
            $email = $attendance->student->user->email ?? $attendance->student->email ?? null;
 
            if ($email) {
                Mail::to($email)->send(new AttendanceMail($attendance));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
 
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance Deleted Successfully',
            ]);
        }
 
        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance Deleted Successfully');
    }
}