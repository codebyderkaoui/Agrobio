<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Farm;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $farms = Farm::pluck('id', 'name');

        $products = [
            // Ferme El Mansouri
            ['farm' => 'Ferme El Mansouri', 'name' => 'Tomates cerises BIO',      'category' => 'Légumes',         'price' => 18,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🍅', 'bg_class' => 'bg4', 'stock_quantity' => 120],
            ['farm' => 'Ferme El Mansouri', 'name' => 'Carottes de saison',        'category' => 'Légumes',         'price' => 12,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🥕', 'bg_class' => 'bg6', 'stock_quantity' => 200],
            ['farm' => 'Ferme El Mansouri', 'name' => 'Courgettes BIO',            'category' => 'Légumes',         'price' => 15,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🥒', 'bg_class' => 'bg7', 'stock_quantity' => 80],
            ['farm' => 'Ferme El Mansouri', 'name' => 'Oignons rouges BIO',        'category' => 'Légumes',         'price' => 10,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🧅', 'bg_class' => 'bg4', 'stock_quantity' => 300],
            ['farm' => 'Ferme El Mansouri', 'name' => 'Poivrons tricolores',       'category' => 'Légumes',         'price' => 22,  'unit' => 'kg',    'stock_status' => 'low', 'emoji' => '🫑', 'bg_class' => 'bg3', 'stock_quantity' => 15],
            ['farm' => 'Ferme El Mansouri', 'name' => 'Pommes de terre BIO',       'category' => 'Légumes',         'price' => 9,   'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🥔', 'bg_class' => 'bg5', 'stock_quantity' => 400],

            // Domaine Souissi
            ['farm' => 'Domaine Souissi',   'name' => "Huile d'olive BIO",         'category' => 'Huiles & Épices', 'price' => 85,  'unit' => '500ml', 'stock_status' => 'ok',  'emoji' => '🫒', 'bg_class' => 'bg2', 'stock_quantity' => 60],
            ['farm' => 'Domaine Souissi',   'name' => "Huile d'olive vierge extra",'category' => 'Huiles & Épices', 'price' => 110, 'unit' => '1L',    'stock_status' => 'ok',  'emoji' => '🫒', 'bg_class' => 'bg5', 'stock_quantity' => 40],
            ['farm' => 'Domaine Souissi',   'name' => 'Citrons BIO',               'category' => 'Fruits',          'price' => 8,   'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🍋', 'bg_class' => 'bg3', 'stock_quantity' => 150],
            ['farm' => 'Domaine Souissi',   'name' => 'Olives vertes marinées',    'category' => 'Huiles & Épices', 'price' => 35,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🫒', 'bg_class' => 'bg6', 'stock_quantity' => 50],

            // Coopérative Idriss
            ['farm' => 'Coopérative Idriss','name' => 'Miel de Thym Pure',         'category' => 'Miel & Confiture','price' => 95,  'unit' => '250g',  'stock_status' => 'low', 'emoji' => '🍯', 'bg_class' => 'bg5', 'stock_quantity' => 8],
            ['farm' => 'Coopérative Idriss','name' => 'Miel de Romarin',           'category' => 'Miel & Confiture','price' => 85,  'unit' => '250g',  'stock_status' => 'ok',  'emoji' => '🍯', 'bg_class' => 'bg2', 'stock_quantity' => 25],
            ['farm' => 'Coopérative Idriss','name' => 'Huile d\'argan culinaire',  'category' => 'Huiles & Épices', 'price' => 130, 'unit' => '250ml', 'stock_status' => 'ok',  'emoji' => '🌰', 'bg_class' => 'bg5', 'stock_quantity' => 30],
            ['farm' => 'Coopérative Idriss','name' => 'Amandes BIO',               'category' => 'Céréales',        'price' => 65,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🥜', 'bg_class' => 'bg1', 'stock_quantity' => 45],

            // Ferme Benali
            ['farm' => 'Ferme Benali',      'name' => 'Couscous moyen BIO',        'category' => 'Céréales',        'price' => 30,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🌾', 'bg_class' => 'bg6', 'stock_quantity' => 100],
            ['farm' => 'Ferme Benali',      'name' => 'Farine complète BIO',       'category' => 'Céréales',        'price' => 18,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🌾', 'bg_class' => 'bg1', 'stock_quantity' => 80],
            ['farm' => 'Ferme Benali',      'name' => 'Orge BIO',                  'category' => 'Céréales',        'price' => 22,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🌿', 'bg_class' => 'bg1', 'stock_quantity' => 60],
            ['farm' => 'Ferme Benali',      'name' => 'Pois chiches BIO',          'category' => 'Céréales',        'price' => 25,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🫘', 'bg_class' => 'bg2', 'stock_quantity' => 70],

            // Moulin Berbère
            ['farm' => 'Moulin Berbère',    'name' => 'Confiture figue BIO',       'category' => 'Miel & Confiture','price' => 42,  'unit' => '250g',  'stock_status' => 'low', 'emoji' => '🫙', 'bg_class' => 'bg2', 'stock_quantity' => 12],
            ['farm' => 'Moulin Berbère',    'name' => 'Confiture rose de Damas',   'category' => 'Miel & Confiture','price' => 55,  'unit' => '250g',  'stock_status' => 'ok',  'emoji' => '🌹', 'bg_class' => 'bg4', 'stock_quantity' => 20],
            ['farm' => 'Moulin Berbère',    'name' => 'Pommes Golden BIO',         'category' => 'Fruits',          'price' => 25,  'unit' => 'kg',    'stock_status' => 'ok',  'emoji' => '🍎', 'bg_class' => 'bg7', 'stock_quantity' => 90],

            // Coopérative Féminine
            ['farm' => 'Coopérative Féminine','name' => 'Argan cosmétique BIO',   'category' => 'Huiles & Épices', 'price' => 120, 'unit' => '100ml', 'stock_status' => 'ok',  'emoji' => '🌰', 'bg_class' => 'bg3', 'stock_quantity' => 35],
            ['farm' => 'Coopérative Féminine','name' => 'Harissa maison',          'category' => 'Huiles & Épices', 'price' => 50,  'unit' => 'kg',    'stock_status' => 'out', 'emoji' => '🌶', 'bg_class' => 'bg4', 'stock_quantity' => 0],
            ['farm' => 'Coopérative Féminine','name' => 'Ghee BIO (Smen)',        'category' => 'Huiles & Épices', 'price' => 75,  'unit' => '250g',  'stock_status' => 'ok',  'emoji' => '🧈', 'bg_class' => 'bg2', 'stock_quantity' => 22],
        ];

        foreach ($products as $p) {
            $farmId = $farms[$p['farm']] ?? null;
            if (!$farmId) continue;
            Product::firstOrCreate(
                ['name' => $p['name'], 'farm_id' => $farmId],
                [
                    'farm_id'        => $farmId,
                    'category'       => $p['category'],
                    'price'          => $p['price'],
                    'unit'           => $p['unit'],
                    'stock_status'   => $p['stock_status'],
                    'stock_quantity' => $p['stock_quantity'],
                    'emoji'          => $p['emoji'],
                    'bg_class'       => $p['bg_class'],
                    'is_active'      => true,
                ]
            );
        }
    }
}
