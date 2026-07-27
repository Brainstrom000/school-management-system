<?php
 
namespace App\Http\Controllers;
 
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
 
class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * Admin sees all fees. Student sees only their own.
     */
    public function index()
    {
        return view('fees.index');
    }
 
    /**
     * Server-side AJAX source for the Fees DataTable.
     * Admin sees every fee (with student column); a student only sees their own.
     */
    public function datatable(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';
 
        // Column order must match the columns defined in the Blade view's JS config
        $columns = $isAdmin
            ? ['id', 'student', 'title', 'amount', 'due_date', 'status', 'action']
            : ['id', 'title', 'amount', 'due_date', 'status', 'action'];
 
        $query = Fee::query()->with('student.user');
 
        if (!$isAdmin) {
            $student = Student::where('user_id', auth()->id())->firstOrFail();
            $query->where('student_id', $student->id);
        }
 
        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }
 
        $recordsTotal = (clone $query)->count();
 
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search, $isAdmin) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
 
                if ($isAdmin) {
                    $q->orWhereHas('student.user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
                }
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
 
        $fees = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();
 
        $data = $fees->map(function ($fee) use ($isAdmin) {
            $status = $fee->status === 'paid'
                ? '<span class="badge bg-success">Paid</span>'
                : '<span class="badge bg-danger">Unpaid</span>';
 
            $actions = '<a href="' . route('fees.show', $fee->id) . '" class="btn btn-info btn-sm">View</a> ';
 
            if ($fee->status === 'unpaid') {
                $actions .= '<a href="' . route('fees.pay', $fee->id) . '" class="btn btn-success btn-sm">Pay Now</a> ';
            }
 
            if ($isAdmin) {
                $actions .= '
                    <a href="' . route('fees.edit', $fee->id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button"
                        class="btn btn-danger btn-sm ajax-delete-btn"
                        data-url="' . route('fees.destroy', $fee->id) . '"
                        data-confirm="Are you sure you want to delete this fee record?">
                        Delete
                    </button>
                ';
            }
 
            $row = [
                'id' => $fee->id,
                'title' => e($fee->title),
                'amount' => 'Rs ' . number_format($fee->amount, 0),
                'due_date' => $fee->due_date->format('d M Y'),
                'status' => $status,
                'action' => '<div class="action-buttons">' . $actions . '</div>',
            ];
 
            if ($isAdmin) {
                $row['student'] = e(optional(optional($fee->student)->user)->name ?? 'N/A');
            }
 
            return $row;
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
        $classFees = \App\Models\SchoolClass::pluck('fee_amount', 'name');
 
        return view('fees.create', compact('students', 'classFees'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title'      => 'required|string|max:255',
            'amount'     => 'required|numeric|min:1',
            'due_date'   => 'required|date',
        ]);
 
        $fee = Fee::create($request->only('student_id', 'title', 'amount', 'due_date'));
 
        ActivityLogController::log('Fee', 'Create', 'Fee "' . $fee->title . '" created for student #' . $fee->student_id);
 
        return redirect()->route('fees.index')->with('success', 'Fee record created successfully.');
    }
 
    /**
     * Display the specified resource.
     */
    public function show(Fee $fee)
    {
        $this->authorizeFeeAccess($fee);
 
        return view('fees.show', compact('fee'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fee $fee)
    {
        $students = Student::with('user')->get();
 
        return view('fees.edit', compact('fee', 'students'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fee $fee)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title'      => 'required|string|max:255',
            'amount'     => 'required|numeric|min:1',
            'due_date'   => 'required|date',
            'status'     => 'required|in:unpaid,paid',
        ]);
 
        $fee->update($request->only('student_id', 'title', 'amount', 'due_date', 'status'));
 
        ActivityLogController::log('Fee', 'Update', 'Fee #' . $fee->id . ' updated');
 
        return redirect()->route('fees.index')->with('success', 'Fee record updated successfully.');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();
 
        ActivityLogController::log('Fee', 'Delete', 'Fee #' . $fee->id . ' deleted');
 
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fee record deleted successfully.',
            ]);
        }
 
        return redirect()->route('fees.index')->with('success', 'Fee record deleted successfully.');
    }
 
    /**
     * Show payment method selection page for an unpaid fee.
     */
    public function pay(Fee $fee)
    {
        $this->authorizeFeeAccess($fee);
 
        if ($fee->isPaid()) {
            return redirect()->route('fees.show', $fee)->with('error', 'This fee has already been paid.');
        }
 
        return view('fees.pay', compact('fee'));
    }
 
    /**
     * Ensure the current user is allowed to view/pay this fee.
     */
    protected function authorizeFeeAccess(Fee $fee): void
    {
        if (auth()->user()->role === 'student') {
            $student = Student::where('user_id', auth()->id())->first();
 
            abort_unless($student && $fee->student_id === $student->id, 403, 'Unauthorized Access');
        }
    }
}