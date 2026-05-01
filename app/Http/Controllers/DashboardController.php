<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Task;
use App\Models\Project;
use App\Models\Farm;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── KPI stats ──────────────────────────────
        $stats = [
            'products_active'   => Product::active()->count(),
            'orders_this_month' => Order::thisMonth()->count(),
            'revenue_this_month'=> Order::thisMonth()->sum('total_amount'),
            'tasks_done'        => Task::where('is_done', true)->count(),
            'tasks_total'       => Task::count(),
        ];

        // ── Priority tasks (open, max 6) ───────────
        $priorityTasks = Task::with('assignee')
            ->where('is_done', false)
            ->orderByRaw("FIELD(priority, 'high', 'med', 'low')")
            ->limit(6)
            ->get();

        // ── Recent orders ──────────────────────────
        $recentOrders = Order::with('creator')
            ->latest()
            ->limit(4)
            ->get();

        // ── Top farms by revenue ───────────────────
        $topFarms = Farm::select('farms.*')
            ->selectRaw('COALESCE(SUM(oi.subtotal), 0) as total_revenue')
            ->leftJoin('products', 'farms.id', '=', 'products.farm_id')
            ->leftJoin('order_items as oi', 'products.id', '=', 'oi.product_id')
            ->where('farms.is_active', true)
            ->groupBy('farms.id')
            ->orderByDesc('total_revenue')
            ->limit(4)
            ->get();

        // ── Weekly chart data (last 7 days) ────────
        $weeklyRevenue = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklyData[] = [
                'label' => now()->subDays($i)->translatedFormat('D'),
                'total' => $weeklyRevenue[$date]->total ?? 0,
            ];
        }

        // ── Order status counts ─────────────────────
        $orderStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dashboard', compact(
            'stats',
            'priorityTasks',
            'recentOrders',
            'topFarms',
            'weeklyData',
            'orderStats'
        ));
    }
}
