<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        return view('admin.academic_years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('admin.academic_years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_label' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->is_active) {
                // Deactivate all others
                AcademicYear::query()->update(['is_active' => 0]);
            }

            AcademicYear::create($request->all());
        });

        return redirect()->route('admin.academic_years.index')->with('success', 'Tahun ajaran baru berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic_years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'year_label' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $academicYear) {
            if ($request->is_active) {
                // Deactivate all others
                AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => 0]);
            }

            $academicYear->update($request->all());
        });

        return redirect()->route('admin.academic_years.index')->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    public function activate(AcademicYear $academicYear)
    {
        DB::transaction(function () use ($academicYear) {
            AcademicYear::query()->update(['is_active' => 0]);
            $academicYear->update(['is_active' => 1]);
        });

        return redirect()->route('admin.academic_years.index')->with('success', "Tahun ajaran {$academicYear->year_label} ({$academicYear->semester}) sekarang aktif.");
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return back()->withErrors(['error' => 'Tahun ajaran aktif tidak dapat dihapus. Silakan aktifkan tahun ajaran lain terlebih dahulu.']);
        }

        $academicYear->delete();
        return redirect()->route('admin.academic_years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
