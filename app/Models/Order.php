<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'client_name',
        'client_email',
        'client_phone',
        'delivery_mode',
        'delivery_address',
        'total_amount',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'float',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // ── Helpers ───────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->order_number, 4)) + 1 : 1;
        return 'CMD-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'Nouveau'   => 'os-new',
            'En cours'  => 'os-progress',
            'Livré'     => 'os-done',
            'Annulé'    => 'os-cancel',
            default     => '',
        };
    }

    public function recalculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }

    public function canAdvance(): bool
    {
        return in_array($this->status, ['Nouveau', 'En cours']);
    }

    public function advance(): void
    {
        $flow = ['Nouveau' => 'En cours', 'En cours' => 'Livré'];
        if (isset($flow[$this->status])) {
            $this->status = $flow[$this->status];
            $this->save();
        }
    }
}
