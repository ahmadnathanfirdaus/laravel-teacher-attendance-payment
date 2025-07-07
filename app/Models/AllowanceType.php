<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllowanceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_amount',
        'is_active',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher allowances for this allowance type.
     */
    public function teacherAllowances(): HasMany
    {
        return $this->hasMany(TeacherAllowance::class);
    }

    /**
     * Get active teacher allowances for this allowance type.
     */
    public function activeTeacherAllowances(): HasMany
    {
        return $this->teacherAllowances()->where('is_active', true);
    }

    /**
     * Scope to get only active allowance types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
