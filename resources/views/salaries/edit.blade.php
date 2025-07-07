@extends('layouts.app')

@section('title', 'Edit Gaji - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Gaji</h1>
            <a href="{{ route('salaries.show', $salary) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Gaji
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('salaries.update', $salary) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Data gaji pokok, tunjangan, dan kehadiran tidak dapat diubah. Hanya bonus, potongan, status, dan keterangan yang dapat diedit.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bonus" class="form-label">Bonus</label>
                                <input type="number" class="form-control @error('bonus') is-invalid @enderror"
                                       id="bonus" name="bonus" value="{{ old('bonus', $salary->bonus) }}" min="0">
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan bonus dalam rupiah (tanpa titik/koma)</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="potongan" class="form-label">Potongan</label>
                                <input type="number" class="form-control @error('potongan') is-invalid @enderror"
                                       id="potongan" name="potongan" value="{{ old('potongan', $salary->potongan) }}" min="0">
                                @error('potongan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan potongan dalam rupiah (tanpa titik/koma)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Gaji</label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="draft" {{ old('status', $salary->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ old('status', $salary->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ old('status', $salary->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Draft:</strong> Gaji sedang disiapkan<br>
                            <strong>Approved:</strong> Gaji sudah disetujui<br>
                            <strong>Paid:</strong> Gaji sudah dibayarkan
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $salary->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Catatan tambahan untuk gaji ini (opsional)</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('salaries.show', $salary) }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin memperbarui data gaji ini?')">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info me-2"></i>
                    Informasi Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Guru:</strong>
                    <p class="mb-0">{{ $salary->teacher->user->name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Periode:</strong>
                    <p class="mb-0">{{ $salary->bulan }} {{ $salary->tahun }}</p>
                </div>
                <div class="mb-3">
                    <strong>Status Saat Ini:</strong>
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
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Rincian Gaji (Read Only)
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
                <div class="d-flex justify-content-between mb-2">
                    <span>Hari Kerja:</span>
                    <span>{{ $salary->hari_kerja }} hari</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Hari Hadir:</span>
                    <span>{{ $salary->hari_hadir }} hari</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2" id="current-bonus">
                    <span>Bonus Saat Ini:</span>
                    <span class="text-success">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" id="current-potongan">
                    <span>Potongan Saat Ini:</span>
                    <span class="text-danger">Rp {{ number_format($salary->potongan, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold" id="current-total">
                    <span>Total Gaji Saat Ini:</span>
                    <span class="text-primary">Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Preview Perhitungan
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
                <div class="d-flex justify-content-between mb-2">
                    <span>Bonus Baru:</span>
                    <span class="text-success" id="preview-bonus">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Potongan Baru:</span>
                    <span class="text-danger" id="preview-potongan">Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total Gaji Baru:</span>
                    <span class="text-primary" id="preview-total">Rp {{ number_format($salary->gaji_pokok + $salary->tunjangan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bonusInput = document.getElementById('bonus');
    const potonganInput = document.getElementById('potongan');
    const previewBonus = document.getElementById('preview-bonus');
    const previewPotongan = document.getElementById('preview-potongan');
    const previewTotal = document.getElementById('preview-total');

    const gajiPokok = {{ $salary->gaji_pokok }};
    const tunjangan = {{ $salary->tunjangan }};
    const hariKerja = {{ $salary->hari_kerja }};
    const hariHadir = {{ $salary->hari_hadir }};

    function updatePreview() {
        const bonus = parseInt(bonusInput.value) || 0;
        const potongan = parseInt(potonganInput.value) || 0;

        // Hitung gaji berdasarkan kehadiran
        const gajiPerHari = gajiPokok / hariKerja;
        const gajiPokokAktual = gajiPerHari * hariHadir;
        const totalGaji = gajiPokokAktual + tunjangan + bonus - potongan;

        previewBonus.textContent = 'Rp ' + bonus.toLocaleString('id-ID');
        previewPotongan.textContent = 'Rp ' + potongan.toLocaleString('id-ID');
        previewTotal.textContent = 'Rp ' + totalGaji.toLocaleString('id-ID');
    }

    bonusInput.addEventListener('input', updatePreview);
    potonganInput.addEventListener('input', updatePreview);

    // Initial calculation
    updatePreview();
});
</script>
@endpush
@endsection
