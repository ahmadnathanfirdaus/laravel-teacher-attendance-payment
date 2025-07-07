<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAllowance extends Model
{
    protected $fillable = [
        'teacher_id',
        'allowance_type_id',
        'amount',
        'effective_date',
        'end_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher that owns this allowance.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the allowance type for this teacher allowance.
     */
    public function allowanceType(): BelongsTo
    {
        return $this->belongsTo(AllowanceType::class);
    }

    /**
     * Scope to get only active allowances.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get current allowances (effective and not ended).
     */
    public function scopeCurrent($query)
    {
        return $query->where('effective_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Check if allowance is currently effective.
     */
    public function isCurrentlyEffective(): bool
    {
        $now = now()->toDateString();
        return $this->effective_date <= $now &&
               ($this->end_date === null || $this->end_date >= $now);
    }
}
