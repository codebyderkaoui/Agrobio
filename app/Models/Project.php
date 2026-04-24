<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'progress',
        'deadline',
        'owner_id',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ── Helpers ───────────────────────────────────

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'Actif'    => 'green',
            'En pause' => 'amber',
            'Planifié' => 'teal',
            'Terminé'  => 'gray',
            default    => 'gray',
        };
    }

    public function getProgressFillClassAttribute(): string
    {
        return match($this->status) {
            'En pause' => 'pf-amber',
            'Planifié' => 'pf-teal',
            default    => '',
        };
    }

    /** Recalculate progress from tasks */
    public function recalculateProgress(): void
    {
        $total = $this->tasks()->count();
        if ($total === 0) { $this->progress = 0; $this->save(); return; }
        $done = $this->tasks()->where('is_done', true)->count();
        $this->progress = (int) round(($done / $total) * 100);
        $this->save();
    }
}
