<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Full notices list — everyone sees the notices meant for their role;
     * admins additionally get the post/delete controls.
     */
    public function index()
    {
        $notices = Notice::with('postedBy')
            ->visibleTo(auth()->user())
            ->latest()
            ->paginate(15);

        return view('notices.index', compact('notices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'message'  => 'required|string|max:2000',
            'audience' => 'required|in:all,students,teachers',
        ]);

        Notice::create([
            ...$validated,
            'posted_by' => auth()->id(),
        ]);

        return redirect()->route('notices.index')->with('success', 'Notice posted successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('notices.index')->with('success', 'Notice deleted.');
    }
}
