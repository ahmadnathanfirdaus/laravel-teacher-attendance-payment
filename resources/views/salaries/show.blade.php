@extends('layouts.app')

@section('title', 'Detail Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detail Gaji</h1>
            <div>
                @if(auth()->user()->role !== 'guru')
                    <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Informasi Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Nama Guru:</strong>
                            <p class="mb-0">{{ $salary->teacher->nama_lengkap }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>NIP:</strong>
                            <p class="mb-0">{{ $salary->teacher->nip }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Mata Pelajaran:</strong>
                            <p class="mb-0">{{ $salary->teacher->mata_pelajaran }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Periode:</strong>
                            <p class="mb-0">{{ $salary->bulan }} {{ $salary->tahun }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            @switch($salary->status)
                                @case('draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-warning">Approved</span>
                                    @break
                                @case('paid')
                                    <span class="badge bg-success">Paid</span>
                                    @break
                                @default
                                    <span class="badge bg-light">{{ ucfirst($salary->status) }}</span>
                            @endswitch
                        </div>
                        <div class="mb-3">
                            <strong>Tanggal Dibuat:</strong>
                            <p class="mb-0">{{ $salary->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Rincian Kehadiran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                            <h5 class="mb-1">{{ $salary->hari_kerja }}</h5>
                            <small class="text-muted">Hari Kerja</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h5 class="mb-1">{{ $salary->hari_hadir }}</h5>
                            <small class="text-muted">Hari Hadir</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                            <h5 class="mb-1">{{ $salary->hari_tidak_hadir }}</h5>
                            <small class="text-muted">Hari Tidak Hadir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Rincian Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Gaji Pokok:</span>
                    <span>Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tunjangan:</span>
                    <span>Rp {{ number_format($salary->tunjangan, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <small>Gaji per hari:</small>
                    <small>Rp {{ number_format($salary->gaji_pokok / $salary->hari_kerja, 0, ',', '.') }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted">
                    <small>Gaji berdasarkan kehadiran:</small>
                    <small>Rp {{ number_format(($salary->gaji_pokok / $salary->hari_kerja) * $salary->hari_hadir, 0, ',', '.') }}</small>
                </div>
                @if($salary->bonus > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Bonus:</span>
                        <span>+ Rp {{ number_format($salary->bonus, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($salary->potongan > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Potongan:</span>
                        <span>- Rp {{ number_format($salary->potongan, 0, ',', '.') }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total Gaji:</span>
                    <span class="text-primary">Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($salary->keterangan)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note me-2"></i>
                        Keterangan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $salary->keterangan }}</p>
                </div>
            </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-download me-2"></i>
                    Aksi
                </h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary w-100 mb-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak Slip Gaji
                </button>
                @if(auth()->user()->role !== 'guru')
                    <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning w-100">
                        <i class="fas fa-edit me-2"></i>Edit Gaji
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .card-header, nav, footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 0 !important;
    }
}
</style>
@endpush
@endsection
