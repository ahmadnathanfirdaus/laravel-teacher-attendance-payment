@extends('layouts.app')

@section('title', 'Dashboard Admin - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">Dashboard Admin</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Total Guru</h5>
                        <h2 class="mb-0">{{ $totalTeachers }}</h2>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Absensi Hari Ini</h5>
                        <h2 class="mb-0">{{ $totalAttendanceToday }}</h2>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Gaji Bulan Ini</h5>
                        <h2 class="mb-0">{{ $totalSalariesThisMonth }}</h2>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Guru</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->teacher->nama_lengkap }}</td>
                                    <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                                    <td>{{ $attendance->jam_keluar ?? '-' }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('hadir')
                                                <span class="badge bg-success">Hadir</span>
                                                @break
                                            @case('tidak_hadir')
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                                @break
                                            @case('terlambat')
                                                <span class="badge bg-warning">Terlambat</span>
                                                @break
                                            @case('izin')
                                                <span class="badge bg-info">Izin</span>
                                                @break
                                            @case('sakit')
                                                <span class="badge bg-secondary">Sakit</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $attendance->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada data absensi hari ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
