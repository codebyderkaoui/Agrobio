<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'ahmed@agrobio.ma')->first();
        $products = Product::all()->keyBy('name');

        $orders = [
            [
                'order_number' => 'CMD-098',
                'client_name'  => 'Marché Bio Casablanca',
                'delivery_mode'=> 'Express',
                'status'       => 'Nouveau',
                'days_ago'     => 7,
                'items'        => [
                    ['Tomates cerises BIO', 5],
                    ["Huile d'olive BIO", 3],
                    ['Miel de Thym Pure', 6],
                ],
            ],
            [
                'order_number' => 'CMD-097',
                'client_name'  => 'Restaurant Al Fassia',
                'delivery_mode'=> 'Standard',
                'status'       => 'En cours',
                'days_ago'     => 8,
                'items'        => [
                    ['Carottes de saison', 10],
                    ['Oignons rouges BIO', 8],
                ],
            ],
            [
                'order_number' => 'CMD-096',
                'client_name'  => 'Épicerie Verte Rabat',
                'delivery_mode'=> 'Standard',
                'status'       => 'Livré',
                'days_ago'     => 9,
                'items'        => [
                    ['Couscous moyen BIO', 12],
                    ['Farine complète BIO', 10],
                ],
            ],
            [
                'order_number' => 'CMD-095',
                'client_name'  => 'Coopérative Féminine Oujda',
                'delivery_mode'=> 'Retrait',
                'status'       => 'Livré',
                'days_ago'     => 10,
                'items'        => [
                    ['Miel de Romarin', 4],
                    ['Amandes BIO', 2],
                ],
            ],
            [
                'order_number' => 'CMD-094',
                'client_name'  => 'Superette Naturelle Fès',
                'delivery_mode'=> 'Express',
                'status'       => 'Livré',
                'days_ago'     => 11,
                'items'        => [
                    ["Huile d'olive vierge extra", 5],
                    ["Huile d'olive BIO", 8],
                    ['Olives vertes marinées', 5],
                ],
            ],
            [
                'order_number' => 'CMD-093',
                'client_name'  => 'Hotel Ibis Marrakech',
                'delivery_mode'=> 'Standard',
                'status'       => 'Nouveau',
                'days_ago'     => 12,
                'items'        => [
                    ['Tomates cerises BIO', 20],
                    ['Carottes de saison', 15],
                    ['Courgettes BIO', 10],
                    ['Oignons rouges BIO', 15],
                ],
            ],
            [
                'order_number' => 'CMD-092',
                'client_name'  => 'Café Argan Marrakech',
                'delivery_mode'=> 'Retrait',
                'status'       => 'Livré',
                'days_ago'     => 13,
                'items'        => [
                    ["Huile d'argan culinaire", 2],
                    ['Argan cosmétique BIO', 3],
                ],
            ],
            [
                'order_number' => 'CMD-091',
                'client_name'  => 'Naturalia Casablanca',
                'delivery_mode'=> 'Express',
                'status'       => 'Livré',
                'days_ago'     => 14,
                'items'        => [
                    ['Couscous moyen BIO', 20],
                    ["Huile d'olive BIO", 10],
                    ['Miel de Thym Pure', 8],
                    ['Amandes BIO', 5],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            // Skip if already seeded
            if (Order::where('order_number', $orderData['order_number'])->exists()) continue;

            $order = Order::create([
                'order_number'  => $orderData['order_number'],
                'client_name'   => $orderData['client_name'],
                'delivery_mode' => $orderData['delivery_mode'],
                'status'        => $orderData['status'],
                'total_amount'  => 0,
                'created_by'    => $admin?->id,
                'created_at'    => now()->subDays($orderData['days_ago']),
                'updated_at'    => now()->subDays($orderData['days_ago']),
            ]);

            $total = 0;
            foreach ($orderData['items'] as [$productName, $qty]) {
                $product = $products[$productName] ?? null;
                if (!$product) continue;

                $unitPrice = $product->price;
                $subtotal  = $unitPrice * $qty;
                $total    += $subtotal;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
        }
    }
}
