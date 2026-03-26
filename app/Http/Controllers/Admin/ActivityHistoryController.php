<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Order;
use Illuminate\Http\Request;

class ActivityHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $action = $request->query('action');
        $admin = $request->query('admin');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = AdminActivityLog::query()
            ->leftJoin('orders', 'orders.order_id', '=', 'admin_activity_logs.order_id')
            ->select([
                'admin_activity_logs.id',
                'admin_activity_logs.order_id as order_id',
                'admin_activity_logs.action',
                'admin_activity_logs.admin_id',
                'admin_activity_logs.admin_name',
                'admin_activity_logs.admin_email',
                'admin_activity_logs.notes',
                'admin_activity_logs.created_at',
                'orders.username as order_username',
                'orders.email as order_email',
                'orders.game_type as order_game_type',
                'orders.amount as order_amount',
                'orders.payment_status as order_payment_status',
                'orders.order_status as order_order_status',
            ])
            ->orderByDesc('admin_activity_logs.created_at');

        if ($action) {
            $query->where('admin_activity_logs.action', $action);
        }

        if ($admin) {
            $query->where(function ($q) use ($admin) {
                $q->where('admin_activity_logs.admin_name', 'like', '%' . $admin . '%')
                    ->orWhere('admin_activity_logs.admin_email', 'like', '%' . $admin . '%');
            });
        }

        if ($search) {
            $normalized = ltrim(trim((string) $search), '#');
            $query->where(function ($q) use ($search, $normalized) {
                $q->where('orders.username', 'like', '%' . $search . '%')
                    ->orWhere('admin_activity_logs.order_id', 'like', '%' . $normalized . '%');
            });
        }

        if ($dateFrom) {
            $query->whereDate('admin_activity_logs.created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('admin_activity_logs.created_at', '<=', $dateTo);
        }

        $activities = $query->paginate(30)->appends($request->query());

        $actionOptions = AdminActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.activity-history', compact('activities', 'actionOptions'));
    }
}
