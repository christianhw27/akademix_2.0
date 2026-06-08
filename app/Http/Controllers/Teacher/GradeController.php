<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            $grades = collect();
        } else {
            $query = Grade::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['student.user', 'classroom.studyClass', 'subject'])
                ->orderBy('created_at', 'desc');

            if ($request->filled('classroom_id')) {
                $query->where('classroom_id', $request->classroom_id);
            }
            if ($request->filled('grade_type')) {
                $query->where('grade_type', $request->grade_type);
            }

            $grades = $query->get();
        }

        // Get unique classrooms for filter
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        return view('teacher.grades.index', compact('grades', 'classrooms', 'activeYear'));
    }

    public function create(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.grades.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();

        $selectedClassroom = null;
        $selectedSubject = null;
        $students = collect();

        if ($request->filled('classroom_id') && $request->filled('subject_id')) {
            $selectedClassroom = Classroom::find($request->classroom_id);
            $selectedSubject = Subject::find($request->subject_id);

            $allowed = Schedule::where('teacher_id', $teacher->id)
                ->where('classroom_id', $request->classroom_id)
                ->where('subject_id', $request->subject_id)
                ->exists();

            if ($allowed && $selectedClassroom) {
                $students = $selectedClassroom->students()->with('user')->get();
            } else {
                return redirect()->route('teacher.grades.create')->withErrors(['error' => 'Anda tidak memiliki jadwal mengajar di kelas ini untuk mata pelajaran tersebut.']);
            }
        }

        return view('teacher.grades.create', compact('classrooms', 'subjects', 'selectedClassroom', 'selectedSubject', 'students'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.grades.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_type' => 'required|in:harian,tugas,rapor',
            'title' => 'required|string|max:150',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $teacher, $activeYear) {
            foreach ($request->scores as $studentId => $score) {
                if ($score !== null && $score !== '') {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'subject_id' => $request->subject_id,
                            'classroom_id' => $request->classroom_id,
                            'academic_year_id' => $activeYear->id,
                            'semester' => $activeYear->semester,
                            'grade_type' => $request->grade_type,
                            'title' => $request->title,
                        ],
                        [
                            'teacher_id' => $teacher->id,
                            'score' => $score,
                            'notes' => $request->notes[$studentId] ?? null,
                        ]
                    );
                }
            }
        });

        return redirect()->route('teacher.grades.index')->with('success', 'Nilai siswa berhasil disimpan.');
    }

    public function edit(Grade $grade)
    {
        $teacher = auth()->user()->teacher;

        if ($grade->teacher_id !== $teacher->id) {
            abort(403);
        }

        return view('teacher.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $teacher = auth()->user()->teacher;

        if ($grade->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $grade->update([
            'score' => $request->score,
            'notes' => $request->notes,
        ]);

        return redirect()->route('teacher.grades.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    public function destroy(Grade $grade)
    {
        $teacher = auth()->user()->teacher;

        if ($grade->teacher_id !== $teacher->id) {
            abort(403);
        }

        $grade->delete();
        return redirect()->route('teacher.grades.index')->with('success', 'Nilai siswa berhasil dihapus.');
    }
}
