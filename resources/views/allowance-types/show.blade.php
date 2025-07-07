@extends('layouts.app')

@section('title', 'Detail Jenis Tunjangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Detail Jenis Tunjangan: {{ $allowanceType->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('allowance-types.edit', $allowanceType) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('allowance-types.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Jenis Tunjangan:</th>
                                    <td>{{ $allowanceType->name }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Default:</th>
                                    <td>
                                        <strong class="text-primary">Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($allowanceType->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>{{ $allowanceType->description ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Penggunaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-primary">{{ $allowanceType->teacherAllowances->count() }}</h3>
                                                <p class="text-muted">Total Guru</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-success">{{ $allowanceType->teacherAllowances->where('is_active', true)->count() }}</h3>
                                                <p class="text-muted">Aktif</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-info">Rp {{ number_format($allowanceType->teacherAllowances->where('is_active', true)->sum('amount'), 0, ',', '.') }}</h3>
                                                <p class="text-muted">Total/Bulan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($allowanceType->teacherAllowances->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Guru yang Menerima Tunjangan Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Guru</th>
                                                        <th>NIP</th>
                                                        <th>Jumlah Tunjangan</th>
                                                        <th>Status</th>
                                                        <th>Diberikan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($allowanceType->teacherAllowances as $teacherAllowance)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $teacherAllowance->teacher->user->name }}</td>
                                                            <td>{{ $teacherAllowance->teacher->nip }}</td>
                                                            <td>
                                                                <strong>Rp {{ number_format($teacherAllowance->amount, 0, ',', '.') }}</strong>
                                                                @if($teacherAllowance->amount != $allowanceType->default_amount)
                                                                    <small class="text-muted">(disesuaikan)</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($teacherAllowance->is_active)
                                                                    <span class="badge bg-success">Aktif</span>
                                                                @else
                                                                    <span class="badge bg-danger">Nonaktif</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $teacherAllowance->created_at->format('d/m/Y') }}</td>
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
                                Dibuat: {{ $allowanceType->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <i class="fas fa-edit mr-1"></i>
                                Diperbarui: {{ $allowanceType->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
