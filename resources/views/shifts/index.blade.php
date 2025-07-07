@extends('layouts.app')

@section('title', 'Manajemen Shift')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Manajemen Shift
                    </h3>
                    <a href="{{ route('shifts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Shift
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Nama Shift</th>
                                    <th width="15%">Jam Mulai</th>
                                    <th width="15%">Jam Selesai</th>
                                    <th width="20%">Deskripsi</th>
                                    <th width="10%">Jumlah Guru</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shifts as $shift)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $shift->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $shift->start_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $shift->end_time }}
                                            </span>
                                        </td>
                                        <td>{{ $shift->description ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-secondary text-white">
                                                {{ $shift->teachers->count() }} guru
                                            </span>
                                        </td>
                                        <td>
                                            @if ($shift->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('shifts.show', $shift) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('shifts.edit', $shift) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('shifts.toggle-status', $shift) }}"
                                                      method="POST"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm {{ $shift->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $shift->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $shift->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data shift</h5>
                                                <p class="text-muted">Silakan tambah shift pertama Anda.</p>
                                                <a href="{{ route('shifts.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Shift
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
