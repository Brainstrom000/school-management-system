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
        return view('activity_logs.index');
    }

    /**
     * Server-side AJAX source for the Activity Logs DataTable.
     */
    public function datatable(\Illuminate\Http\Request $request)
    {
        $columns = ['id', 'user', 'role', 'module', 'action', 'description', 'created_at'];

        $query = ActivityLog::query()->with('user');

        $recordsTotal = (clone $query)->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumn, ['user', 'role'])) {
            $orderColumn = 'id';
        }

        $query->orderBy($orderColumn, $orderDir);

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $logs = $length === -1
            ? $query->get()
            : $query->skip($start)->take($length)->get();

        $actionBadges = [
            'Create' => 'success',
            'Update' => 'primary',
            'Delete' => 'warning',
            'Restore' => 'info',
            'Force Delete' => 'danger',
        ];

        $data = $logs->map(function ($log) use ($actionBadges) {
            $badgeClass = $actionBadges[$log->action] ?? 'secondary';

            return [
                'id' => $log->id,
                'user' => e(optional($log->user)->name ?? 'Deleted User'),
                'role' => '<span class="badge badge-info">' . e(ucfirst(optional($log->user)->role ?? 'N/A')) . '</span>',
                'module' => e($log->module),
                'action' => '<span class="badge badge-' . $badgeClass . '">' . e($log->action) . '</span>',
                'description' => e($log->description),
                'created_at' => $log->created_at->format('d M Y h:i A'),
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}