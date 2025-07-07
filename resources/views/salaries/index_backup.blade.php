@extends('layouts.app')

@section('title', 'Data Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Data Gaji</h1>
            @if(auth()->user()->role !== 'guru')
                <div class="btn-group">
                    <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Generate Gaji
                    </a>
                    <a href="{{ route('salaries.bulk-create') }}" class="btn btn-warning">
                        <i class="fas fa-cogs me-2"></i>Generate Semua Guru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats -->
@if(auth()->user()->role !== 'guru')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ $salaries->total() }}</div>
                <div class="text-muted small">Total Gaji</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ $salaries->where('bulan', now()->format('F'))->where('tahun', now()->year)->count() }}</div>
                <div class="text-muted small">Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ \App\Models\Teacher::where('is_active', true)->count() }}</div>
                <div class="text-muted small">Guru Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <div class="h5 mb-1">{{ \App\Models\AllowanceType::where('is_active', true)->count() }}</div>
                <div class="text-muted small">Tunjangan</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('salaries.index') }}">
                    <div class="row">
                        @if(auth()->user()->role !== 'guru')
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Guru</label>
                                <select class="form-select" id="teacher_id" name="teacher_id">
                                    <option value="">Semua Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year">
                                    <option value="">Semua Tahun</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($salaries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if(auth()->user()->role !== 'guru')
                                        <th>Nama Guru</th>
                                    @endif
                                    <th>Periode</th>
                                    <th>Gaji Pokok</th>
                                    <th>Tunjangan</th>
                                    <th>Bonus</th>
                                    <th>Potongan</th>
                                    <th>Total Gaji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaries as $salary)
                                <tr>
                                    @if(auth()->user()->role !== 'guru')
                                        <td>{{ $salary->teacher->user->name }}</td>
                                    @endif
                                    <td>{{ $salary->bulan }} {{ $salary->tahun }}</td>
                                    <td>Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($salary->tunjangan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($salary->potongan, 0, ',', '.') }}</td>
                                    <td>
                                        <strong>Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @switch($salary->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Draft</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-warning">Disetujui</span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success">Dibayar</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('salaries.show', $salary) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role !== 'guru')
                                                <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(auth()->user()->role === 'admin')
                                                    <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                                onclick="return confirm('Yakin ingin menghapus data gaji ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $salaries->withQueryString()->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data gaji.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
