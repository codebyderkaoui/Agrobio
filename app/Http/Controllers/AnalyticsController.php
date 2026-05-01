<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Farm;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('analytics.index');
    }

    /**
     * GET /api/analytics/summary
     */
    public function summary(): JsonResponse
    {
        $thisMonth  = now()->startOfMonth();
        $lastMonth  = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $revenueThis = Order::where('created_at', '>=', $thisMonth)->sum('total_amount');
        $revenueLast = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_amount');

        $ordersThis = Order::where('created_at', '>=', $thisMonth)->count();
        $ordersLast = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();

        $avgBasket = $ordersThis > 0 ? round($revenueThis / $ordersThis, 2) : 0;

        return response()->json([
            'revenue'     => $revenueThis,
            'revenue_pct' => $revenueLast > 0 ? round((($revenueThis - $revenueLast) / $revenueLast) * 100, 1) : 0,
            'orders'      => $ordersThis,
            'orders_pct'  => $ordersLast > 0 ? round((($ordersThis - $ordersLast) / $ordersLast) * 100, 1) : 0,
            'avg_basket'  => $avgBasket,
        ]);
    }

    /**
     * GET /api/analytics/monthly
     * Returns monthly revenue for the current year
     */
    public function monthly(): JsonResponse
    {
        $rows = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
        $data   = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = [
                'label' => $months[$m - 1],
                'total' => $rows[$m] ?? 0,
            ];
        }

        return response()->json($data);
    }

    /**
     * GET /api/analytics/top-products
     */
    public function topProducts(): JsonResponse
    {
        $products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('farms', 'products.farm_id', '=', 'farms.id')
            ->select(
                'products.name',
                'products.emoji',
                'farms.name as farm_name',
                DB::raw('SUM(order_items.quantity) as qty_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('order_items.product_id', 'products.name', 'products.emoji', 'farms.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return response()->json($products);
    }

    /**
     * GET /api/analytics/top-farms
     */
    public function topFarms(): JsonResponse
    {
        $farms = DB::table('farms')
            ->leftJoin('products', 'farms.id', '=', 'products.farm_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->select(
                'farms.name',
                'farms.emoji',
                'farms.city',
                DB::raw('COALESCE(SUM(order_items.subtotal), 0) as revenue')
            )
            ->where('farms.is_active', true)
            ->groupBy('farms.id', 'farms.name', 'farms.emoji', 'farms.city')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return response()->json($farms);
    }

    /**
     * GET /api/analytics/categories
     */
    public function categories(): JsonResponse
    {
        $data = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.category',
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.category')
            ->orderByDesc('revenue')
            ->get();

        $total = $data->sum('revenue');

        $result = $data->map(fn($row) => [
            'category' => $row->category,
            'revenue'  => $row->revenue,
            'pct'      => $total > 0 ? round(($row->revenue / $total) * 100, 1) : 0,
        ]);

        return response()->json($result);
    }
}
