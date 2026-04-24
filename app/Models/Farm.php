<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner',
        'city',
        'region',
        'email',
        'phone',
        'emoji',
        'bio_certified',
        'contract_expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bio_certified'       => 'boolean',
            'is_active'           => 'boolean',
            'contract_expires_at' => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    // ── Helpers ───────────────────────────────────

    public function getTotalRevenueAttribute(): float
    {
        return $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->sum('order_items.subtotal');
    }

    public function contractExpiresLabel(): string
    {
        if (!$this->contract_expires_at) return '—';
        $days = now()->diffInDays($this->contract_expires_at, false);
        if ($days < 0)  return 'Expiré';
        if ($days < 30) return "Expire dans {$days}j";
        return $this->contract_expires_at->format('d/m/Y');
    }
}
