<?php
 
namespace App\Http\Controllers;
 
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
 
class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subjects.index');
    }
 
    /**
     * Server-side AJAX source for the Subjects DataTable.
     */
    public function datatable(Request $request)
    {
        $columns = ['id', 'name', 'class', 'action'];
 
        $query = Subject::query()->with('schoolClass');
 
        $recordsTotal = (clone $query)->count();
 
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('schoolClass', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%");
                    });
            });
        }
 
        $recordsFiltered = (clone $query)->count();
 
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
 
        if ($orderColumn === 'class') {
            $query->join('school_classes', 'school_classes.id', '=', 'subjects.school_class_id')
                ->orderBy('school_classes.name', $orderDir)
                ->select('subjects.*');
        } elseif ($orderColumn === 'action') {
            $query->orderBy('id', $orderDir);
        } else {
            $query->orderBy($orderColumn, $orderDir);
        }
 
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
 
        $subjects = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();
 
        $data = $subjects->map(function ($subject) {
            $actions = '<div class="action-buttons">
                <a href="' . route('subjects.show', $subject->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('subjects.edit', $subject->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('subjects.destroy', $subject->id) . '"
                    data-confirm="Delete this subject?">
                    Delete
                </button>
            </div>';
 
            return [
                'id' => $subject->id,
                'name' => e($subject->name),
                'class' => e(optional($subject->schoolClass)->name),
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
        $classes = SchoolClass::all();
 
        return view('subjects.create', compact('classes'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);
 
        Subject::create([
            'name' => $request->name,
            'school_class_id' => $request->school_class_id,
        ]);
 
        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject Added Successfully');
    }
 
    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $classes = SchoolClass::all();
 
        return view('subjects.edit', compact('subject', 'classes'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);
 
        $subject->update([
            'name' => $request->name,
            'school_class_id' => $request->school_class_id,
        ]);
 
        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject Updated Successfully');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
 
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject Deleted Successfully',
            ]);
        }
 
        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject Deleted Successfully');
    }
}