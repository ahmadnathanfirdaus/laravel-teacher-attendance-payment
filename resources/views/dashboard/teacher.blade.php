@extends('layouts.app')

@section('title', 'Dashboard Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">Dashboard Guru</h1>
        <p class="text-muted">Selamat datang, {{ $teacher->nama_lengkap }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Absensi Bulan Ini</h5>
                        <h2 class="mb-0">{{ $myAttendanceThisMonth }} hari</h2>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">Gaji Bulan Ini</h5>
                        <h2 class="mb-0">
                            @if($mySalaryThisMonth)
                                Rp {{ number_format($mySalaryThisMonth->total_gaji, 0, ',', '.') }}
                            @else
                                Belum tersedia
                            @endif
                        </h2>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Absensi Hari Ini</h5>
                        <p class="mb-0">Lakukan absensi dengan kamera untuk mencatat kehadiran Anda</p>
                    </div>
                    <div>
                        <a href="{{ route('self-attendance.index') }}" class="btn btn-light">
                            <i class="fas fa-camera me-2"></i>
                            Buka Kamera Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>{{ $teacher->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap:</strong></td>
                        <td>{{ $teacher->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mata Pelajaran:</strong></td>
                        <td>{{ $teacher->mata_pelajaran }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Pokok:</strong></td>
                        <td>Rp {{ number_format($teacher->gaji_pokok, 0, ',', '.') }}</td>
                    </tr>
                    @if($teacher->position && $teacher->position->base_allowance > 0)
                    <tr>
                        <td><strong>Tunjangan Jabatan:</strong></td>
                        <td>Rp {{ number_format($teacher->position->base_allowance, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Total Tunjangan:</strong></td>
                        <td>Rp {{ number_format($totalAllowances, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Tunjangan</h5>
            </div>
            <div class="card-body">
                @if($teacher->position && $teacher->position->base_allowance > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <div>
                                <strong>Tunjangan Jabatan ({{ $teacher->position->name }})</strong>
                            </div>
                            <div class="text-success fw-bold">
                                Rp {{ number_format($positionAllowance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($activeAllowances->count() > 0)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Tunjangan Lainnya:</h6>
                        @foreach($activeAllowances as $allowance)
                            <div class="d-flex justify-content-between align-items-center p-2 mb-2 border rounded">
                                <div>
                                    <span>{{ $allowance->allowanceType->name }}</span>
                                    @if($allowance->notes)
                                        <small class="text-muted d-block">{{ $allowance->notes }}</small>
                                    @endif
                                </div>
                                <div class="text-primary fw-bold">
                                    Rp {{ number_format($allowance->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($totalAllowances == 0)
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada tunjangan yang ditetapkan
                    </div>
                @else
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total Tunjangan:</strong>
                            <strong class="text-success h5 mb-0">
                                Rp {{ number_format($totalAllowances, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Absensi Terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
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
                                    <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada data absensi.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
