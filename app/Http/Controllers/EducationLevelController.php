<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationLevelController extends Controller
{
    /**
     * Display a listing of the education levels.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $educationLevels = EducationLevel::with('teachers')
            ->orderBy('level_order')
            ->paginate(10);

        return view('education-levels.index', compact('educationLevels'));
    }

    /**
     * Show the form for creating a new education level.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('education-levels.create');
    }

    /**
     * Store a newly created education level in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:education_levels,name',
            'full_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level_order' => 'required|integer|min:1',
        ]);

        EducationLevel::create($validated);

        return redirect()->route('education-levels.index')
            ->with('success', 'Education level created successfully.');
    }

    /**
     * Display the specified education level.
     */
    public function show(EducationLevel $educationLevel)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $educationLevel->load('teachers');

        return view('education-levels.show', compact('educationLevel'));
    }

    /**
     * Show the form for editing the specified education level.
     */
    public function edit(EducationLevel $educationLevel)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('education-levels.edit', compact('educationLevel'));
    }

    /**
     * Update the specified education level in storage.
     */
    public function update(Request $request, EducationLevel $educationLevel)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:education_levels,name,' . $educationLevel->id,
            'full_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level_order' => 'required|integer|min:1',
        ]);

        $educationLevel->update($validated);

        return redirect()->route('education-levels.index')
            ->with('success', 'Education level updated successfully.');
    }

    /**
     * Remove the specified education level from storage.
     */
    public function destroy(EducationLevel $educationLevel)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Check if any teachers are assigned to this education level
        if ($educationLevel->teachers()->count() > 0) {
            return redirect()->route('education-levels.index')
                ->with('error', 'Cannot delete education level that is assigned to teachers.');
        }

        $educationLevel->delete();

        return redirect()->route('education-levels.index')
            ->with('success', 'Education level deleted successfully.');
    }

    /**
     * Toggle the status of the specified education level.
     */
    public function toggleStatus(EducationLevel $educationLevel)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $educationLevel->update(['is_active' => !$educationLevel->is_active]);

        $status = $educationLevel->is_active ? 'activated' : 'deactivated';

        return redirect()->route('education-levels.index')
            ->with('success', "Education level has been {$status} successfully.");
    }
}
