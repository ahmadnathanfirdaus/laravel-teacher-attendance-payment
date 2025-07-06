@extends('layouts.app')

@section('title', 'Absensi Mandiri - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">Absensi Mandiri</h1>
        <p class="text-muted">Selamat datang, {{ $teacher->nama_lengkap }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Kamera Absensi
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <video id="video" width="400" height="300" autoplay style="border: 2px solid #ddd; border-radius: 8px;"></video>
                    <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>

                    <div class="mt-3">
                        <div id="location-info" class="text-muted mb-3"></div>

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success" id="clock-in-btn"
                                    {{ $todayAttendance && $todayAttendance->jam_masuk ? 'disabled' : '' }}>
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Absen Masuk
                            </button>
                            <button type="button" class="btn btn-danger" id="clock-out-btn"
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

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="mb-3">
                        <strong>Tanggal:</strong> {{ $todayAttendance->tanggal->format('d F Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        @switch($todayAttendance->status)
                            @case('hadir')
                                <span class="badge bg-success">Hadir</span>
                                @break
                            @case('terlambat')
                                <span class="badge bg-warning">Terlambat</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($todayAttendance->status) }}</span>
                        @endswitch
                    </div>
                    <div class="mb-3">
                        <strong>Jam Masuk:</strong>
                        {{ $todayAttendance->jam_masuk ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Jam Keluar:</strong>
                        {{ $todayAttendance->jam_keluar ?? '-' }}
                    </div>
                    @if($todayAttendance->keterangan)
                        <div class="mb-3">
                            <strong>Keterangan:</strong>
                            {{ $todayAttendance->keterangan }}
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <p>Belum ada absensi hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Jam Kerja</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Jam Masuk:</strong> 07:00 WIB
                </div>
                <div class="mb-2">
                    <strong>Jam Keluar:</strong> 16:00 WIB
                </div>
                <div class="mb-2">
                    <strong>Waktu Sekarang:</strong>
                    <span id="current-time">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="resultModalHeader">
                <h5 class="modal-title" id="resultModalTitle">Status Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4" id="resultModalBody">
                <div id="resultIcon" class="mb-3"></div>
                <p id="resultMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="refreshBtn" onclick="window.location.reload()" style="display: none;">Refresh Halaman</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let video, canvas, context;
let currentLocation = null;
let resultModal = null;

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi result modal
    try {
        resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        console.log('Result modal initialized successfully');
    } catch (error) {
        console.error('Error initializing result modal:', error);
    }

    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    context = canvas.getContext('2d');

    // Initialize camera
    initCamera();

    // Get location
    getLocation();

    // Update current time
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);

    // Event listeners
    document.getElementById('clock-in-btn').addEventListener('click', function() {
        takeAttendance('masuk');
    });

    document.getElementById('clock-out-btn').addEventListener('click', function() {
        takeAttendance('keluar');
    });
});

function initCamera() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: {
                width: 400,
                height: 300,
                facingMode: 'user' // Front camera
            }
        })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.error('Error accessing camera: ', err);
            alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin kamera.');
        });
    } else {
        alert('Browser Anda tidak mendukung akses kamera.');
    }
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            currentLocation = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            };

            document.getElementById('location-info').innerHTML =
                '<i class="fas fa-map-marker-alt me-1"></i>' +
                'Lokasi: ' + currentLocation.latitude.toFixed(6) + ', ' + currentLocation.longitude.toFixed(6);
        }, function(error) {
            document.getElementById('location-info').innerHTML =
                '<i class="fas fa-exclamation-triangle me-1 text-warning"></i>' +
                'Lokasi tidak tersedia';
        });
    }
}

function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('current-time').textContent = timeString;
}

function takeAttendance(type) {
    console.log('Starting attendance process for:', type);

    // Disable buttons to prevent double submission
    const clockInBtn = document.getElementById('clock-in-btn');
    const clockOutBtn = document.getElementById('clock-out-btn');
    const originalClockInHTML = clockInBtn.innerHTML;
    const originalClockOutHTML = clockOutBtn.innerHTML;

    clockInBtn.disabled = true;
    clockOutBtn.disabled = true;

    // Change button text to show processing
    if (type === 'masuk') {
        clockInBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    } else {
        clockOutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    }

    // Capture photo
    context.drawImage(video, 0, 0, 400, 300);
    const photoData = canvas.toDataURL('image/jpeg', 0.8);

    // Prepare data
    const data = {
        type: type,
        photo: photoData,
        location: currentLocation ? `${currentLocation.latitude}, ${currentLocation.longitude}` : null,
        _token: '{{ csrf_token() }}'
    };

    // Send to server
    fetch('{{ route("self-attendance.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response received:', data);

        if (data.success) {
            console.log('Success response');
            showResultModal('success', 'Absensi Berhasil!', data.message, true);
        } else {
            console.log('Error response:', data.error);
            showResultModal('error', 'Absensi Gagal!', data.error || 'Terjadi kesalahan', false);

            // Re-enable buttons and restore original text only on error
            clockInBtn.disabled = false;
            clockOutBtn.disabled = false;
            clockInBtn.innerHTML = originalClockInHTML;
            clockOutBtn.innerHTML = originalClockOutHTML;
        }
    })
    .catch(error => {
        console.log('Fetch error occurred:', error);
        showResultModal('error', 'Absensi Gagal!', 'Terjadi kesalahan saat memproses absensi. Silakan coba lagi.', false);

        // Re-enable buttons and restore original text only on error
        clockInBtn.disabled = false;
        clockOutBtn.disabled = false;
        clockInBtn.innerHTML = originalClockInHTML;
        clockOutBtn.innerHTML = originalClockOutHTML;
    });
}

function showResultModal(type, title, message, shouldRefresh = false) {
    console.log('Showing result modal:', type, title, message);

    const modalHeader = document.getElementById('resultModalHeader');
    const modalTitle = document.getElementById('resultModalTitle');
    const resultIcon = document.getElementById('resultIcon');
    const resultMessage = document.getElementById('resultMessage');
    const refreshBtn = document.getElementById('refreshBtn');

    // Set title
    modalTitle.textContent = title;

    // Set icon and colors based on type
    if (type === 'success') {
        modalHeader.className = 'modal-header bg-success text-white';
        resultIcon.innerHTML = '<i class="fas fa-check-circle fa-4x text-success"></i>';
        refreshBtn.style.display = shouldRefresh ? 'inline-block' : 'none';
    } else {
        modalHeader.className = 'modal-header bg-danger text-white';
        resultIcon.innerHTML = '<i class="fas fa-times-circle fa-4x text-danger"></i>';
        refreshBtn.style.display = 'none';
    }

    // Set message
    resultMessage.textContent = message;

    // Show modal
    if (resultModal) {
        resultModal.show();
    }

    // Auto refresh for success after 3 seconds
    if (shouldRefresh && type === 'success') {
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    }
}
</script>
@endpush
