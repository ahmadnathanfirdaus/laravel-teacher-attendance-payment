<?php

namespace App\Http\Controllers;

use App\Models\AllowanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowanceTypeController extends Controller
{
    /**
     * Display a listing of the allowance types.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $allowanceTypes = AllowanceType::with('teacherAllowances')->paginate(10);

        return view('allowance-types.index', compact('allowanceTypes'));
    }

    /**
     * Show the form for creating a new allowance type.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('allowance-types.create');
    }

    /**
     * Store a newly created allowance type in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calculation_type' => 'required|in:per_hari,per_bulan',
            'default_amount' => 'required|numeric|min:0',
        ]);

        AllowanceType::create($validated);

        return redirect()->route('allowance-types.index')
            ->with('success', 'Allowance type created successfully.');
    }

    /**
     * Display the specified allowance type.
     */
    public function show(AllowanceType $allowanceType)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $allowanceType->load('teacherAllowances.teacher');

        return view('allowance-types.show', compact('allowanceType'));
    }

    /**
     * Show the form for editing the specified allowance type.
     */
    public function edit(AllowanceType $allowanceType)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('allowance-types.edit', compact('allowanceType'));
    }

    /**
     * Update the specified allowance type in storage.
     */
    public function update(Request $request, AllowanceType $allowanceType)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calculation_type' => 'required|in:per_hari,per_bulan',
            'default_amount' => 'required|numeric|min:0',
        ]);

        $allowanceType->update($validated);

        return redirect()->route('allowance-types.index')
            ->with('success', 'Allowance type updated successfully.');
    }

    /**
     * Remove the specified allowance type from storage.
     */
    public function destroy(AllowanceType $allowanceType)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Check if any teacher allowances are using this type
        if ($allowanceType->teacherAllowances()->count() > 0) {
            return redirect()->route('allowance-types.index')
                ->with('error', 'Cannot delete allowance type that is assigned to teachers.');
        }

        $allowanceType->delete();

        return redirect()->route('allowance-types.index')
            ->with('success', 'Allowance type deleted successfully.');
    }

    /**
     * Toggle the status of the specified allowance type.
     */
    public function toggleStatus(AllowanceType $allowanceType)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $allowanceType->update(['is_active' => !$allowanceType->is_active]);

        $status = $allowanceType->is_active ? 'activated' : 'deactivated';

        return redirect()->route('allowance-types.index')
            ->with('success', "Allowance type has been {$status} successfully.");
    }
}
