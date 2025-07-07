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
                                <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                                <input type="number" class="form-control @error('gaji_pokok') is-invalid @enderror"
                                       id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok') }}" required>
                                @error('gaji_pokok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tunjangan" class="form-label">Tunjangan</label>
                                <input type="number" class="form-control @error('tunjangan') is-invalid @enderror"
                                       id="tunjangan" name="tunjangan" value="{{ old('tunjangan', 0) }}">
                                @error('tunjangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- New fields for position, education level, photo, and shifts -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="position_id" class="form-label">Jabatan</label>
                                <select class="form-control @error('position_id') is-invalid @enderror" id="position_id" name="position_id">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach(\App\Models\Position::active()->get() as $position)
                                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }} (Level {{ $position->level }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

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
                                <div class="form-check-group row form-allowance-types">
                                    @foreach($allowanceTypes as $allowanceType)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="allowance_types_{{ $allowanceType->id }}"
                                                       name="allowance_types[]" value="{{ $allowanceType->id }}"
                                                       {{ in_array($allowanceType->id, old('allowance_types', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allowance_types_{{ $allowanceType->id }}">
                                                    {{ $allowanceType->name }}
                                                    <small class="text-muted">(Rp {{ number_format($allowanceType->default_amount, 0, ',', '.') }})</small>
                                                </label>
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
