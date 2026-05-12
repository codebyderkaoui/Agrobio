<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Requests\Api\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    // ── Web view ──────────────────────────────────────────────────────────────

    public function index()
    {
        $stats = [
            'Nouveau'  => Order::byStatus('Nouveau')->count(),
            'En cours' => Order::byStatus('En cours')->count(),
            'Livré'    => Order::byStatus('Livré')->count(),
            'Annulé'   => Order::byStatus('Annulé')->count(),
        ];
        return view('orders.index', compact('stats'));
    }

    // ── REST API ──────────────────────────────────────────────────────────────

    /**
     * GET /api/orders
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $q = Order::with(['items.product', 'creator'])->latest();

        if ($s = $request->search) {
            $q->where(function ($query) use ($s) {
                $query->where('client_name', 'like', "%{$s}%")
                      ->orWhere('order_number', 'like', "%{$s}%");
            });
        }

        if ($status = $request->status) {
            $q->where('status', $status);
        }

        $orders = $q->paginate($request->integer('per_page', 20));

        return response()->json($orders);
    }

    /**
     * POST /api/orders
     * Accepts: { client_name, delivery_mode, notes, items: [{product_id, quantity}] }
     */
    public function apiStore(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();

        $order = Order::create([
            'order_number'     => Order::generateOrderNumber(),
            'client_name'      => $data['client_name'],
            'client_email'     => $data['client_email'] ?? null,
            'client_phone'     => $data['client_phone'] ?? null,
            'delivery_mode'    => $data['delivery_mode'] ?? 'Standard',
            'delivery_address' => $data['delivery_address'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'status'           => 'Nouveau',
            'total_amount'     => 0,
            'created_by'       => auth()->id(),
        ]);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $product   = Product::findOrFail($item['product_id']);
                $qty       = (int) $item['quantity'];
                $unitPrice = (float) $product->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $unitPrice * $qty,
                ]);
            }
            $order->recalculateTotal();
        } elseif (isset($data['total_amount'])) {
            $order->total_amount = $data['total_amount'];
            $order->save();
        }

        return response()->json($order->load('items.product'), 201);
    }

    /**
     * PATCH /api/orders/{id}/advance
     */
    public function apiAdvance(Order $order): JsonResponse
    {
        if (!$order->canAdvance()) {
            return response()->json(['message' => 'Cette commande ne peut pas être avancée.'], 422);
        }
        $order->advance();
        return response()->json($order);
    }

    /**
     * DELETE /api/orders/{id}
     */
    public function apiDestroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(['message' => 'Commande supprimée.']);
    }
}
