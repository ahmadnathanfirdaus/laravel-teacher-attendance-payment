<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            // Teacher can only see their own requests
            $teacher = Teacher::where('user_id', $user->id)->first();
            if (!$teacher) {
                abort(404, 'Data guru tidak ditemukan.');
            }

            $leaveRequests = LeaveRequest::where('teacher_id', $teacher->id)
                ->with(['teacher.user', 'approvedBy'])
                ->when($request->status, function($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($request->leave_type, function($query, $type) {
                    return $query->where('leave_type', $type);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('leave-requests.index', compact('leaveRequests'));
        }

        if (in_array($user->role, ['admin', 'bendahara'])) {
            // Admin can see all requests
            $leaveRequests = LeaveRequest::with(['teacher.user', 'approvedBy'])
                ->when($request->status, function($query, $status) {
                    return $query->where('status', $status);
                })
                ->when($request->leave_type, function($query, $type) {
                    return $query->where('leave_type', $type);
                })
                ->when($request->teacher_id, function($query, $teacherId) {
                    return $query->where('teacher_id', $teacherId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $teachers = Teacher::with('user')->get();

            return view('leave-requests.index', compact('leaveRequests', 'teachers'));
        }

        abort(403, 'Unauthorized access.');
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengajukan cuti.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $leaveTypes = LeaveRequest::getLeaveTypes();

        return view('leave-requests.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengajukan cuti.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:' . implode(',', array_keys(LeaveRequest::getLeaveTypes())),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total days
        $totalDays = Carbon::parse($validated['start_date'])
            ->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        LeaveRequest::create([
            'teacher_id' => $teacher->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'status' => LeaveRequest::STATUS_PENDING,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat dan menunggu persetujuan.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->role === 'guru') {
            $teacher = Teacher::where('user_id', $user->id)->first();
            if (!$teacher || $leaveRequest->teacher_id !== $teacher->id) {
                abort(403, 'Unauthorized access.');
            }
        } elseif (!in_array($user->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized access.');
        }

        $leaveRequest->load(['teacher.user', 'approvedBy']);

        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified leave request.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengedit pengajuan cuti.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher || $leaveRequest->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Hanya pengajuan cuti yang masih pending yang dapat diedit.');
        }

        $leaveTypes = LeaveRequest::getLeaveTypes();

        return view('leave-requests.edit', compact('leaveRequest', 'leaveTypes'));
    }

    /**
     * Update the specified leave request.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengedit pengajuan cuti.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher || $leaveRequest->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow updating pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Hanya pengajuan cuti yang masih pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:' . implode(',', array_keys(LeaveRequest::getLeaveTypes())),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total days
        $totalDays = Carbon::parse($validated['start_date'])
            ->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        // Handle file upload
        $attachmentPath = $leaveRequest->attachment_path;
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $leaveRequest->update([
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat menghapus pengajuan cuti.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher || $leaveRequest->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deleting pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Hanya pengajuan cuti yang masih pending yang dapat dihapus.');
        }

        // Delete attachment if exists
        if ($leaveRequest->attachment_path) {
            Storage::disk('public')->delete($leaveRequest->attachment_path);
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    /**
     * Approve or reject a leave request (Admin only).
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'bendahara'])) {
            abort(403, 'Hanya admin yang dapat menyetujui/menolak pengajuan cuti.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        $statusText = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('leave-requests.index')
            ->with('success', "Pengajuan cuti berhasil {$statusText}.");
    }
}
