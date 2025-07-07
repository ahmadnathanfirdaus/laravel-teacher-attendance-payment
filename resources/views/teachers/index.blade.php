@extends('layouts.app')

@section('title', 'Data Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Data Guru</h1>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Guru
                </a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($teachers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenjang</th>
                                    <th>Mata Pelajaran</th>
                                    <th>No. Telepon</th>
                                    <th>Gaji Pokok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->nip }}</td>
                                    <td>{{ $teacher->nama_lengkap }}</td>
                                    <td>
                                        @if($teacher->educationLevel)
                                            <span class="badge bg-info">{{ $teacher->educationLevel->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $teacher->mata_pelajaran }}</td>
                                    <td>{{ $teacher->no_telepon }}</td>
                                    <td>Rp {{ number_format($teacher->gaji_pokok, 0, ',', '.') }}</td>
                                    <td>
                                        @if($teacher->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                            onclick="return confirm('Yakin ingin menonaktifkan guru ini?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $teachers->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data guru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
