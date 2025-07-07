@extends('layouts.app')

@section('title', 'Manajemen Jenis Tunjangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-tags mr-2"></i>
                        Manajemen Jenis Tunjangan
                    </h3>
                    <a href="{{ route('allowance-types.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jenis Tunjangan
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
                                    <th width="25%">Nama Jenis Tunjangan</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Nominal Default</th>
                                    <th width="10%">Penggunaan</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allowanceTypes as $allowanceType)
                                    <tr>
                                        <td>{{ $loop->iteration + ($allowanceTypes->currentPage() - 1) * $allowanceTypes->perPage() }}</td>
                                        <td>
                                            <strong>{{ $allowanceType->name }}</strong>
                                        </td>
                                        <td>{{ $allowanceType->description ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-success text-white">
                                                Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $allowanceType->teacherAllowances->count() }} guru
                                            </span>
                                        </td>
                                        <td>
                                            @if ($allowanceType->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('allowance-types.show', $allowanceType) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('allowance-types.edit', $allowanceType) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('allowance-types.toggle-status', $allowanceType) }}"
                                                      method="POST"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm {{ $allowanceType->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $allowanceType->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $allowanceType->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data jenis tunjangan</h5>
                                                <p class="text-muted">Silakan tambah jenis tunjangan pertama Anda.</p>
                                                <a href="{{ route('allowance-types.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Jenis Tunjangan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($allowanceTypes->hasPages())
                        <div class="mt-3">
                            {{ $allowanceTypes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
