<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Farm;
use App\Http\Requests\Api\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    // ── Web view ──────────────────────────────────

    public function index()
    {
        $farms      = Farm::where('is_active', true)->get();
        $categories = Product::distinct()->pluck('category')->sort()->values();
        return view('products.index', compact('farms', 'categories'));
    }

    // ── REST API ──────────────────────────────────

    /**
     * GET /api/products
     * Query params: search, category, stock_status, sort, per_page
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $q = Product::with('farm')->active();

        if ($s = $request->search) {
            $q->where(function ($query) use ($s) {
                $query->where('name', 'like', "%{$s}%")
                      ->orWhereHas('farm', fn($f) => $f->where('name', 'like', "%{$s}%"));
            });
        }

        if ($cat = $request->category) {
            $q->where('category', $cat);
        }

        if ($stock = $request->stock_status) {
            $q->where('stock_status', $stock);
        }

        match ($request->sort) {
            'price-asc'  => $q->orderBy('price'),
            'price-desc' => $q->orderByDesc('price'),
            default      => $q->orderBy('name'),
        };

        $products = $q->paginate($request->integer('per_page', 24));

        return response()->json($products);
    }

    /**
     * POST /api/products
     */
    public function apiStore(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json($product->load('farm'), 201);
    }

    /**
     * PUT /api/products/{id}
     */
    public function apiUpdate(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'farm_id'       => 'sometimes|exists:farms,id',
            'name'          => 'sometimes|string|max:120',
            'description'   => 'nullable|string',
            'category'      => 'sometimes|string|max:60',
            'price'         => 'sometimes|numeric|min:0',
            'unit'          => 'sometimes|string|max:30',
            'stock_status'  => 'sometimes|in:ok,low,out',
            'stock_quantity'=> 'sometimes|integer|min:0',
            'is_active'     => 'sometimes|boolean',
        ]);

        $product->update($data);

        return response()->json($product->load('farm'));
    }

    /**
     * DELETE /api/products/{id}
     */
    public function apiDestroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }
}
