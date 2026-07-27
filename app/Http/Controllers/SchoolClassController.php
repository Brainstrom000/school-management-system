<?php
 
namespace App\Http\Controllers;
 
use App\Models\SchoolClass;
use Illuminate\Http\Request;
 
class SchoolClassController extends Controller
{
    public function index()
    {
        return view('classes.index');
    }
 
    /**
     * Server-side AJAX source for the Classes DataTable.
     */
    public function datatable(Request $request)
    {
        $columns = ['id', 'name', 'fee_amount', 'action'];
 
        $query = SchoolClass::query();
 
        $recordsTotal = (clone $query)->count();
 
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('fee_amount', 'like', "%{$search}%");
            });
        }
 
        $recordsFiltered = (clone $query)->count();
 
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
 
        if ($orderColumn === 'action') {
            $orderColumn = 'id';
        }
 
        $query->orderBy($orderColumn, $orderDir);
 
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
 
        $classes = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();
 
        $data = $classes->map(function ($class) {
            $actions = '<div class="action-buttons">
                <a href="' . route('classes.show', $class->id) . '" class="btn btn-info btn-sm">View</a>
                <a href="' . route('classes.edit', $class->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button type="button"
                    class="btn btn-danger btn-sm ajax-delete-btn"
                    data-url="' . route('classes.destroy', $class->id) . '"
                    data-confirm="Are you sure you want to delete this class?">
                    Delete
                </button>
            </div>';
 
            return [
                'id' => $class->id,
                'name' => e($class->name),
                'fee_amount' => 'Rs ' . number_format($class->fee_amount, 0),
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
        return view('classes.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|unique:school_classes,name',
            'fee_amount' => 'nullable|numeric|min:0',
        ]);
 
        SchoolClass::create([
            'name'       => $request->name,
            'fee_amount' => $request->fee_amount ?? 0,
        ]);
 
        return redirect()
            ->route('classes.index')
            ->with('success', 'Class Added Successfully');
    }
 
    public function show(SchoolClass $class)
    {
        return view('classes.show', compact('class'));
    }
 
    public function edit(SchoolClass $class)
    {
        return view('classes.edit', compact('class'));
    }
 
    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'       => 'required|unique:school_classes,name,' . $class->id,
            'fee_amount' => 'nullable|numeric|min:0',
        ]);
 
        $class->update([
            'name'       => $request->name,
            'fee_amount' => $request->fee_amount ?? $class->fee_amount,
        ]);
 
        return redirect()
            ->route('classes.index')
            ->with('success', 'Class Updated Successfully');
    }
 
    public function destroy(SchoolClass $class)
    {
        $class->delete();
 
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class Deleted Successfully',
            ]);
        }
 
        return redirect()
            ->route('classes.index')
            ->with('success', 'Class Deleted Successfully');
    }
}