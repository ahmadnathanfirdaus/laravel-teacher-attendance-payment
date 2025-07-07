@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check me-2"></i>
                        Detail Pengajuan Cuti
                    </h3>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Guru</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->teacher->user->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->teacher->nip }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Cuti</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info fs-6">
                                        {{ \App\Models\LeaveRequest::getLeaveTypes()[$leaveRequest->leave_type] }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge {{ $leaveRequest->getStatusBadgeClass() }} fs-6">
                                        {{ \App\Models\LeaveRequest::getStatuses()[$leaveRequest->status] }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->start_date->format('d M Y') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->end_date->format('d M Y') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Hari</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->total_days }} hari</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Pengajuan</label>
                                <p class="form-control-plaintext">{{ $leaveRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alasan</label>
                        <div class="card">
                            <div class="card-body">
                                {{ $leaveRequest->reason }}
                            </div>
                        </div>
                    </div>

                    @if($leaveRequest->attachment_path)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lampiran</label>
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ asset('storage/' . $leaveRequest->attachment_path) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-paperclip me-2"></i>
                                        Lihat Lampiran
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($leaveRequest->approved_by || $leaveRequest->admin_notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Informasi Persetujuan</label>
                            <div class="card">
                                <div class="card-body">
                                    @if($leaveRequest->approved_by)
                                        <p><strong>Diproses oleh:</strong> {{ $leaveRequest->approvedBy->name }}</p>
                                    @endif
                                    @if($leaveRequest->approved_at)
                                        <p><strong>Tanggal Diproses:</strong> {{ $leaveRequest->approved_at->format('d M Y H:i') }}</p>
                                    @endif
                                    @if($leaveRequest->admin_notes)
                                        <p><strong>Catatan Admin:</strong></p>
                                        <div class="alert alert-light">
                                            {{ $leaveRequest->admin_notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div>
                            @if(Auth::user()->role === 'guru' && $leaveRequest->isPending())
                                <a href="{{ route('leave-requests.edit', $leaveRequest) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit
                                </a>
                                <form action="{{ route('leave-requests.destroy', $leaveRequest) }}"
                                      method="POST"
                                      style="display: inline-block;"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan cuti ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div>
                            @if(in_array(Auth::user()->role, ['admin', 'bendahara']) && $leaveRequest->isPending())
                                <button type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal">
                                    <i class="fas fa-check me-2"></i>
                                    Setujui
                                </button>
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>
                                    Tolak
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
@if(in_array(Auth::user()->role, ['admin', 'bendahara']) && $leaveRequest->isPending())
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('leave-requests.update-status', $leaveRequest) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui pengajuan cuti dari <strong>{{ $leaveRequest->teacher->user->name }}</strong>?</p>
                        <div class="mb-3">
                            <label for="admin_notes_approve" class="form-label">Catatan (Opsional)</label>
                            <textarea name="admin_notes"
                                      id="admin_notes_approve"
                                      class="form-control"
                                      rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('leave-requests.update-status', $leaveRequest) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menolak pengajuan cuti dari <strong>{{ $leaveRequest->teacher->user->name }}</strong>?</p>
                        <div class="mb-3">
                            <label for="admin_notes_reject" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="admin_notes"
                                      id="admin_notes_reject"
                                      class="form-control"
                                      rows="3"
                                      required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
