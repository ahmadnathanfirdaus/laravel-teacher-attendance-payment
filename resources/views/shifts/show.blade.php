@extends('layouts.app')

@section('title', 'Detail Shift')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Detail Shift: {{ $shift->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Shift:</th>
                                    <td>{{ $shift->name }}</td>
                                </tr>
                                <tr>
                                    <th>Jam Mulai:</th>
                                    <td>{{ $shift->start_time }}</td>
                                </tr>
                                <tr>
                                    <th>Jam Selesai:</th>
                                    <td>{{ $shift->end_time }}</td>
                                </tr>
                                <tr>
                                    <th>Durasi:</th>
                                    <td>
                                        @php
                                            $start = \Carbon\Carbon::parse($shift->start_time);
                                            $end = \Carbon\Carbon::parse($shift->end_time);
                                            $duration = $start->diffInHours($end);
                                        @endphp
                                        {{ $duration }} jam
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($shift->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deskripsi:</th>
                                    <td>{{ $shift->description ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Shift</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-primary">{{ $shift->teachers->count() }}</h3>
                                                <p class="text-muted">Guru Menggunakan</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-info">{{ $shift->attendances->count() }}</h3>
                                                <p class="text-muted">Total Absensi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($shift->teachers->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Guru yang Menggunakan Shift Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>NIP</th>
                                                        <th>Jabatan</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($shift->teachers as $teacher)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $teacher->name }}</td>
                                                            <td>{{ $teacher->nip }}</td>
                                                            <td>{{ $teacher->position->name ?? '-' }}</td>
                                                            <td>
                                                                @if($teacher->is_active)
                                                                    <span class="badge badge-success">Aktif</span>
                                                                @else
                                                                    <span class="badge badge-danger">Nonaktif</span>
                                                                @endif
                                                            </td>
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
                                Dibuat: {{ $shift->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <i class="fas fa-edit mr-1"></i>
                                Diperbarui: {{ $shift->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
