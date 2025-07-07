<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Teacher;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            // Guru hanya bisa melihat gaji dirinya sendiri
            $teacher = $user->teacher;
            $salaries = Salary::where('teacher_id', $teacher->id)
                            ->when($request->year, function($query, $year) {
                                return $query->where('tahun', $year);
                            })
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->paginate(10);
        } else {
            // Admin dan bendahara bisa melihat semua gaji
            $salaries = Salary::with('teacher')
                            ->when($request->teacher_id, function($query, $teacherId) {
                                return $query->where('teacher_id', $teacherId);
                            })
                            ->when($request->year, function($query, $year) {
                                return $query->where('tahun', $year);
                            })
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->paginate(10);
        }

        $teachers = Teacher::where('is_active', true)->get();

        return view('salaries.index', compact('salaries', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('salaries.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2020|max:2030',
            'bonus' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $teacher = Teacher::findOrFail($validated['teacher_id']);

        // Hitung hari kerja dan hari hadir berdasarkan absensi
        // Parse bulan dari string ke number
        $monthMapping = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $monthNumber = is_numeric($validated['bulan'])
            ? (int) $validated['bulan']
            : ($monthMapping[$validated['bulan']] ?? 1);

        $startDate = Carbon::create($validated['tahun'], $monthNumber, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $hariKerja = $this->calculateWorkingDays($startDate, $endDate);
        $hariHadir = Attendance::where('teacher_id', $teacher->id)
                             ->whereBetween('tanggal', [$startDate, $endDate])
                             ->where('status', 'hadir')
                             ->count();
        $hariTidakHadir = $hariKerja - $hariHadir;

        // Calculate salary based on salary type
        $nominalGaji = $teacher->nominal ?? $teacher->gaji_pokok ?? 0;
        $salaryType = $teacher->salary_type ?? 'per_bulan';

        switch ($salaryType) {
            case 'per_hari':
                $gajiPokok = $nominalGaji * $hariHadir;
                break;
            case 'per_jam':
                // For per_jam, we need to get working hours - for now use 8 hours per day
                $jamKerja = $hariHadir * 8; // This could be enhanced to track actual hours
                $gajiPokok = $nominalGaji * $jamKerja;
                break;
            case 'per_bulan':
            default:
                $gajiPokok = $nominalGaji; // Fixed monthly salary
                break;
        }

        // Calculate allowances with their calculation types
        $totalTunjangan = 0;

        foreach ($teacher->teacherAllowances()->where('is_active', true)->get() as $allowance) {
            $calculationType = $allowance->calculation_type ?? 'fixed';
            $allowanceAmount = 0;

            switch ($calculationType) {
                case 'per_hari':
                    $allowanceAmount = $allowance->amount * $hariHadir;
                    break;
                case 'per_bulan':
                    $allowanceAmount = $allowance->amount;
                    break;
                case 'fixed':
                default:
                    $allowanceAmount = $allowance->amount;
                    break;
            }

            $totalTunjangan += $allowanceAmount;
        }

        // Add position-based allowances with their calculation types
        if ($teacher->positions) {
            foreach ($teacher->positions as $position) {
                if ($position->base_allowance > 0) {
                    // For now, position allowances are treated as fixed monthly
                    // This could be enhanced to support different calculation types for positions
                    $totalTunjangan += $position->base_allowance;
                }
            }
        }

        $totalGaji = $gajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

        Salary::create([
            'teacher_id' => $validated['teacher_id'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'gaji_pokok' => $gajiPokok,
            'tunjangan' => $totalTunjangan,
            'bonus' => $validated['bonus'] ?? 0,
            'potongan' => $validated['potongan'] ?? 0,
            'hari_kerja' => $hariKerja,
            'hari_hadir' => $hariHadir,
            'hari_tidak_hadir' => $hariTidakHadir,
            'total_gaji' => $totalGaji,
            'status' => 'draft',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        $user = Auth::user();

        // Guru hanya bisa melihat gaji dirinya sendiri
        if ($user->role === 'guru' && $salary->teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('salaries.show', compact('salary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('salaries.edit', compact('salary', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'bonus' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,approved,paid',
            'keterangan' => 'nullable|string',
        ]);

        // Recalculate total gaji based on the teacher's salary system
        $teacher = $salary->teacher;
        $salaryType = $teacher->salary_type ?? 'per_bulan';

        // The gaji_pokok in the salary record should already be calculated correctly
        // based on the salary type when it was created, so we just use it as is
        $totalGaji = $salary->gaji_pokok + $salary->tunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

        $salary->update([
            'bonus' => $validated['bonus'] ?? 0,
            'potongan' => $validated['potongan'] ?? 0,
            'total_gaji' => $totalGaji,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil dihapus.');
    }

    /**
     * Show form for bulk salary generation
     */
    public function bulkCreate()
    {
        $user = Auth::user();

        if ($user && $user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        return view('salaries.bulk-create');
    }

    /**
     * Generate salaries for all active teachers
     */
    public function bulkStore(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        try {
            $validated = $request->validate([
                'bulan' => 'required|string',
                'tahun' => 'required|integer|min:2020|max:2030',
                'bonus' => 'nullable|numeric|min:0',
                'potongan' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);

            $monthMapping = [
                'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
                'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
                'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
            ];

            $monthNumber = is_numeric($validated['bulan'])
                ? (int) $validated['bulan']
                : ($monthMapping[$validated['bulan']] ?? 1);

            $startDate = Carbon::create($validated['tahun'], $monthNumber, 1);
            $endDate = $startDate->copy()->endOfMonth();

            $teachers = Teacher::where('is_active', true)->get();
            $successCount = 0;
            $skippedCount = 0;

            foreach ($teachers as $teacher) {
                // Check if salary already exists for this month/year
                $existingSalary = Salary::where('teacher_id', $teacher->id)
                                       ->where('bulan', $validated['bulan'])
                                   ->where('tahun', $validated['tahun'])
                                   ->first();

            if ($existingSalary) {
                $skippedCount++;
                continue;
            }

            // Calculate working days and attendance
            $hariKerja = $this->calculateWorkingDays($startDate, $endDate);
            $hariHadir = Attendance::where('teacher_id', $teacher->id)
                                 ->whereBetween('tanggal', [$startDate, $endDate])
                                 ->where('status', 'hadir')
                                 ->count();
            $hariTidakHadir = $hariKerja - $hariHadir;

            // Calculate salary based on salary type
            $nominalGaji = $teacher->nominal ?? $teacher->gaji_pokok ?? 0;
            $salaryType = $teacher->salary_type ?? 'per_bulan';

            if ($nominalGaji == 0) {
                // If no salary is set, skip this teacher
                $skippedCount++;
                continue;
            }

            switch ($salaryType) {
                case 'per_hari':
                    $gajiPokok = $nominalGaji * $hariHadir;
                    break;
                case 'per_jam':
                    // For per_jam, we need to get working hours - for now use 8 hours per day
                    $jamKerja = $hariHadir * 8; // This could be enhanced to track actual hours
                    $gajiPokok = $nominalGaji * $jamKerja;
                    break;
                case 'per_bulan':
                default:
                    $gajiPokok = $nominalGaji; // Fixed monthly salary
                    break;
            }

            // Calculate allowances with their calculation types
            $totalTunjangan = 0;
            $tunjanganDetails = [];

            // Get all active allowances for this teacher
            $teacherAllowances = $teacher->teacherAllowances()
                                         ->with('allowanceType')
                                         ->where('is_active', true)
                                         ->get();

            foreach ($teacherAllowances as $teacherAllowance) {
                $calculationType = $teacherAllowance->calculation_type ?? 'fixed';
                $allowanceAmount = 0;

                switch ($calculationType) {
                    case 'per_hari':
                        $allowanceAmount = $teacherAllowance->amount * $hariHadir;
                        break;
                    case 'per_bulan':
                        $allowanceAmount = $teacherAllowance->amount;
                        break;
                    case 'fixed':
                    default:
                        $allowanceAmount = $teacherAllowance->amount;
                        break;
                }

                $totalTunjangan += $allowanceAmount;
                $tunjanganDetails[] = [
                    'type' => $teacherAllowance->allowanceType->name,
                    'amount' => $allowanceAmount,
                    'calculation' => $calculationType
                ];
            }

            // Add position-based allowances with their calculation types
            if ($teacher->positions) {
                foreach ($teacher->positions as $position) {
                    if ($position->base_allowance > 0) {
                        // For now, position allowances are treated as fixed monthly
                        $positionAllowance = $position->base_allowance;
                        $totalTunjangan += $positionAllowance;
                        $tunjanganDetails[] = [
                            'type' => 'Tunjangan Jabatan (' . $position->name . ')',
                            'amount' => $positionAllowance,
                            'calculation' => 'fixed'
                        ];
                    }
                }
            }

            $totalGaji = $gajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

            // Prepare keterangan with allowance details including calculation types
            $keteranganWithDetails = $validated['keterangan'] ?? '';
            if (!empty($tunjanganDetails)) {
                $keteranganWithDetails .= (empty($keteranganWithDetails) ? '' : ' | ') . 'Tunjangan: ';
                $allowanceList = [];
                foreach ($tunjanganDetails as $detail) {
                    $calcText = $detail['calculation'] == 'fixed' ? '' : ' (' . str_replace('_', ' ', $detail['calculation']) . ')';
                    $allowanceList[] = $detail['type'] . $calcText . ' (Rp ' . number_format($detail['amount'], 0, ',', '.') . ')';
                }
                $keteranganWithDetails .= implode(', ', $allowanceList);
            }

            // Create salary record
            Salary::create([
                'teacher_id' => $teacher->id,
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $gajiPokok,
                'tunjangan' => $totalTunjangan,
                'bonus' => $validated['bonus'] ?? 0,
                'potongan' => $validated['potongan'] ?? 0,
                'hari_kerja' => $hariKerja,
                'hari_hadir' => $hariHadir,
                'hari_tidak_hadir' => $hariTidakHadir,
                'total_gaji' => $totalGaji,
                'status' => 'draft',
                'keterangan' => $keteranganWithDetails,
            ]);

            $successCount++;
        }        $message = "Berhasil generate gaji untuk {$successCount} guru.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} guru dilewati karena sudah ada data gaji untuk periode tersebut.";
        }

        return redirect()->route('salaries.index')->with('success', $message);

        } catch (\Exception $e) {
            // Handle any exceptions
            $errorMessage = 'Terjadi kesalahan saat memproses generate gaji: ' . $e->getMessage();

            return redirect()->route('salaries.bulk-create')->with('error', $errorMessage);
        }
    }

    /**
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // Skip Sunday (0) and Saturday (6)
            if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }
}
