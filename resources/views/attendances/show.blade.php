@extends('layouts.app')

@section('title', 'Detail Absensi - YAKIIN')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Detail Absensi</h1>
            <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Absensi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    @if(auth()->user()->role !== 'guru')
                    <tr>
                        <td width="150"><strong>Nama Guru:</strong></td>
                        <td>{{ $attendance->teacher->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>{{ $attendance->teacher->nip }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $attendance->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jam Masuk:</strong></td>
                        <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jam Keluar:</strong></td>
                        <td>{{ $attendance->jam_keluar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @switch($attendance->status)
                                @case('hadir')
                                    <span class="badge bg-success">Hadir</span>
                                    @break
                                @case('tidak_hadir')
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                    @break
                                @case('terlambat')
                                    <span class="badge bg-warning">Terlambat</span>
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
                    @if($attendance->location)
                    <tr>
                        <td><strong>Lokasi:</strong></td>
                        <td>{{ $attendance->location }}</td>
                    </tr>
                    @endif
                    @if($attendance->keterangan)
                    <tr>
                        <td><strong>Keterangan:</strong></td>
                        <td>{{ $attendance->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Foto Absensi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($attendance->photo_masuk)
                    <div class="col-md-6 mb-3">
                        <h6>Foto Masuk</h6>
                        <img src="{{ asset('storage/' . $attendance->photo_masuk) }}"
                             alt="Foto Masuk"
                             class="img-fluid rounded border"
                             style="cursor: pointer;"
                             onclick="showPhotoModal('{{ asset('storage/' . $attendance->photo_masuk) }}', 'Foto Masuk - {{ $attendance->tanggal->format('d F Y') }}')">
                        <small class="text-muted d-block mt-1">{{ $attendance->jam_masuk }}</small>
                    </div>
                    @endif

                    @if($attendance->photo_keluar)
                    <div class="col-md-6 mb-3">
                        <h6>Foto Keluar</h6>
                        <img src="{{ asset('storage/' . $attendance->photo_keluar) }}"
                             alt="Foto Keluar"
                             class="img-fluid rounded border"
                             style="cursor: pointer;"
                             onclick="showPhotoModal('{{ asset('storage/' . $attendance->photo_keluar) }}', 'Foto Keluar - {{ $attendance->tanggal->format('d F Y') }}')">
                        <small class="text-muted d-block mt-1">{{ $attendance->jam_keluar }}</small>
                    </div>
                    @endif

                    @if(!$attendance->photo_masuk && !$attendance->photo_keluar)
                    <div class="col-12 text-center text-muted">
                        <i class="fas fa-camera-slash fa-3x mb-3"></i>
                        <p>Tidak ada foto absensi</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Foto Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="Foto Absensi" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPhotoModal(imageSrc, title) {
    document.getElementById('modalPhoto').src = imageSrc;
    document.getElementById('photoModalLabel').textContent = title;

    const modal = new bootstrap.Modal(document.getElementById('photoModal'));
    modal.show();
}
</script>
@endpush
