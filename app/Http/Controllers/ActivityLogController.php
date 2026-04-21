<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with('user')->latest('created_at');

        if (!$request->user()->is_admin) {
            $query->where('user_id', $request->user()->id);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('description', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%")
                    ->orWhere('entity_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $actionFilter = trim((string) $request->query('action', 'all'));
        if ($actionFilter !== '' && $actionFilter !== 'all') {
            $query->where('action', $actionFilter);
        }

        $logs = $query->paginate(15)->withQueryString();

        $baseStats = ActivityLog::query();
        if (!$request->user()->is_admin) {
            $baseStats->where('user_id', $request->user()->id);
        }

        $stats = [
            'today' => (clone $baseStats)->whereDate('created_at', now()->toDateString())->count(),
            'created' => (clone $baseStats)->where('action', 'created')->count(),
            'updated' => (clone $baseStats)->where('action', 'updated')->count(),
            'deleted' => (clone $baseStats)->where('action', 'deleted')->count(),
            'total' => (clone $baseStats)->count(),
        ];

        return view('aquaheart.logs.index', compact('logs', 'stats', 'actionFilter', 'search'));
    }
}
