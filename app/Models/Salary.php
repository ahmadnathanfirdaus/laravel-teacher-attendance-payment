<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'teacher_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'bonus',
        'potongan',
        'hari_kerja',
        'hari_hadir',
        'hari_tidak_hadir',
        'total_gaji',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'bonus' => 'decimal:2',
        'potongan' => 'decimal:2',
        'total_gaji' => 'decimal:2',
        'tahun' => 'integer',
        'hari_kerja' => 'integer',
        'hari_hadir' => 'integer',
        'hari_tidak_hadir' => 'integer',
    ];

    /**
     * Get the teacher that owns the salary.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
