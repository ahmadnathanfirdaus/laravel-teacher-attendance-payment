@extends('layouts.app')

@section('title', 'Detail Jenjang Pendidikan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Jenjang Pendidikan: {{ $educationLevel->name }}</h5>
                    <div>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('education-levels.edit', $educationLevel) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('education-levels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama Singkat:</strong></td>
                                    <td>{{ $educationLevel->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Lengkap:</strong></td>
                                    <td>{{ $educationLevel->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Urutan Level:</strong></td>
                                    <td>{{ $educationLevel->level_order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($educationLevel->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($educationLevel->description)
                                <tr>
                                    <td><strong>Deskripsi:</strong></td>
                                    <td>{{ $educationLevel->description }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $educationLevel->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui:</strong></td>
                                    <td>{{ $educationLevel->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Guru dengan Jenjang Pendidikan Ini</h6>
                            @if($educationLevel->teachers->count() > 0)
                                <div class="list-group">
                                    @foreach($educationLevel->teachers->take(10) as $teacher)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $teacher->name }}</strong><br>
                                                <small class="text-muted">{{ $teacher->email }}</small>
                                            </div>
                                            @if($teacher->is_active)
                                                <span class="badge bg-success rounded-pill">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">Tidak Aktif</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($educationLevel->teachers->count() > 10)
                                        <div class="list-group-item text-center">
                                            <small class="text-muted">
                                                Dan {{ $educationLevel->teachers->count() - 10 }} guru lainnya...
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Total: {{ $educationLevel->teachers->count() }} guru
                                    </small>
                                </div>
                            @else
                                <p class="text-muted">Belum ada guru yang terdaftar dengan jenjang pendidikan ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
