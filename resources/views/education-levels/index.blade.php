@extends('layouts.app')

@section('title', 'Manajemen Jenjang Pendidikan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Manajemen Jenjang Pendidikan
                    </h3>
                    <a href="{{ route('education-levels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jenjang Pendidikan
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
                                    <th width="15%">Nama</th>
                                    <th width="25%">Nama Lengkap</th>
                                    <th width="25%">Deskripsi</th>
                                    <th width="10%">Urutan</th>
                                    <th width="10%">Guru</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($educationLevels as $educationLevel)
                                    <tr>
                                        <td>{{ $loop->iteration + ($educationLevels->currentPage() - 1) * $educationLevels->perPage() }}</td>
                                        <td>
                                            <strong>{{ $educationLevel->name }}</strong>
                                        </td>
                                        <td>{{ $educationLevel->full_name }}</td>
                                        <td>{{ $educationLevel->description ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $educationLevel->level_order }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-white">
                                                {{ $educationLevel->teachers->count() }} guru
                                            </span>
                                        </td>
                                        <td>
                                            @if ($educationLevel->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('education-levels.show', $educationLevel) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('education-levels.edit', $educationLevel) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('education-levels.toggle-status', $educationLevel) }}"
                                                      method="POST"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm {{ $educationLevel->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $educationLevel->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $educationLevel->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                @if($educationLevel->teachers->count() == 0)
                                                    <form action="{{ route('education-levels.destroy', $educationLevel) }}"
                                                          method="POST"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Yakin ingin menghapus jenjang pendidikan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data jenjang pendidikan</h5>
                                                <p class="text-muted">Silakan tambah jenjang pendidikan pertama Anda.</p>
                                                <a href="{{ route('education-levels.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Jenjang Pendidikan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($educationLevels->hasPages())
                        <div class="mt-3">
                            {{ $educationLevels->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
