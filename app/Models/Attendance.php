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
        'shift_id',
        'expected_time_in',
        'expected_time_out',
        'is_late',
        'late_minutes',
        'is_early_leave',
        'early_leave_minutes',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i',
        'jam_keluar' => 'datetime:H:i',
        'expected_time_in' => 'datetime:H:i',
        'expected_time_out' => 'datetime:H:i',
        'is_late' => 'boolean',
        'is_early_leave' => 'boolean',
    ];

    /**
     * Get the teacher that owns the attendance.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the shift for this attendance.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Check if attendance is within shift time.
     */
    public function isWithinShiftTime(): bool
    {
        if (!$this->shift) {
            return true; // No shift restriction
        }

        if ($this->jam_masuk) {
            $checkIn = $this->jam_masuk->format('H:i');
            $shiftStart = $this->shift->start_time->format('H:i');
            $shiftEnd = $this->shift->end_time->format('H:i');

            return $checkIn >= $shiftStart && $checkIn <= $shiftEnd;
        }

        return false;
    }

    /**
     * Calculate late minutes.
     */
    public function calculateLateMinutes(): int
    {
        if (!$this->jam_masuk || !$this->expected_time_in) {
            return 0;
        }

        $actualTime = $this->jam_masuk;
        $expectedTime = $this->expected_time_in;

        if ($actualTime > $expectedTime) {
            return $actualTime->diffInMinutes($expectedTime);
        }

        return 0;
    }

    /**
     * Calculate early leave minutes.
     */
    public function calculateEarlyLeaveMinutes(): int
    {
        if (!$this->jam_keluar || !$this->expected_time_out) {
            return 0;
        }

        $actualTime = $this->jam_keluar;
        $expectedTime = $this->expected_time_out;

        if ($actualTime < $expectedTime) {
            return $expectedTime->diffInMinutes($actualTime);
        }

        return 0;
    }
}
