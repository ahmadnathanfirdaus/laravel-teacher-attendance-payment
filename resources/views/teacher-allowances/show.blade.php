@extends('layouts.app')

@section('title', 'Detail Tunjangan Guru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Detail Tunjangan: {{ $teacherAllowance->teacher->name }} - {{ $teacherAllowance->allowanceType->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher-allowances.edit', $teacherAllowance) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('teacher-allowances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Informasi Guru</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Nama:</th>
                                            <td>{{ $teacherAllowance->teacher->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>NIP:</th>
                                            <td>{{ $teacherAllowance->teacher->nip }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $teacherAllowance->teacher->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan:</th>
                                            <td>{{ $teacherAllowance->teacher->position->name ?? 'Belum ditentukan' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Guru:</th>
                                            <td>
                                                @if($teacherAllowance->teacher->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Detail Tunjangan</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Jenis Tunjangan:</th>
                                            <td>{{ $teacherAllowance->allowanceType->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah:</th>
                                            <td>
                                                <strong class="text-primary">Rp {{ number_format($teacherAllowance->amount, 0, ',', '.') }}</strong>
                                                @if($teacherAllowance->amount != $teacherAllowance->allowanceType->default_amount)
                                                    <br><small class="text-muted">
                                                        Default: Rp {{ number_format($teacherAllowance->allowanceType->default_amount, 0, ',', '.') }}
                                                        (disesuaikan)
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Berlaku Mulai:</th>
                                            <td>{{ $teacherAllowance->effective_date ? $teacherAllowance->effective_date->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Tunjangan:</th>
                                            <td>
                                                @if($teacherAllowance->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Catatan:</th>
                                            <td>{{ $teacherAllowance->notes ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Tunjangan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary">
                                                    @php
                                                        $monthsActive = $teacherAllowance->effective_date
                                                            ? $teacherAllowance->effective_date->diffInMonths(now()) + 1
                                                            : 0;
                                                    @endphp
                                                    {{ $monthsActive }}
                                                </h4>
                                                <p class="text-muted">Bulan Aktif</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success">
                                                    Rp {{ number_format($teacherAllowance->amount * $monthsActive, 0, ',', '.') }}
                                                </h4>
                                                <p class="text-muted">Total Diterima</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info">
                                                    Rp {{ number_format($teacherAllowance->amount * 12, 0, ',', '.') }}
                                                </h4>
                                                <p class="text-muted">Proyeksi Tahunan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                @php
                                                    $percentage = $teacherAllowance->allowanceType->default_amount > 0
                                                        ? ($teacherAllowance->amount / $teacherAllowance->allowanceType->default_amount) * 100
                                                        : 100;
                                                @endphp
                                                <h4 class="text-warning">{{ number_format($percentage, 1) }}%</h4>
                                                <p class="text-muted">dari Default</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($teacherAllowance->teacher->allowances->count() > 1)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Tunjangan Lain dari Guru Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Jenis Tunjangan</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                        <th>Berlaku Mulai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($teacherAllowance->teacher->allowances->where('id', '!=', $teacherAllowance->id) as $otherAllowance)
                                                        <tr>
                                                            <td>{{ $otherAllowance->allowanceType->name }}</td>
                                                            <td>Rp {{ number_format($otherAllowance->amount, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if($otherAllowance->is_active)
                                                                    <span class="badge badge-success">Aktif</span>
                                                                @else
                                                                    <span class="badge badge-danger">Nonaktif</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $otherAllowance->effective_date ? $otherAllowance->effective_date->format('d/m/Y') : '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                Dibuat: {{ $teacherAllowance->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <i class="fas fa-edit mr-1"></i>
                                Diperbarui: {{ $teacherAllowance->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
