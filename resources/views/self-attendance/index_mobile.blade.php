@extends('layouts.app')

@section('title', 'Absensi Mandiri - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3">Absensi Mandiri</h1>
        <p class="text-muted mb-4">Selamat datang, {{ $teacher->nama_lengkap }}</p>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Kamera Absensi
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <!-- Mobile-optimized video container -->
                    <div class="position-relative d-inline-block">
                        <video id="video"
                               class="img-fluid rounded border"
                               style="max-width: 100%; height: auto; max-height: 300px; background-color: #f8f9fa;"
                               autoplay></video>
                        <canvas id="canvas"
                                style="display: none; max-width: 100%; height: auto;"></canvas>

                        <!-- Mobile overlay for better UX -->
                        <div id="camera-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light rounded" style="display: none !important;">
                            <div class="text-center">
                                <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                                <p class="text-muted">Mengakses kamera...</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <button type="button" class="btn btn-success btn-lg" id="clock-in-btn"
                                    {{ $todayAttendance && $todayAttendance->jam_masuk ? 'disabled' : '' }}>
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Absen Masuk
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" id="clock-out-btn"
                                    {{ !$todayAttendance || !$todayAttendance->jam_masuk || $todayAttendance->jam_keluar ? 'disabled' : '' }}>
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Absen Keluar
                            </button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pastikan wajah Anda terlihat jelas dalam kamera
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Status Hari Ini
                </h5>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-day fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $todayAttendance->tanggal->format('d F Y') }}</h6>
                            <span class="badge bg-success">Sudah Absen</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-sign-in-alt text-success mb-2"></i>
                                <div>
                                    <strong>Masuk</strong><br>
                                    <span class="text-success">{{ $todayAttendance->jam_masuk ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-sign-out-alt text-danger mb-2"></i>
                                <div>
                                    <strong>Keluar</strong><br>
                                    <span class="text-danger">{{ $todayAttendance->jam_keluar ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum Absen Hari Ini</h6>
                        <p class="text-muted small">Silakan lakukan absensi masuk</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Cards for Mobile -->
<div class="row mt-4">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-week fa-2x text-primary mb-2"></i>
                <h6 class="card-title">Minggu Ini</h6>
                <h5 class="text-primary">{{ isset($weeklyAttendances) ? $weeklyAttendances->count() : 0 }}</h5>
                <small class="text-muted">hari</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                <h6 class="card-title">Bulan Ini</h6>
                <h5 class="text-success">{{ isset($monthlyAttendances) ? $monthlyAttendances->count() : 0 }}</h5>
                <small class="text-muted">hari</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h6 class="card-title">Rata-rata</h6>
                <h5 class="text-warning">{{ $averageWorkingHours ?? '0' }}</h5>
                <small class="text-muted">jam/hari</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                <h6 class="card-title">Kehadiran</h6>
                <h5 class="text-info">{{ $attendanceRate ?? '0' }}%</h5>
                <small class="text-muted">bulan ini</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance History -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Absensi Terbaru
                </h5>
                <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <!-- Mobile Card View -->
                    <div class="d-md-none">
                        @foreach($recentAttendances as $attendance)
                        <div class="card mb-2 border-start border-3 {{ $attendance->status == 'hadir' ? 'border-success' : ($attendance->status == 'terlambat' ? 'border-warning' : 'border-danger') }}">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $attendance->tanggal->format('d M Y') }}</h6>
                                        <small class="text-muted">{{ $attendance->jam_masuk ?? '-' }} - {{ $attendance->jam_keluar ?? '-' }}</small>
                                    </div>
                                    <div>
                                        @switch($attendance->status)
                                            @case('hadir')
                                                <span class="badge bg-success">Hadir</span>
                                                @break
                                            @case('terlambat')
                                                <span class="badge bg-warning">Terlambat</span>
                                                @break
                                            @case('tidak_hadir')
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                                @break
                                            @case('izin')
                                                <span class="badge bg-info">Izin</span>
                                                @break
                                            @case('sakit')
                                                <span class="badge bg-secondary">Sakit</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAttendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                                        <td>{{ $attendance->jam_keluar ?? '-' }}</td>
                                        <td>
                                            @switch($attendance->status)
                                                @case('hadir')
                                                    <span class="badge bg-success">Hadir</span>
                                                    @break
                                                @case('terlambat')
                                                    <span class="badge bg-warning">Terlambat</span>
                                                    @break
                                                @case('tidak_hadir')
                                                    <span class="badge bg-danger">Tidak Hadir</span>
                                                    @break
                                                @case('izin')
                                                    <span class="badge bg-info">Izin</span>
                                                    @break
                                                @case('sakit')
                                                    <span class="badge bg-secondary">Sakit</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada riwayat absensi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Sedang memproses absensi...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const clockInBtn = document.getElementById('clock-in-btn');
    const clockOutBtn = document.getElementById('clock-out-btn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    // Initialize camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        const constraints = {
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                video.srcObject = stream;
                video.onloadedmetadata = function() {
                    // Adjust canvas size to match video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                };
            })
            .catch(function(err) {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin kamera.');
            });
    }

    // Clock in functionality
    clockInBtn.addEventListener('click', function() {
        captureAndSubmit('clock-in');
    });

    // Clock out functionality
    clockOutBtn.addEventListener('click', function() {
        captureAndSubmit('clock-out');
    });

    function captureAndSubmit(action) {
        if (!video.srcObject) {
            alert('Kamera tidak tersedia');
            return;
        }

        // Show loading
        loadingModal.show();

        // Capture image
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert to blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('photo', blob, 'attendance.jpg');
            formData.append('action', action);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("self-attendance.store") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingModal.hide();
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                loadingModal.hide();
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses absensi');
            });
        }, 'image/jpeg', 0.8);
    }

    // Handle page visibility changes (mobile optimization)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Pause video when page is hidden
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.enabled = false);
            }
        } else {
            // Resume video when page is visible
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.enabled = true);
            }
        }
    });
});
</script>
@endpush
