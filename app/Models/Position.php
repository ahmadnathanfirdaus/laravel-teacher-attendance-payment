<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_allowance',
        'is_active',
    ];

    protected $casts = [
        'base_allowance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teachers for this position (many-to-many relationship).
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_positions')
                    ->withPivot([
                        'is_active',
                        'notes'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get active teachers for this position.
     */
    public function activeTeachers(): BelongsToMany
    {
        return $this->teachers()->wherePivot('is_active', true);
    }

    /**
     * Scope to get only active positions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
