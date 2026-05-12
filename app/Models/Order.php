<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $casts = [
        'total_amount' => 'float',
    ];

    // ── Status flow ───────────────────────────────────────────────────────────

    const STATUS_FLOW = ['Nouveau', 'En cours', 'Livré'];

    public function canAdvance(): bool
    {
        return in_array($this->status, ['Nouveau', 'En cours']);
    }

    public function advance(): void
    {
        $idx = array_search($this->status, self::STATUS_FLOW);
        if ($idx !== false && isset(self::STATUS_FLOW[$idx + 1])) {
            $this->status = self::STATUS_FLOW[$idx + 1];
            $this->save();
        }
    }

    // ── Order number generator ────────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $last = static::withTrashed()
            ->selectRaw("MAX(CAST(SUBSTRING(order_number, 5) AS UNSIGNED)) as max_num")
            ->value('max_num') ?? 0;

        return 'CMD-' . ($last + 1);
    }

    // ── Total recalculation ───────────────────────────────────────────────────

    public function recalculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'Nouveau'  => 'os-new',
            'En cours' => 'os-progress',
            'Livré'    => 'os-done',
            'Annulé'   => 'os-cancel',
            default    => 'os-new',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}