<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'attachment_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Leave type constants
    const TYPE_SICK = 'sakit';
    const TYPE_PERMISSION = 'izin';
    const TYPE_ANNUAL = 'cuti_tahunan';
    const TYPE_MATERNITY = 'cuti_melahirkan';
    const TYPE_EMERGENCY = 'darurat';

    /**
     * Get the teacher that owns the leave request.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the admin who approved/rejected the request.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get available leave types.
     */
    public static function getLeaveTypes()
    {
        return [
            self::TYPE_SICK => 'Sakit',
            self::TYPE_PERMISSION => 'Izin',
            self::TYPE_ANNUAL => 'Cuti Tahunan',
            self::TYPE_MATERNITY => 'Cuti Melahirkan',
            self::TYPE_EMERGENCY => 'Darurat',
        ];
    }

    /**
     * Get available statuses.
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    /**
     * Calculate total days between start and end date.
     */
    public function calculateTotalDays()
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    /**
     * Check if the request is pending.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the request is approved.
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the request is rejected.
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get the status badge class for UI.
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
