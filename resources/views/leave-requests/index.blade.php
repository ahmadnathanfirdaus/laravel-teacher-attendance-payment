@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-times me-2"></i>
                        Pengajuan Cuti
                    </h3>
                    @if(Auth::user()->role === 'guru')
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Ajukan Cuti
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('leave-requests.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    @foreach(\App\Models\LeaveRequest::getStatuses() as $key => $value)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="leave_type" class="form-select">
                                    <option value="">Semua Jenis Cuti</option>
                                    @foreach(\App\Models\LeaveRequest::getLeaveTypes() as $key => $value)
                                        <option value="{{ $key }}" {{ request('leave_type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(in_array(Auth::user()->role, ['admin', 'bendahara']) && isset($teachers))
                                <div class="col-md-3">
                                    <select name="teacher_id" class="form-select">
                                        <option value="">Semua Guru</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Filter
                                </button>
                                <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-2"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Leave Requests Table -->
                    @if($leaveRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
                                            <th>Guru</th>
                                        @endif
                                        <th>Jenis Cuti</th>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Diajukan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveRequests as $index => $leaveRequest)
                                        <tr>
                                            <td>{{ $leaveRequests->firstItem() + $index }}</td>
                                            @if(in_array(Auth::user()->role, ['admin', 'bendahara']))
                                                <td>{{ $leaveRequest->teacher->user->name }}</td>
                                            @endif
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ \App\Models\LeaveRequest::getLeaveTypes()[$leaveRequest->leave_type] }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $leaveRequest->start_date->format('d M Y') }}
                                                @if($leaveRequest->start_date->format('Y-m-d') !== $leaveRequest->end_date->format('Y-m-d'))
                                                    <br>s/d {{ $leaveRequest->end_date->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $leaveRequest->total_days }} hari</td>
                                            <td>
                                                <span class="badge {{ $leaveRequest->getStatusBadgeClass() }}">
                                                    {{ \App\Models\LeaveRequest::getStatuses()[$leaveRequest->status] }}
                                                </span>
                                            </td>
                                            <td>{{ $leaveRequest->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('leave-requests.show', $leaveRequest) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if(Auth::user()->role === 'guru' && $leaveRequest->isPending())
                                                        <a href="{{ route('leave-requests.edit', $leaveRequest) }}"
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('leave-requests.destroy', $leaveRequest) }}"
                                                              method="POST"
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan cuti ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(in_array(Auth::user()->role, ['admin', 'bendahara']) && $leaveRequest->isPending())
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#approveModal{{ $leaveRequest->id }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectModal{{ $leaveRequest->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $leaveRequests->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada pengajuan cuti ditemukan.</p>
                            @if(Auth::user()->role === 'guru')
                                <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Ajukan Cuti Pertama
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval/Rejection Modals -->
@if(in_array(Auth::user()->role, ['admin', 'bendahara']))
    @foreach($leaveRequests as $leaveRequest)
        @if($leaveRequest->isPending())
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal{{ $leaveRequest->id }}" tabindex="-1">
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
                                    <label for="admin_notes_approve{{ $leaveRequest->id }}" class="form-label">Catatan (Opsional)</label>
                                    <textarea name="admin_notes"
                                              id="admin_notes_approve{{ $leaveRequest->id }}"
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
            <div class="modal fade" id="rejectModal{{ $leaveRequest->id }}" tabindex="-1">
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
                                    <label for="admin_notes_reject{{ $leaveRequest->id }}" class="form-label">Alasan Penolakan</label>
                                    <textarea name="admin_notes"
                                              id="admin_notes_reject{{ $leaveRequest->id }}"
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
    @endforeach
@endif
@endsection
