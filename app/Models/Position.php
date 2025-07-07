<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get the teachers for this position.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get active teachers for this position.
     */
    public function activeTeachers(): HasMany
    {
        return $this->teachers()->where('is_active', true);
    }

    /**
     * Scope to get only active positions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
