<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::latest()->paginate(10);

        return view('classes.index', compact('classes'));
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

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class Deleted Successfully');
    }
}