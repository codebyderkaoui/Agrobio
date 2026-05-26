<?php
// ============================================================
// app/Http/Controllers/ClientController.php  (new file)
// ============================================================
namespace App\Http\Controllers;

use App\Models\Order;

class ClientController extends Controller
{
    /** Web view — the blade does its own aggregation query */
    public function index()
    {
        return view('clients.index');
    }

    /**
     * JSON API — order history for one client
     * GET /api/clients/{name}/orders
     */
    public function orders(string $clientName)
    {
        $orders = Order::where('client_name', $clientName)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->select([
                'id', 'order_number', 'client_name', 'client_email', 'client_phone',
                'delivery_mode', 'delivery_address', 'total_amount',
                'status', 'notes', 'created_at',
            ])
            ->get();

        return response()->json([
            'client' => $clientName,
            'orders' => $orders,
        ]);
    }
}
