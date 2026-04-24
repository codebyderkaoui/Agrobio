<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'name',
        'description',
        'category',
        'price',
        'unit',
        'stock_status',
        'stock_quantity',
        'emoji',
        'bg_class',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'float',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function farm(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', '!=', 'out');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ── Helpers ───────────────────────────────────

    public function getStockLabelAttribute(): string
    {
        return match($this->stock_status) {
            'ok'  => 'En stock',
            'low' => 'Stock bas',
            'out' => 'Rupture',
            default => '—',
        };
    }

    public function getStockBadgeClassAttribute(): string
    {
        return match($this->stock_status) {
            'ok'  => 'sb-ok',
            'low' => 'sb-low',
            'out' => 'sb-out',
            default => '',
        };
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock_status !== 'out';
    }
}
