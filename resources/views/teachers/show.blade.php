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
                    <tr>
                        <td><strong>Tunjangan:</strong></td>
                        <td>Rp {{ number_format($teacher->tunjangan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td><strong>Rp {{ number_format($teacher->gaji_pokok + $teacher->tunjangan, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

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
