@extends('layouts.app')

@section('title', 'Tambah Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Guru</h1>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                       id="nip" name="nip" value="{{ old('nip') }}" required>
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Field nama lengkap dihapus, menggunakan field name dari user -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required>
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                       id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror"
                                       id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" required>
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control @error('mata_pelajaran') is-invalid @enderror"
                                       id="mata_pelajaran" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required>
                                @error('mata_pelajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                       id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="salary_type" class="form-label">Tipe Penggajian</label>
                                <select class="form-select @error('salary_type') is-invalid @enderror"
                                        id="salary_type" name="salary_type" required onchange="updateNominalLabel()">
                                    <option value="">Pilih Tipe Penggajian</option>
                                    <option value="per_hari" {{ old('salary_type') == 'per_hari' ? 'selected' : '' }}>Per Hari</option>
                                    <option value="per_jam" {{ old('salary_type') == 'per_jam' ? 'selected' : '' }}>Per Jam</option>
                                    <option value="per_bulan" {{ old('salary_type', 'per_bulan') == 'per_bulan' ? 'selected' : '' }}>Per Bulan</option>
                                </select>
                                @error('salary_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nominal" class="form-label">
                                    <span id="nominal-label">Nominal Gaji</span>
                                </label>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                       id="nominal" name="nominal" value="{{ old('nominal') }}" required>
                                <div class="form-text">
                                    <span id="nominal-help">Masukkan nominal sesuai tipe penggajian</span>
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- New fields for education level, photo, and shifts -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="education_level_id" class="form-label">Jenjang Pendidikan</label>
                                <select class="form-control @error('education_level_id') is-invalid @enderror" id="education_level_id" name="education_level_id">
                                    <option value="">Pilih Jenjang</option>
                                    @foreach(\App\Models\EducationLevel::active()->orderedByLevel()->get() as $level)
                                        <option value="{{ $level->id }}" {{ old('education_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('education_level_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                       id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Positions Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="positions" class="form-label">Jabatan (Bisa Lebih dari Satu)</label>
                                <select class="form-select @error('positions') is-invalid @enderror"
                                        id="positions" name="positions[]" multiple size="5">
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}"
                                                {{ in_array($position->id, old('positions', [])) ? 'selected' : '' }}>
                                            {{ $position->name }}
                                            @if($position->base_allowance > 0)
                                                (Tunjangan: Rp {{ number_format($position->base_allowance) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu jabatan.
                                    Pilih minimal satu jabatan untuk guru ini.
                                </div>
                                @error('positions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Working Days and Shifts Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hari Kerja</label>
                                <div class="form-check-group">
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="working_days_{{ $day }}"
                                                   name="working_days[]" value="{{ $day }}"
                                                   {{ in_array($day, old('working_days', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="working_days_{{ $day }}">
                                                {{ ucfirst($day) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('working_days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Shift Mengajar</label>
                                <div class="form-check-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="shift_none" name="shift_id" value="" checked>
                                        <label class="form-check-label" for="shift_none">
                                            Tidak ada shift
                                        </label>
                                    </div>
                                    @foreach(\App\Models\Shift::active()->get() as $shift)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="shift_{{ $shift->id }}"
                                                   name="shift_id" value="{{ $shift->id }}"
                                                   {{ old('shift_id') == $shift->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shift_{{ $shift->id }}">
                                                {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('shift_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Jenis Tunjangan</label>
                                <div class="form-allowance-types">
                                    @foreach($allowanceTypes as $allowanceType)
                                        <div class="card mb-3" style="display: none;" id="allowance_card_{{ $allowanceType->id }}">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input allowance-checkbox" type="checkbox"
                                                                   id="allowance_types_{{ $allowanceType->id }}"
                                                                   name="allowance_types[]" value="{{ $allowanceType->id }}"
                                                                   {{ in_array($allowanceType->id, old('allowance_types', [])) ? 'checked' : '' }}
                                                                   onchange="toggleAllowanceOptions({{ $allowanceType->id }})">
                                                            <label class="form-check-label" for="allowance_types_{{ $allowanceType->id }}">
                                                                <strong>{{ $allowanceType->name }}</strong>
                                                                <small class="text-muted">(Default: Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }})</small>
                                                                <br><small class="text-muted">{{ $allowanceType->description }}</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" id="calculation_options_{{ $allowanceType->id }}" style="display: none;">
                                                        <label for="allowance_calculation_{{ $allowanceType->id }}" class="form-label">Tipe Perhitungan</label>
                                                        <select class="form-select" name="allowance_calculation_{{ $allowanceType->id }}" id="allowance_calculation_{{ $allowanceType->id }}">
                                                            <option value="per_hari" {{ ($allowanceType->calculation_type ?? 'per_hari') == 'per_hari' ? 'selected' : '' }}>Per Hari</option>
                                                            <option value="per_bulan" {{ ($allowanceType->calculation_type ?? 'per_hari') == 'per_bulan' ? 'selected' : '' }}>Per Bulan</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4" id="amount_options_{{ $allowanceType->id }}" style="display: none;">
                                                        <label for="allowance_amount_{{ $allowanceType->id }}" class="form-label">Nominal</label>
                                                        <input type="number" class="form-control"
                                                               name="allowance_amount_{{ $allowanceType->id }}"
                                                               id="allowance_amount_{{ $allowanceType->id }}"
                                                               value="{{ old("allowance_amount_{$allowanceType->id}", $allowanceType->default_amount) }}"
                                                               min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('allowance_types')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Pilih jenis tunjangan yang akan diberikan kepada guru ini dengan tipe perhitungan dan nominal yang sesuai.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Helper function to toggle allowance selection
function toggleAllAllowances() {
    const checkboxes = document.querySelectorAll('input[name="allowance_types[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

// Add a "Select All" button for allowances
document.addEventListener('DOMContentLoaded', function() {
    const allowanceContainer = document.querySelector('.form-allowance-types');
    if (allowanceContainer) {
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'btn btn-sm btn-outline-secondary mb-2';
        selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>Pilih Semua / Batal Pilih';
        selectAllBtn.onclick = toggleAllAllowances;

        allowanceContainer.parentNode.insertBefore(selectAllBtn, allowanceContainer);
    }
});
</script>
@endpush
@endsection
