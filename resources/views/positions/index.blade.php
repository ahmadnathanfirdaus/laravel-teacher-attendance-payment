@extends('layouts.app')

@section('title', 'Manajemen Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-briefcase mr-2"></i>
                        Manajemen Jabatan
                    </h3>
                    <a href="{{ route('positions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jabatan
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
                                    <th width="25%">Nama Jabatan</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Tunjangan Dasar</th>
                                    <th width="10%">Jumlah Guru</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($positions as $position)
                                    <tr>
                                        <td>{{ $loop->iteration + ($positions->currentPage() - 1) * $positions->perPage() }}</td>
                                        <td>
                                            <strong>{{ $position->name }}</strong>
                                        </td>
                                        <td>{{ $position->description ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-success text-white">
                                                Rp {{ number_format($position->base_allowance, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $position->teachers->count() }} guru
                                            </span>
                                        </td>
                                        <td>
                                            @if ($position->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('positions.show', $position) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('positions.edit', $position) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('positions.toggle-status', $position) }}"
                                                      method="POST"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm {{ $position->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $position->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $position->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data jabatan</h5>
                                                <p class="text-muted">Silakan tambah jabatan pertama Anda.</p>
                                                <a href="{{ route('positions.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Jabatan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($positions->hasPages())
                        <div class="mt-3">
                            {{ $positions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
