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
        $subjects = Subject::with('schoolClass')
            ->latest()
            ->paginate(10);

        return view('subjects.index', compact('subjects'));
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

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject Deleted Successfully');
    }
}