@extends('layouts.app')

@section('title', 'Generate Gaji Semua Guru - YAKIIN')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Generate Gaji Semua Guru</h1>
                <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Fitur ini akan generate gaji untuk semua guru aktif sekaligus</li>
                            <li>Sistem akan melewati guru yang sudah memiliki data gaji untuk periode yang sama</li>
                            <li>Gaji akan dihitung berdasarkan data absensi dan tunjangan masing-masing guru</li>
                            <li>Pastikan data absensi sudah lengkap sebelum generate gaji</li>
                        </ul>
                    </div>

                    <form action="{{ route('salaries.bulk-store') }}" method="POST"
                        onsubmit="return confirmBulkGenerate()">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('bulan') is-invalid @enderror" id="bulan"
                                        name="bulan" required>
                                        <option value="">Pilih Bulan</option>
                                        <option value="January" {{ old('bulan') == 'January' ? 'selected' : '' }}>Januari
                                        </option>
                                        <option value="February" {{ old('bulan') == 'February' ? 'selected' : '' }}>Februari
                                        </option>
                                        <option value="March" {{ old('bulan') == 'March' ? 'selected' : '' }}>Maret
                                        </option>
                                        <option value="April" {{ old('bulan') == 'April' ? 'selected' : '' }}>April
                                        </option>
                                        <option value="May" {{ old('bulan') == 'May' ? 'selected' : '' }}>Mei</option>
                                        <option value="June" {{ old('bulan') == 'June' ? 'selected' : '' }}>Juni</option>
                                        <option value="July" {{ old('bulan') == 'July' ? 'selected' : '' }}>Juli</option>
                                        <option value="August" {{ old('bulan') == 'August' ? 'selected' : '' }}>Agustus
                                        </option>
                                        <option value="September" {{ old('bulan') == 'September' ? 'selected' : '' }}>
                                            September</option>
                                        <option value="October" {{ old('bulan') == 'October' ? 'selected' : '' }}>Oktober
                                        </option>
                                        <option value="November" {{ old('bulan') == 'November' ? 'selected' : '' }}>
                                            November</option>
                                        <option value="December" {{ old('bulan') == 'December' ? 'selected' : '' }}>
                                            Desember</option>
                                    </select>
                                    @error('bulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('tahun') is-invalid @enderror" id="tahun"
                                        name="tahun" required>
                                        <option value="">Pilih Tahun</option>
                                        @for ($i = date('Y'); $i >= 2020; $i--)
                                            <option value="{{ $i }}"
                                                {{ old('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bonus" class="form-label">Bonus Global (opsional)</label>
                                    <input type="number" class="form-control @error('bonus') is-invalid @enderror"
                                        id="bonus" name="bonus" value="{{ old('bonus', 0) }}" min="0">
                                    @error('bonus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Bonus yang akan diberikan ke semua guru</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="potongan" class="form-label">Potongan Global (opsional)</label>
                                    <input type="number" class="form-control @error('potongan') is-invalid @enderror"
                                        id="potongan" name="potongan" value="{{ old('potongan', 0) }}" min="0">
                                    @error('potongan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Potongan yang akan diberlakukan ke semua
                                        guru</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Informasi Generate</h6>
                                    <p class="card-text mb-2">
                                        <strong>Total Guru Aktif:</strong>
                                        {{ \App\Models\Teacher::where('is_active', true)->count() }} guru
                                    </p>
                                    <p class="card-text mb-0">
                                        <strong>Catatan:</strong> Sistem akan otomatis menghitung gaji berdasarkan:
                                    </p>
                                    <ul class="mb-0">
                                        <li>Data absensi masing-masing guru</li>
                                        <li>Gaji pokok dan jabatan</li>
                                        <li>Tunjangan yang aktif</li>
                                        <li>Bonus dan potongan global</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('salaries.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-warning" id="bulk-generate-btn">
                                <i class="fas fa-cogs me-2"></i>Generate Gaji Semua Guru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmBulkGenerate() {
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;

                if (!bulan || !tahun) {
                    alert('Mohon pilih bulan dan tahun terlebih dahulu!');
                    return false;
                }

                const monthNames = {
                    'January': 'Januari',
                    'February': 'Februari',
                    'March': 'Maret',
                    'April': 'April',
                    'May': 'Mei',
                    'June': 'Juni',
                    'July': 'Juli',
                    'August': 'Agustus',
                    'September': 'September',
                    'October': 'Oktober',
                    'November': 'November',
                    'December': 'Desember'
                };

                const confirmation = confirm(
                    `Apakah Anda yakin ingin generate gaji untuk semua guru pada periode ${monthNames[bulan]} ${tahun}?\n\n` +
                    `Proses ini akan:\n` +
                    `- Generate gaji untuk semua guru aktif\n` +
                    `- Menghitung berdasarkan data absensi dan tunjangan\n` +
                    `- Tidak dapat dibatalkan setelah dijalankan\n\n` +
                    `Klik OK untuk melanjutkan.`
                );

                if (confirmation) {
                    // Disable button dan ubah text untuk mencegah double submit
                    const btn = document.getElementById('bulk-generate-btn');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                    console.log(btn);

                    // Allow form submission to proceed
                    return true;
                }

                return false;
            }

            // Auto select current month and year if not selected
            document.addEventListener('DOMContentLoaded', function() {
                const bulanSelect = document.getElementById('bulan');
                const tahunSelect = document.getElementById('tahun');

                if (!bulanSelect.value) {
                    const currentMonth = new Date().toLocaleString('en-US', {
                        month: 'long'
                    });
                    bulanSelect.value = currentMonth;
                }

                if (!tahunSelect.value) {
                    tahunSelect.value = new Date().getFullYear();
                }
            });
        </script>
    @endpush
@endsection
