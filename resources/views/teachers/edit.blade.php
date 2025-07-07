@extends('layouts.app')

@section('title', 'Edit Guru - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Guru</h1>
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
                <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $teacher->user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $teacher->user->email) }}" required>
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
                                       id="nip" name="nip" value="{{ old('nip', $teacher->nip) }}" required>
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
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $teacher->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $teacher->no_telepon) }}" required>
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
                                    <option value="laki-laki" {{ old('jenis_kelamin', $teacher->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('jenis_kelamin', $teacher->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                       id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $teacher->tempat_lahir) }}" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $teacher->tanggal_lahir ? $teacher->tanggal_lahir->format('Y-m-d') : '') }}" required>
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
                                       id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir) }}" required>
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control @error('mata_pelajaran') is-invalid @enderror"
                                       id="mata_pelajaran" name="mata_pelajaran" value="{{ old('mata_pelajaran', $teacher->mata_pelajaran) }}" required>
                                @error('mata_pelajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                       id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $teacher->tanggal_masuk ? $teacher->tanggal_masuk->format('Y-m-d') : '') }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salary_type" class="form-label">Tipe Gaji</label>
                                <select class="form-select @error('salary_type') is-invalid @enderror"
                                        id="salary_type" name="salary_type" required>
                                    <option value="">Pilih Tipe Gaji</option>
                                    <option value="per_hari" {{ old('salary_type', $teacher->salary_type ?? 'per_bulan') == 'per_hari' ? 'selected' : '' }}>Per Hari</option>
                                    <option value="per_jam" {{ old('salary_type', $teacher->salary_type ?? 'per_bulan') == 'per_jam' ? 'selected' : '' }}>Per Jam</option>
                                    <option value="per_bulan" {{ old('salary_type', $teacher->salary_type ?? 'per_bulan') == 'per_bulan' ? 'selected' : '' }}>Per Bulan</option>
                                </select>
                                @error('salary_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal Gaji</label>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                       id="nominal" name="nominal" value="{{ old('nominal', $teacher->nominal) }}" required>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="salary-help-text">
                                    @if(old('salary_type', $teacher->salary_type ?? 'per_bulan') == 'per_hari')
                                        Nominal akan dikalikan dengan jumlah hari kerja
                                    @elseif(old('salary_type', $teacher->salary_type ?? 'per_bulan') == 'per_jam')
                                        Nominal akan dikalikan dengan jumlah jam kerja
                                    @else
                                        Nominal gaji bulanan tetap
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6"></div>
                    </div>

                    <!-- New fields for position, education level, photo, and shifts -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="positions" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('positions') is-invalid @enderror"
                                        id="positions" name="positions[]" multiple required>
                                    @foreach(\App\Models\Position::active()->get() as $position)
                                        <option value="{{ $position->id }}"
                                                {{ in_array($position->id, old('positions', $teacher->positions->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                            {{ $position->name }}
                                            @if($position->base_allowance > 0)
                                                (Tunjangan: Rp {{ number_format($position->base_allowance, 0, ',', '.') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('positions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu jabatan.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="education_level_id" class="form-label">Jenjang Pendidikan</label>
                                <select class="form-control @error('education_level_id') is-invalid @enderror" id="education_level_id" name="education_level_id">
                                    <option value="">Pilih Jenjang</option>
                                    @foreach(\App\Models\EducationLevel::active()->orderedByLevel()->get() as $level)
                                        <option value="{{ $level->id }}" {{ old('education_level_id', $teacher->education_level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('education_level_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto Profil</label>
                                @if($teacher->photo_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="Foto Profil" class="img-thumbnail" style="max-width: 100px;">
                                        <small class="d-block text-muted">Foto saat ini</small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                       id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hari Kerja</label>
                                <div class="form-check-group">
                                    @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="working_days_{{ $day }}"
                                                   name="working_days[]" value="{{ $day }}"
                                                   {{ in_array($day, old('working_days', $teacher->working_days ?? [])) ? 'checked' : '' }}>
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
                                        <input class="form-check-input" type="radio" id="shift_none" name="shift_id" value="">
                                        <label class="form-check-label" for="shift_none">
                                            Tidak ada shift
                                        </label>
                                    </div>
                                    @foreach(\App\Models\Shift::active()->get() as $shift)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="shift_{{ $shift->id }}"
                                                   name="shift_id" value="{{ $shift->id }}"
                                                   {{ old('shift_id', $teacher->shifts->first()?->id) == $shift->id ? 'checked' : '' }}>
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
                                <div class="form-check-group row" id="allowanceContainer">
                                    @php
                                        $existingAllowances = $teacher->teacherAllowances->keyBy('allowance_type_id');
                                    @endphp
                                    @foreach($allowanceTypes as $allowanceType)
                                        @php
                                            $existingAllowance = $existingAllowances->get($allowanceType->id);
                                            $isChecked = $existingAllowance || in_array($allowanceType->id, old('allowance_types', []));
                                        @endphp
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input allowance-checkbox" type="checkbox"
                                                               id="allowance_types_{{ $allowanceType->id }}"
                                                               name="allowance_types[]" value="{{ $allowanceType->id }}"
                                                               {{ $isChecked ? 'checked' : '' }}
                                                               onchange="toggleAllowanceOptions({{ $allowanceType->id }})">
                                                        <label class="form-check-label fw-bold" for="allowance_types_{{ $allowanceType->id }}">
                                                            {{ $allowanceType->name }}
                                                            <small class="text-muted">(Default: Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }})</small>
                                                        </label>
                                                    </div>

                                                    <div class="allowance-options" id="allowance_options_{{ $allowanceType->id }}"
                                                         style="display: {{ $isChecked ? 'block' : 'none' }}">
                                                        <div class="mb-2">
                                                            <label class="form-label small">Tipe Perhitungan:</label>
                                                            <select class="form-select form-select-sm" name="allowance_calculation_types[{{ $allowanceType->id }}]">
                                                                <option value="per_hari" {{ old("allowance_calculation_types.{$allowanceType->id}", $existingAllowance->calculation_type ?? $allowanceType->calculation_type ?? 'per_hari') == 'per_hari' ? 'selected' : '' }}>Per Hari</option>
                                                                <option value="per_bulan" {{ old("allowance_calculation_types.{$allowanceType->id}", $existingAllowance->calculation_type ?? $allowanceType->calculation_type ?? 'per_hari') == 'per_bulan' ? 'selected' : '' }}>Per Bulan</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label small">Nominal Custom:</label>
                                                            <input type="number" class="form-control form-control-sm"
                                                                   name="allowance_amounts[{{ $allowanceType->id }}]"
                                                                   value="{{ old("allowance_amounts.{$allowanceType->id}", $existingAllowance->amount ?? '') }}"
                                                                   placeholder="Default: Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }}">
                                                            <small class="text-muted">Kosongkan untuk menggunakan nominal default</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('allowance_types')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Pilih jenis tunjangan yang akan diberikan kepada guru ini.</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Password akun pengguna tidak akan berubah saat melakukan edit data guru.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin memperbarui data guru ini?')">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Salary type change handler
function handleSalaryTypeChange() {
    const salaryType = document.getElementById('salary_type').value;
    const helpText = document.getElementById('salary-help-text');

    switch(salaryType) {
        case 'per_hari':
            helpText.textContent = 'Nominal akan dikalikan dengan jumlah hari kerja';
            break;
        case 'per_jam':
            helpText.textContent = 'Nominal akan dikalikan dengan jumlah jam kerja';
            break;
        case 'per_bulan':
            helpText.textContent = 'Nominal gaji bulanan tetap';
            break;
        default:
            helpText.textContent = 'Pilih tipe gaji terlebih dahulu';
    }
}

// Toggle allowance options visibility
function toggleAllowanceOptions(allowanceId) {
    const checkbox = document.getElementById(`allowance_types_${allowanceId}`);
    const options = document.getElementById(`allowance_options_${allowanceId}`);

    if (checkbox.checked) {
        options.style.display = 'block';
    } else {
        options.style.display = 'none';
    }
}

// Helper function to toggle all allowances
function toggleAllAllowances() {
    const checkboxes = document.querySelectorAll('input[name="allowance_types[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
        // Trigger the onchange event to show/hide options
        toggleAllowanceOptions(cb.value);
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set up salary type change listener
    const salaryTypeSelect = document.getElementById('salary_type');
    if (salaryTypeSelect) {
        salaryTypeSelect.addEventListener('change', handleSalaryTypeChange);
        handleSalaryTypeChange(); // Initialize help text
    }

    // Add a "Select All" button for allowances
    const allowanceContainer = document.getElementById('allowanceContainer');
    if (allowanceContainer) {
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'btn btn-sm btn-outline-secondary mb-3';
        selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>Pilih Semua / Batal Pilih';
        selectAllBtn.onclick = toggleAllAllowances;

        allowanceContainer.parentNode.insertBefore(selectAllBtn, allowanceContainer);
    }

    // Initialize allowance options visibility based on current state
    document.querySelectorAll('.allowance-checkbox').forEach(checkbox => {
        toggleAllowanceOptions(checkbox.value);
    });
});
</script>
@endsection
