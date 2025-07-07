@extends('layouts.app')

@section('title', 'Manajemen Tunjangan Guru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Manajemen Tunjangan Guru
                    </h3>
                    <a href="{{ route('teacher-allowances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Tunjangan Guru
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
                                    <th width="20%">Nama Guru</th>
                                    <th width="20%">Jenis Tunjangan</th>
                                    <th width="15%">Nominal</th>
                                    <th width="15%">Periode</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teacherAllowances as $teacherAllowance)
                                    <tr>
                                        <td>{{ $loop->iteration + ($teacherAllowances->currentPage() - 1) * $teacherAllowances->perPage() }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $teacherAllowance->teacher->nama_lengkap }}</strong>
                                                <br>
                                                <small class="text-muted">NIP: {{ $teacherAllowance->teacher->nip }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ $teacherAllowance->allowanceType->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success text-white">
                                                Rp {{ number_format($teacherAllowance->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <small>
                                                    <strong>Mulai:</strong> {{ $teacherAllowance->effective_date->format('d/m/Y') }}
                                                    <br>
                                                    <strong>Berakhir:</strong>
                                                    {{ $teacherAllowance->end_date ? $teacherAllowance->end_date->format('d/m/Y') : 'Tidak terbatas' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($teacherAllowance->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher-allowances.show', $teacherAllowance) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher-allowances.edit', $teacherAllowance) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('teacher-allowances.toggle-status', $teacherAllowance) }}"
                                                      method="POST"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm {{ $teacherAllowance->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $teacherAllowance->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $teacherAllowance->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data tunjangan guru</h5>
                                                <p class="text-muted">Silakan tambah tunjangan guru pertama Anda.</p>
                                                <a href="{{ route('teacher-allowances.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Tunjangan Guru
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($teacherAllowances->hasPages())
                        <div class="mt-3">
                            {{ $teacherAllowances->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
