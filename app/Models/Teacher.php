<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'alamat',
        'no_telepon',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'pendidikan_terakhir',
        'mata_pelajaran',
        'tanggal_masuk',
        'gaji_pokok',
        'tunjangan',
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances for the teacher.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the salaries for the teacher.
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
}
