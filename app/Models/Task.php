<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'category',
        'priority',
        'column',
        'due_date',
        'assigned_to',
        'created_by',
        'is_done',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'is_done'  => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ───────────────────────────────────

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high' => 'coral',
            'med'  => 'amber',
            'low'  => 'teal',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'high' => 'Haute',
            'med'  => 'Moy.',
            'low'  => 'Basse',
            default => '—',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        $map = [
            'Stock'            => 'amber',
            'Livraison'        => 'teal',
            'Dev'              => 'teal',
            'Commercial'       => 'coral',
            'Rapport'          => 'amber',
            'Qualité'          => 'coral',
            'Légal'            => 'green',
            'Catalogue'        => 'green',
            'Approvisionnement'=> 'coral',
            'DevOps'           => 'amber',
            'Design'           => 'green',
            'Backend'          => 'teal',
            'QA'               => 'green',
        ];
        return $map[$this->category] ?? 'gray';
    }

    public function isOverdue(): bool
    {
        return !$this->is_done && $this->due_date && $this->due_date->isPast();
    }

    public function getDueLabelAttribute(): string
    {
        if (!$this->due_date) return 'À définir';
        return $this->due_date->translatedFormat('d M');
    }
}
