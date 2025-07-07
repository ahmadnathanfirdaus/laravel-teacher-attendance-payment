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
    <div class="col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Jenjang Pendidikan</h5>
                        <h2 class="mb-0">{{ $totalEducationLevels }}</h2>
                        <small>level tersedia</small>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('education-levels.index') }}" class="text-white text-decoration-none">
                        <small><i class="fas fa-external-link-alt me-1"></i>Kelola Jenjang</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Jenis Tunjangan</h5>
                        <h2 class="mb-0">{{ $totalAllowanceTypes }}</h2>
                        <small>tunjangan tersedia</small>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('allowance-types.index') }}" class="text-white text-decoration-none">
                        <small><i class="fas fa-external-link-alt me-1"></i>Kelola Tunjangan</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Pengajuan Cuti Pending</h5>
                        <h2 class="mb-0">{{ $pendingLeaveRequests }}</h2>
                        <small>menunggu persetujuan</small>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('leave-requests.index') }}?status=pending" class="text-white text-decoration-none">
                        <small><i class="fas fa-external-link-alt me-1"></i>Kelola Cuti</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Cuti Disetujui Bulan Ini</h5>
                        <h2 class="mb-0">{{ $approvedLeaveRequestsThisMonth }}</h2>
                        <small>pengajuan disetujui</small>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('leave-requests.index') }}?status=approved" class="text-white text-decoration-none">
                        <small><i class="fas fa-external-link-alt me-1"></i>Lihat Laporan</small>
                    </a>
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

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Pengajuan Cuti Terbaru (Pending)</h5>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentLeaveRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Guru</th>
                                    <th>Jenis Cuti</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Alasan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeaveRequests as $leaveRequest)
                                <tr>
                                    <td>{{ $leaveRequest->teacher->user->name }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ \App\Models\LeaveRequest::getLeaveTypes()[$leaveRequest->leave_type] }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $leaveRequest->start_date->format('d M Y') }}
                                        @if($leaveRequest->start_date->format('Y-m-d') !== $leaveRequest->end_date->format('Y-m-d'))
                                            <br><small class="text-muted">s/d {{ $leaveRequest->end_date->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $leaveRequest->total_days }} hari</td>
                                    <td>{{ Str::limit($leaveRequest->reason, 50) }}</td>
                                    <td>
                                        <a href="{{ route('leave-requests.show', $leaveRequest) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Tidak ada pengajuan cuti yang pending.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
