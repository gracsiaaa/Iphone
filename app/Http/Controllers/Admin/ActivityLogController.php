<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::query()
            ->with(['user', 'subject'])
            ->latest('created_at');

        $search = trim((string) $request->input('q'));

        if ($search !== '') {
            $query->where(function ($activityQuery) use ($search): void {
                $activityQuery
                    ->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search): void {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('store_name', 'like', "%{$search}%");
                    });
            });
        }

        $category = (string) $request->input('category');

        if ($category === 'auth') {
            $query->where('action', 'like', 'auth.%');
        } elseif ($category === 'payment') {
            $query->whereIn('action', [
                'payment.submitted',
                'order.approved',
                'order.rejected',
            ]);
        } elseif ($category === 'order') {
            $query->whereIn('action', [
                'order.created',
                'order.completed',
            ]);
        }

        $role = (string) $request->input('role');

        if (in_array($role, ['user', 'admin', 'superadmin'], true)) {
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('role', $role));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $today = now()->toDateString();

        $stats = [
            'today' => ActivityLog::whereDate('created_at', $today)->count(),
            'login' => ActivityLog::whereDate('created_at', $today)
                ->where('action', 'auth.login')
                ->count(),
            'approved' => ActivityLog::whereDate('created_at', $today)
                ->where('action', 'order.approved')
                ->count(),
            'rejected' => ActivityLog::whereDate('created_at', $today)
                ->where('action', 'order.rejected')
                ->count(),
        ];

        return view('admin.activity-logs.index', [
            'logs' => $query->paginate(25)->withQueryString(),
            'stats' => $stats,
        ]);
    }
}
