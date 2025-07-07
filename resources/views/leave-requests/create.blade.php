@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Ajukan Cuti
                    </h3>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                                    <select name="leave_type" id="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Cuti</option>
                                        @foreach($leaveTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('leave_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Lampiran (Opsional)</label>
                                    <input type="file"
                                           name="attachment"
                                           id="attachment"
                                           class="form-control @error('attachment') is-invalid @enderror"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">Format: PDF, JPG, JPEG, PNG. Maksimal 2MB.</div>
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date"
                                           name="start_date"
                                           id="start_date"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date"
                                           name="end_date"
                                           id="end_date"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="total_days_display" class="form-label">Total Hari</label>
                            <input type="text"
                                   id="total_days_display"
                                   class="form-control"
                                   readonly
                                   value="0 hari">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan <span class="text-danger">*</span></label>
                            <textarea name="reason"
                                      id="reason"
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Jelaskan alasan pengajuan cuti..."
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pengajuan cuti akan dikirim ke admin untuk disetujui</li>
                                    <li>Pastikan tanggal yang dipilih sudah benar</li>
                                    <li>Untuk cuti sakit, disarankan melampirkan surat dokter</li>
                                    <li>Anda akan mendapat notifikasi setelah pengajuan diproses</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Ajukan Cuti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysDisplay = document.getElementById('total_days_display');

    function calculateDays() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                totalDaysDisplay.value = diffDays + ' hari';
            } else {
                totalDaysDisplay.value = '0 hari';
            }
        } else {
            totalDaysDisplay.value = '0 hari';
        }
    }

    startDateInput.addEventListener('change', function() {
        // Update minimum end date when start date changes
        endDateInput.min = this.value;
        calculateDays();
    });

    endDateInput.addEventListener('change', calculateDays);

    // Initial calculation if values are present
    calculateDays();
});
</script>
@endsection
