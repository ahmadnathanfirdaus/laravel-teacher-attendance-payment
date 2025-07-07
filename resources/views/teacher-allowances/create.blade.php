@extends('layouts.app')

@section('title', 'Tambah Tunjangan Guru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Tunjangan Guru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher-allowances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('teacher-allowances.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">Pilih Guru <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id"
                                            required>
                                        <option value="">Pilih Guru</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->nama_lengkap }} ({{ $teacher->nip }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allowance_type_id">Jenis Tunjangan <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('allowance_type_id') is-invalid @enderror"
                                            id="allowance_type_id"
                                            name="allowance_type_id"
                                            required>
                                        <option value="">Pilih Jenis Tunjangan</option>
                                        @foreach($allowanceTypes as $allowanceType)
                                            <option value="{{ $allowanceType->id }}"
                                                    data-default-amount="{{ $allowanceType->default_amount }}"
                                                    {{ old('allowance_type_id') == $allowanceType->id ? 'selected' : '' }}>
                                                {{ $allowanceType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('allowance_type_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Jumlah Tunjangan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('amount') is-invalid @enderror"
                                               id="amount"
                                               name="amount"
                                               value="{{ old('amount') }}"
                                               placeholder="0"
                                               min="0"
                                               required>
                                        @error('amount')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Jumlah akan otomatis terisi sesuai default jenis tunjangan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="effective_date">Berlaku Mulai <span class="text-danger">*</span></label>
                                    <input type="date"
                                           class="form-control @error('effective_date') is-invalid @enderror"
                                           id="effective_date"
                                           name="effective_date"
                                           value="{{ old('effective_date', date('Y-m-d')) }}"
                                           required>
                                    @error('effective_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Catatan atau keterangan tambahan (opsional)">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Tunjangan akan aktif secara otomatis setelah disimpan</li>
                                        <li>Jumlah tunjangan dapat disesuaikan dari nilai default</li>
                                        <li>Tunjangan berlaku mulai tanggal yang ditentukan untuk perhitungan gaji</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Tunjangan
                                </button>
                                <a href="{{ route('teacher-allowances.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Auto-fill amount when allowance type is selected
    $('#allowance_type_id').on('change', function() {
        var defaultAmount = $(this).find('option:selected').data('default-amount');
        if (defaultAmount) {
            $('#amount').val(defaultAmount);
        } else {
            $('#amount').val('');
        }
    });

    // Format currency input
    $('#amount').on('input', function() {
        var value = this.value.replace(/[^\d]/g, '');
        this.value = value;
    });
});
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
