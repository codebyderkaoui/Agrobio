<?php
// ============================================================
// app/Http/Controllers/FarmController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\Farm;

class FarmController extends Controller
{
    /** Web view */
    public function index()
    {
        $farms = Farm::with(['products' => fn($q) => $q->orderBy('is_active', 'desc')->orderBy('name')])
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        return view('farms.index', compact('farms'));
    }

    /** JSON API */
    public function apiIndex()
    {
        return response()->json(Farm::with('products')->get());
    }
}
