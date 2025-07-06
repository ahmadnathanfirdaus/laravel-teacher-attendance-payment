<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
        'photo_masuk',
        'photo_keluar',
        'location',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i',
        'jam_keluar' => 'datetime:H:i',
    ];

    /**
     * Get the teacher that owns the attendance.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
