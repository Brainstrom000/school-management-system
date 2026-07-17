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
    public function index(Request $request)
    {
        $query = Fee::with('student.user')->latest();

        if (auth()->user()->role === 'student') {
            $student = Student::where('user_id', auth()->id())->firstOrFail();
            $query->where('student_id', $student->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fees = $query->paginate(10)->withQueryString();

        return view('fees.index', compact('fees'));
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
