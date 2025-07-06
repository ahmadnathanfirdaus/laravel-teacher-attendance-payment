@extends('layouts.app')

@section('title', 'Data Absensi - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Data Absensi</h1>
            @if(auth()->user()->role !== 'guru')
                <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Absensi
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('attendances.index') }}">
                    <div class="row">
                        @if(auth()->user()->role !== 'guru')
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Guru</label>
                                <select class="form-select" id="teacher_id" name="teacher_id">
                                    <option value="">Semua Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <option value="">Semua Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year">
                                    <option value="">Semua Tahun</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if(auth()->user()->role !== 'guru')
                                        <th>Nama Guru</th>
                                    @endif
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    @if(auth()->user()->role !== 'guru')
                                        <td>{{ $attendance->teacher->nama_lengkap }}</td>
                                    @endif
                                    <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
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
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role !== 'guru')
                                                <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(auth()->user()->role === 'admin')
                                                    <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                                onclick="return confirm('Yakin ingin menghapus data absensi ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $attendances->withQueryString()->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data absensi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
