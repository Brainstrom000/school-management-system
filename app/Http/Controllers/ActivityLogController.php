<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Store Activity Log
     */
    public static function log($module, $action, $description)
    {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'module'      => $module,
            'action'      => $action,
            'description' => $description,
        ]);
    }

    /**
     * Display Activity Logs
     */
    public function index()
    {
        $logs = ActivityLog::with('user')
                    ->latest()
                    ->paginate(10);

        return view('activity_logs.index', compact('logs'));
    }
}