<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\StudyClass;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\Student;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with(['academicYear', 'studyClass', 'homeroomTeacher.user'])
            ->withCount('students')
            ->orderBy('grade_level')
            ->get();
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $classes = StudyClass::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.create', compact('academicYears', 'classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'grade_level' => 'required|integer|min:1|max:12',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Check if classroom already exists for the academic year and class
        $exists = Classroom::where('academic_year_id', $request->academic_year_id)
            ->where('class_id', $request->class_id)
            ->where('grade_level', $request->grade_level)
            ->exists();

        if ($exists) {
            return back()->withErrors(['class_id' => 'Kelas dengan tingkat dan tahun ajaran tersebut sudah ada.'])->withInput();
        }

        Classroom::create($request->all());

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(Classroom $classroom)
    {
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $classes = StudyClass::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.edit', compact('classroom', 'academicYears', 'classes', 'teachers'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'grade_level' => 'required|integer|min:1|max:12',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Check if duplicate classroom exists
        $exists = Classroom::where('academic_year_id', $request->academic_year_id)
            ->where('class_id', $request->class_id)
            ->where('grade_level', $request->grade_level)
            ->where('id', '!=', $classroom->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['class_id' => 'Kelas serupa sudah ada di database untuk tahun ajaran ini.'])->withInput();
        }

        $classroom->update($request->all());

        return redirect()->route('admin.classrooms.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function manageStudents(Classroom $classroom)
    {
        $classroom->load(['academicYear', 'studyClass', 'students.user']);
        
        // Find all active students in the cohort/master database
        // Highlight students who are not assigned to ANY classroom in the SAME academic year
        $academicYearId = $classroom->academic_year_id;
        
        $assignedStudentIds = DB::table('classroom_students')
            ->join('classrooms', 'classroom_students.classroom_id', '=', 'classrooms.id')
            ->where('classrooms.academic_year_id', $academicYearId)
            ->where('classrooms.id', '!=', $classroom->id)
            ->pluck('classroom_students.student_id')
            ->toArray();

        $students = Student::with(['user', 'cohort'])
            ->whereNotIn('id', $assignedStudentIds)
            ->get();

        $currentStudentIds = $classroom->students->pluck('id')->toArray();

        return view('admin.classrooms.students', compact('classroom', 'students', 'currentStudentIds'));
    }

    public function saveStudents(Request $request, Classroom $classroom)
    {
        $request->validate([
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
        ]);

        $students = $request->input('students', []);
        $classroom->students()->sync($students);

        return redirect()->route('admin.classrooms.index')->with('success', 'Anggota siswa kelas berhasil diperbarui.');
    }
}
