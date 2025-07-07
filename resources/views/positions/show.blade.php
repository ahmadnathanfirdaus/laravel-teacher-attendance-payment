@extends('layouts.app')

@section('title', 'Detail Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Detail Jabatan: {{ $position->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('positions.edit', $position) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('positions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Jabatan:</th>
                                    <td>{{ $position->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tunjangan:</th>
                                    <td>
                                        <strong class="text-primary">Rp {{ number_format($position->base_allowance, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($position->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>{{ $position->description ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Jabatan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-primary">{{ $position->teachers->count() }}</h3>
                                                <p class="text-muted">Guru</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-success">{{ $position->teachers->where('is_active', true)->count() }}</h3>
                                                <p class="text-muted">Aktif</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($position->teachers->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Guru dengan Jabatan Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>NIP</th>
                                                        <th>Email</th>
                                                        <th>Status</th>
                                                        <th>Bergabung</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($position->teachers as $teacher)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $teacher->user->name }}</td>
                                                            <td>{{ $teacher->nip }}</td>
                                                            <td>{{ $teacher->user->email }}</td>
                                                            <td>
                                                                @if($teacher->is_active)
                                                                    <span class="badge bg-success">Aktif</span>
                                                                @else
                                                                    <span class="badge bg-danger">Nonaktif</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $teacher->created_at->format('d/m/Y') }}</td>
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
                                Dibuat: {{ $position->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <i class="fas fa-edit mr-1"></i>
                                Diperbarui: {{ $position->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
