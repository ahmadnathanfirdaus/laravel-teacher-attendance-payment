<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the teachers that belong to this shift.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_shifts')
                    ->withPivot([
                        'days',
                        'effective_date',
                        'end_date',
                        'notes',
                        'is_active'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get active teachers for this shift.
     */
    public function activeTeachers(): BelongsToMany
    {
        return $this->teachers()->wherePivot('is_active', true);
    }

    /**
     * Get attendances for this shift.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope to get only active shifts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if shift is currently active (based on time).
     */
    public function isCurrentlyActive(): bool
    {
        $now = now()->format('H:i');
        return $now >= $this->start_time->format('H:i') &&
               $now <= $this->end_time->format('H:i');
    }
}
