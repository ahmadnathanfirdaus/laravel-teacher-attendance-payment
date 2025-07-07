<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    protected $fillable = [
        'name',
        'full_name',
        'description',
        'level_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level_order' => 'integer',
    ];

    /**
     * Get the teachers for this education level.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Scope to get only active education levels.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by level order.
     */
    public function scopeOrderedByLevel($query)
    {
        return $query->orderBy('level_order');
    }

    /**
     * Get formatted display name.
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->full_name . ')';
    }
}
