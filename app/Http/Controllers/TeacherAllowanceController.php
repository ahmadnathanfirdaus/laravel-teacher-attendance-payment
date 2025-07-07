<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\AllowanceType;
use App\Models\TeacherAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAllowanceController extends Controller
{
    /**
     * Display a listing of the teacher allowances.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $teacherAllowances = TeacherAllowance::with(['teacher', 'allowanceType'])
            ->paginate(10);

        return view('teacher-allowances.index', compact('teacherAllowances'));
    }

    /**
     * Show the form for creating a new teacher allowance.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::all();
        $allowanceTypes = AllowanceType::all();

        return view('teacher-allowances.create', compact('teachers', 'allowanceTypes'));
    }

    /**
     * Store a newly created teacher allowance in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'allowance_type_id' => 'required|exists:allowance_types,id',
            'amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'notes' => 'nullable|string',
        ]);

        // Check if teacher already has this allowance type
        $existingAllowance = TeacherAllowance::where('teacher_id', $validated['teacher_id'])
            ->where('allowance_type_id', $validated['allowance_type_id'])
            ->where('is_active', true)
            ->first();

        if ($existingAllowance) {
            return redirect()->back()
                ->withErrors(['allowance_type_id' => 'Teacher already has this allowance type assigned.'])
                ->withInput();
        }

        TeacherAllowance::create(array_merge($validated, ['is_active' => true]));

        return redirect()->route('teacher-allowances.index')
            ->with('success', 'Teacher allowance created successfully.');
    }

    /**
     * Display the specified teacher allowance.
     */
    public function show(TeacherAllowance $teacherAllowance)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('teacher-allowances.show', compact('teacherAllowance'));
    }

    /**
     * Show the form for editing the specified teacher allowance.
     */
    public function edit(TeacherAllowance $teacherAllowance)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::all();
        $allowanceTypes = AllowanceType::all();

        return view('teacher-allowances.edit', compact('teacherAllowance', 'teachers', 'allowanceTypes'));
    }

    /**
     * Update the specified teacher allowance in storage.
     */
    public function update(Request $request, TeacherAllowance $teacherAllowance)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'allowance_type_id' => 'required|exists:allowance_types,id',
            'amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'notes' => 'nullable|string',
        ]);

        // Check if teacher already has this allowance type (excluding current record)
        $existingAllowance = TeacherAllowance::where('teacher_id', $validated['teacher_id'])
            ->where('allowance_type_id', $validated['allowance_type_id'])
            ->where('is_active', true)
            ->where('id', '!=', $teacherAllowance->id)
            ->first();

        if ($existingAllowance) {
            return redirect()->back()
                ->withErrors(['allowance_type_id' => 'Teacher already has this allowance type assigned.'])
                ->withInput();
        }

        $teacherAllowance->update($validated);

        return redirect()->route('teacher-allowances.index')
            ->with('success', 'Teacher allowance updated successfully.');
    }

    /**
     * Remove the specified teacher allowance from storage.
     */
    public function destroy(TeacherAllowance $teacherAllowance)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $teacherAllowance->delete();

        return redirect()->route('teacher-allowances.index')
            ->with('success', 'Teacher allowance deleted successfully.');
    }

    /**
     * Toggle the status of the specified teacher allowance.
     */
    public function toggleStatus(TeacherAllowance $teacherAllowance)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $teacherAllowance->update(['is_active' => !$teacherAllowance->is_active]);

        $status = $teacherAllowance->is_active ? 'activated' : 'deactivated';

        return redirect()->route('teacher-allowances.index')
            ->with('success', "Teacher allowance has been {$status} successfully.");
    }
}
