@extends('layouts.app')

@section('title', 'Detail Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detail Guru</h1>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto Profil</h5>
            </div>
            <div class="card-body text-center">
                @if($teacher->photo_path)
                    <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="Foto Profil" class="img-fluid rounded-circle" style="max-width: 200px;">
                @else
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                        <i class="fas fa-user fa-4x text-muted"></i>
                    </div>
                    <p class="text-muted mt-2">Tidak ada foto</p>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Jabatan & Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Jabatan:</strong></td>
                        <td>
                            @if($teacher->position)
                                {{ $teacher->position->name }}
                                <br><small class="text-muted">Level {{ $teacher->position->level }}</small>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>NIP:</strong></td>
                        <td>{{ $teacher->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap:</strong></td>
                        <td>{{ $teacher->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $teacher->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $teacher->no_telepon }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin:</strong></td>
                        <td>{{ ucfirst($teacher->jenis_kelamin) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tempat, Tanggal Lahir:</strong></td>
                        <td>{{ $teacher->tempat_lahir }}, {{ $teacher->tanggal_lahir->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan Terakhir:</strong></td>
                        <td>{{ $teacher->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenjang Pendidikan:</strong></td>
                        <td>
                            @if($teacher->educationLevel)
                                <span class="badge bg-info">{{ $teacher->educationLevel->name }}</span>
                                <br><small class="text-muted">{{ $teacher->educationLevel->full_name }}</small>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Mata Pelajaran:</strong></td>
                        <td>{{ $teacher->mata_pelajaran }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Masuk:</strong></td>
                        <td>{{ $teacher->tanggal_masuk->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Gaji</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
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
                        <td><strong>Total Tunjangan Lainnya:</strong></td>
                        <td>Rp {{ number_format($teacher->teacherAllowances->where('is_active', true)->sum('amount'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Penghasilan:</strong></td>
                        <td><strong>Rp {{ number_format($teacher->gaji_pokok + ($teacher->position ? $teacher->position->base_allowance : 0) + $teacher->teacherAllowances->where('is_active', true)->sum('amount'), 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
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
                                Rp {{ number_format($teacher->position->base_allowance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endif

                @if($teacher->teacherAllowances->where('is_active', true)->count() > 0)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Tunjangan Lainnya:</h6>
                        @foreach($teacher->teacherAllowances->where('is_active', true) as $allowance)
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

                @if(!$teacher->position || $teacher->position->base_allowance == 0)
                    @if($teacher->teacherAllowances->where('is_active', true)->count() == 0)
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada tunjangan yang ditetapkan
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Shift & Hari Kerja</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Shift Mengajar:</strong>
                        @if($teacher->shifts->count() > 0)
                            <ul class="list-unstyled mt-2">
                                @foreach($teacher->shifts as $shift)
                                    <li>
                                        <span class="badge bg-primary">{{ $shift->name }}</span>
                                        <br><small class="text-muted">{{ $shift->start_time }} - {{ $shift->end_time }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Belum ada shift</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Hari Kerja:</strong>
                        @if($teacher->working_days)
                            <div class="mt-2">
                                @foreach($teacher->working_days as $day)
                                    <span class="badge bg-secondary me-1">{{ ucfirst($day) }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ditentukan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($teacher->teacherAllowances->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tunjangan Aktif</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Jenis Tunjangan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Berlaku Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teacher->teacherAllowances as $allowance)
                                <tr>
                                    <td>{{ $allowance->allowanceType->name }}</td>
                                    <td>Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($allowance->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $allowance->effective_date ? $allowance->effective_date->format('d/m/Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Akun Pengguna</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $teacher->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $teacher->user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>{{ ucfirst($teacher->user->role) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Yakin ingin menonaktifkan guru ini?')">
                    <i class="fas fa-ban me-2"></i>Nonaktifkan
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
