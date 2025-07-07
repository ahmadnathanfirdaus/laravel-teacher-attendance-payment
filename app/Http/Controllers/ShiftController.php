<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    private function checkAdminRole()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    public function index()
    {
        $this->checkAdminRole();
        $shifts = Shift::orderBy('name')->get();
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        $this->checkAdminRole();
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $this->checkAdminRole();

        $request->validate([
            'name' => 'required|string|max:255|unique:shifts',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        Shift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function show(Shift $shift)
    {
        $this->checkAdminRole();
        return view('shifts.show', compact('shift'));
    }

    public function edit(Shift $shift)
    {
        $this->checkAdminRole();
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $this->checkAdminRole();

        $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name,' . $shift->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(Shift $shift)
    {
        $this->checkAdminRole();

        // Check if shift is being used by teachers
        if ($shift->teachers()->count() > 0) {
            return redirect()->route('shifts.index')->with('error', 'Shift tidak dapat dihapus karena masih digunakan oleh guru.');
        }

        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift berhasil dihapus.');
    }

    public function toggleStatus(Shift $shift)
    {
        $this->checkAdminRole();

        $shift->update(['is_active' => !$shift->is_active]);

        $status = $shift->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('shifts.index')->with('success', "Shift berhasil {$status}.");
    }
}
