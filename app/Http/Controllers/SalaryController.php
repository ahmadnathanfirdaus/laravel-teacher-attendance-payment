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

        // Hitung total gaji
        $gajiPokok = $teacher->position ? $teacher->position->base_salary : $teacher->gaji_pokok;
        $gajiHarian = $gajiPokok / $hariKerja;
        $totalGajiPokok = $gajiHarian * $hariHadir;

        // Hitung total tunjangan dari berbagai jenis tunjangan yang aktif
        // Include allowances that are effective within the salary month
        $salaryStartDate = Carbon::create($validated['tahun'], $monthNumber, 1);
        $salaryEndDate = $salaryStartDate->copy()->endOfMonth();

        $totalTunjangan = $teacher->teacherAllowances()
                                 ->where('is_active', true)
                                 ->where('effective_date', '<=', $salaryEndDate)
                                 ->where(function($query) use ($salaryStartDate) {
                                     $query->whereNull('end_date')
                                           ->orWhere('end_date', '>=', $salaryStartDate);
                                 })
                                 ->sum('amount');

        // Fallback ke tunjangan lama jika tidak ada tunjangan baru
        if ($totalTunjangan == 0) {
            $totalTunjangan = $teacher->tunjangan ?? 0;
        }

        $totalGaji = $totalGajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

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

        // Recalculate total gaji
        $totalGaji = ($salary->gaji_pokok / $salary->hari_kerja * $salary->hari_hadir)
                   + $salary->tunjangan
                   + ($validated['bonus'] ?? 0)
                   - ($validated['potongan'] ?? 0);

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

            // Calculate salary
            $gajiPokok = 0;
            if ($teacher->position && $teacher->position->base_salary > 0) {
                $gajiPokok = $teacher->position->base_salary;
            } elseif ($teacher->gaji_pokok > 0) {
                $gajiPokok = $teacher->gaji_pokok;
            }

            if ($gajiPokok == 0) {
                // If no salary is set, skip this teacher
                $skippedCount++;
                continue;
            }

            $gajiHarian = $gajiPokok / $hariKerja;
            $totalGajiPokok = $gajiHarian * $hariHadir;

            // Calculate total allowances with detailed breakdown
            $totalTunjangan = 0;
            $tunjanganDetails = [];

            // Get all active allowances for this teacher
            // Include allowances that are effective within the salary month
            $salaryStartDate = Carbon::create($validated['tahun'], $monthNumber, 1);
            $salaryEndDate = $salaryStartDate->copy()->endOfMonth();

            $teacherAllowances = $teacher->teacherAllowances()
                                         ->with('allowanceType')
                                         ->where('is_active', true)
                                         ->where('effective_date', '<=', $salaryEndDate)
                                         ->where(function($query) use ($salaryStartDate) {
                                             $query->whereNull('end_date')
                                                   ->orWhere('end_date', '>=', $salaryStartDate);
                                         })
                                         ->get();

            foreach ($teacherAllowances as $teacherAllowance) {
                $allowanceAmount = $teacherAllowance->amount;
                $totalTunjangan += $allowanceAmount;
                $tunjanganDetails[] = [
                    'type' => $teacherAllowance->allowanceType->name,
                    'amount' => $allowanceAmount
                ];
            }

            // If no specific allowances found, use the general tunjangan field
            if ($totalTunjangan == 0 && $teacher->tunjangan > 0) {
                $totalTunjangan = $teacher->tunjangan;
                $tunjanganDetails[] = [
                    'type' => 'Tunjangan Umum',
                    'amount' => $teacher->tunjangan
                ];
            }

            $totalGaji = $totalGajiPokok + $totalTunjangan + ($validated['bonus'] ?? 0) - ($validated['potongan'] ?? 0);

            // Prepare keterangan with allowance details
            $keteranganWithDetails = $validated['keterangan'] ?? '';
            if (!empty($tunjanganDetails)) {
                $keteranganWithDetails .= (empty($keteranganWithDetails) ? '' : ' | ') . 'Tunjangan: ';
                $allowanceList = [];
                foreach ($tunjanganDetails as $detail) {
                    $allowanceList[] = $detail['type'] . ' (Rp ' . number_format($detail['amount'], 0, ',', '.') . ')';
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
