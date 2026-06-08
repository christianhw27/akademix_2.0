<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            $sessions = collect();
        } else {
            $sessions = AttendanceSession::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.studyClass', 'subject'])
                ->orderBy('attendance_date', 'desc')
                ->get();
        }

        return view('teacher.attendance.index', compact('sessions', 'activeYear'));
    }

    public function create(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.attendance.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
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

            // Double check if teacher is allowed to teach this combination
            $allowed = Schedule::where('teacher_id', $teacher->id)
                ->where('classroom_id', $request->classroom_id)
                ->where('subject_id', $request->subject_id)
                ->exists();

            if ($allowed && $selectedClassroom) {
                $students = $selectedClassroom->students()->with('user')->get();
            } else {
                return redirect()->route('teacher.attendance.create')->withErrors(['error' => 'Anda tidak memiliki jadwal mengajar di kelas ini untuk mata pelajaran tersebut.']);
            }
        }

        return view('teacher.attendance.create', compact('classrooms', 'subjects', 'selectedClassroom', 'selectedSubject', 'students'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.attendance.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendance_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'attendance' => 'required|array',
            'attendance.*' => 'in:hadir,izin,sakit,alpha',
            'record_notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $teacher, $activeYear) {
            $session = AttendanceSession::create([
                'classroom_id' => $request->classroom_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $teacher->id,
                'academic_year_id' => $activeYear->id,
                'attendance_date' => $request->attendance_date,
                'notes' => $request->notes,
            ]);

            foreach ($request->attendance as $studentId => $status) {
                AttendanceRecord::create([
                    'attendance_session_id' => $session->id,
                    'student_id' => $studentId,
                    'status' => $status,
                    'notes' => $request->record_notes[$studentId] ?? null,
                ]);
            }
        });

        return redirect()->route('teacher.attendance.index')->with('success', 'Absensi berhasil dicatat.');
    }

    public function edit(AttendanceSession $session)
    {
        $teacher = auth()->user()->teacher;

        if ($session->teacher_id !== $teacher->id) {
            abort(403);
        }

        $students = $session->classroom->students()->with('user')->get();
        $records = $session->records->keyBy('student_id');

        return view('teacher.attendance.edit', compact('session', 'students', 'records'));
    }

    public function update(Request $request, AttendanceSession $session)
    {
        $teacher = auth()->user()->teacher;

        if ($session->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'attendance_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'attendance' => 'required|array',
            'attendance.*' => 'in:hadir,izin,sakit,alpha',
            'record_notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $session) {
            $session->update([
                'attendance_date' => $request->attendance_date,
                'notes' => $request->notes,
            ]);

            foreach ($request->attendance as $studentId => $status) {
                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_session_id' => $session->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'status' => $status,
                        'notes' => $request->record_notes[$studentId] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('teacher.attendance.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $session)
    {
        $teacher = auth()->user()->teacher;

        if ($session->teacher_id !== $teacher->id) {
            abort(403);
        }

        $session->delete();
        return redirect()->route('teacher.attendance.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
