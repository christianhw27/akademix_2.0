<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends Controller
{
    protected function getStudentProfile()
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            return $user->student;
        } elseif ($user->role === 'parent') {
            $studentId = session('active_student_id');
            if ($studentId) {
                return Student::find($studentId);
            }
        }
        return null;
    }

    public function dashboard()
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();
        $classroom = null;
        $todaySchedules = collect();
        $stats = [
            'materials_count' => 0,
            'assignments_count' => 0,
            'izin_count' => 0,
            'sakit_count' => 0,
            'alpha_count' => 0,
        ];

        // Map PHP dayOfWeek (Carbon) to Indonesian day names
        $dayMap = [
            Carbon::MONDAY    => 'Senin',
            Carbon::TUESDAY   => 'Selasa',
            Carbon::WEDNESDAY => 'Rabu',
            Carbon::THURSDAY  => 'Kamis',
            Carbon::FRIDAY    => 'Jumat',
            Carbon::SATURDAY  => 'Sabtu',
            Carbon::SUNDAY    => 'Minggu',
        ];
        $todayName = $dayMap[Carbon::now()->dayOfWeek] ?? 'Senin';

        if ($activeYear) {
            $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->with('studyClass')->first();

            if ($classroom) {
                // Today's schedules only
                $todaySchedules = Schedule::where('classroom_id', $classroom->id)
                    ->where('day_of_week', $todayName)
                    ->with(['teacher.user', 'subject'])
                    ->orderBy('start_time')
                    ->get();

                // Stats
                $materialsCount = Material::where('classroom_id', $classroom->id)->count();
                $assignmentsCount = Assignment::where('classroom_id', $classroom->id)->count();

                // Attendance breakdown
                $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })->get();

                $stats = [
                    'materials_count' => $materialsCount,
                    'assignments_count' => $assignmentsCount,
                    'izin_count' => $attendanceRecords->where('status', 'izin')->count(),
                    'sakit_count' => $attendanceRecords->where('status', 'sakit')->count(),
                    'alpha_count' => $attendanceRecords->where('status', 'alpha')->count(),
                ];
            }
        }

        return view('student.dashboard', compact('student', 'activeYear', 'classroom', 'todaySchedules', 'todayName', 'stats'));
    }

    public function materials(Request $request)
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();
        $classroom = null;
        $schedulesByDay = collect();

        if ($activeYear) {
            $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->with('studyClass')->first();
            if ($classroom) {
                $schedules = Schedule::where('classroom_id', $classroom->id)
                    ->with(['subject', 'teacher.user'])
                    ->orderBy('start_time')
                    ->get();
                $schedulesByDay = $schedules->groupBy('day_of_week');
            }
        }

        return view('student.materials.index', compact('student', 'activeYear', 'classroom', 'schedulesByDay'));
    }

    public function viewMaterial(Material $material)
    {
        $student = $this->getStudentProfile();
        
        // Ensure student has access to this classroom
        $hasAccess = $student->classrooms()->where('classrooms.id', $material->classroom_id)->exists();
        if (!$hasAccess) {
            abort(403);
        }

        return view('student.materials.show', compact('material', 'student'));
    }

    public function assignments()
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();
        $classroom = null;
        $subjects = collect();

        if ($activeYear) {
            $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->with('studyClass')->first();
            if ($classroom) {
                // Get all subjects scheduled, or having materials or assignments in this classroom
                $subjectIds = collect()
                    ->merge(Schedule::where('classroom_id', $classroom->id)->pluck('subject_id'))
                    ->merge(Material::where('classroom_id', $classroom->id)->pluck('subject_id'))
                    ->merge(Assignment::where('classroom_id', $classroom->id)->pluck('subject_id'))
                    ->unique();

                $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->get()->map(function($subject) use ($classroom) {
                    // Materials
                    $subject->materials = Material::where('classroom_id', $classroom->id)
                        ->where('subject_id', $subject->id)
                        ->with('teacher.user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
                    // Assignments
                    $subject->assignments = Assignment::where('classroom_id', $classroom->id)
                        ->where('subject_id', $subject->id)
                        ->with('teacher.user')
                        ->orderBy('due_date', 'asc')
                        ->get();
                        
                    $subject->materials_count = $subject->materials->count();
                    $subject->assignments_count = $subject->assignments->count();
                    
                    return $subject;
                });
            }
        }

        // Load student submissions
        $submissions = AssignmentSubmission::where('student_id', $student->id)
            ->get()
            ->keyBy('assignment_id');

        return view('student.assignments.index', compact('student', 'classroom', 'activeYear', 'subjects', 'submissions'));
    }

    public function viewAssignment(Assignment $assignment)
    {
        $student = $this->getStudentProfile();
        
        // Ensure student has access
        $hasAccess = $student->classrooms()->where('classrooms.id', $assignment->classroom_id)->exists();
        if (!$hasAccess) {
            abort(403);
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'submission', 'student'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $student = $this->getStudentProfile();
        if (auth()->user()->role === 'parent') {
            return back()->withErrors(['error' => 'Orang tua hanya dapat melihat tugas, bukan mengumpulkannya.']);
        }

        // Ensure student has access
        $hasAccess = $student->classrooms()->where('classrooms.id', $assignment->classroom_id)->exists();
        if (!$hasAccess) {
            abort(403);
        }

        $request->validate([
            'content'       => 'nullable|string',
            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,zip,rar|max:20480',
        ]);

        if (!$request->filled('content') && !$request->hasFile('attachments')) {
            return back()->withErrors(['content' => 'Harap isi jawaban atau lampirkan file.'])->withInput();
        }

        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)->first();

        // Keep existing files then append new ones
        $existingPaths = $existing->attachment ?? [];
        $removeIndexes = array_filter(explode(',', $request->input('remove_indexes', '')), fn($v) => $v !== '');
        foreach ($removeIndexes as $idx) {
            if (isset($existingPaths[(int)$idx])) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPaths[(int)$idx]);
                unset($existingPaths[(int)$idx]);
            }
        }
        $existingPaths = array_values($existingPaths);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $existingPaths[] = $file->storeAs('submissions', uniqid() . '---' . $file->getClientOriginalName(), 'public');
            }
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id'    => $student->id,
            ],
            [
                'content'      => $request->content ?? ($existing->content ?? ''),
                'status'       => 'submitted',
                'submitted_at' => now(),
                'attachment'   => empty($existingPaths) ? null : $existingPaths,
            ]
        );

        return redirect()->route('student.assignments.show', $assignment->id)->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function attendance(Request $request)
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();
        $classroom = null;
        
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // Previous and Next Month calculations
        $prevMonthDate = $startOfMonth->copy()->subMonth();
        $nextMonthDate = $startOfMonth->copy()->addMonth();

        $prevMonth = $prevMonthDate->month;
        $prevYear = $prevMonthDate->year;
        $nextMonth = $nextMonthDate->month;
        $nextYear = $nextMonthDate->year;

        $monthsMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $displayMonthName = $monthsMap[$month] . ' ' . $year;

        $attendanceRecords = collect();
        $dailyStatuses = [];
        $summary = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];

        if ($activeYear) {
            $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->first();
            if ($classroom) {
                // Fetch attendance records for this student in this month and classroom
                $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($classroom, $startOfMonth, $endOfMonth) {
                        $q->where('classroom_id', $classroom->id)
                          ->whereBetween('attendance_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
                    })
                    ->with(['session.subject', 'session.teacher'])
                    ->get();

                // Group by day of month
                $recordsByDay = $attendanceRecords->groupBy(function($record) {
                    return (int) Carbon::parse($record->session->attendance_date)->day;
                });

                // Calculate daily status
                for ($day = 1; $day <= $endOfMonth->day; $day++) {
                    $dayOfWeek = Carbon::create($year, $month, $day)->dayOfWeek;
                    
                    if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
                        $dailyStatuses[$day] = 'LIBUR';
                    } elseif ($recordsByDay->has($day)) {
                        $dayRecords = $recordsByDay->get($day);
                        $hasAlpha = $dayRecords->contains('status', 'alpha');
                        $hasExcuse = $dayRecords->contains(function($r) {
                            return in_array($r->status, ['izin', 'sakit']);
                        });

                        if ($hasAlpha) {
                            $dailyStatuses[$day] = 'TIDAK HADIR';
                        } elseif ($hasExcuse) {
                            $dailyStatuses[$day] = 'SEBAGIAN';
                        } else {
                            $dailyStatuses[$day] = 'HADIR PENUH';
                        }
                    } else {
                        // Weekday but no record yet
                        $dailyStatuses[$day] = null;
                    }
                }

                // Summary for all time in the active classroom/semester
                $allSemesterRecords = AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })
                    ->get();

                $summary = [
                    'hadir' => $allSemesterRecords->where('status', 'hadir')->count(),
                    'izin' => $allSemesterRecords->where('status', 'izin')->count(),
                    'sakit' => $allSemesterRecords->where('status', 'sakit')->count(),
                    'alpha' => $allSemesterRecords->where('status', 'alpha')->count(),
                ];
            }
        }

        // Build the calendar grid (Sunday = 0 to Saturday = 6)
        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 for Sunday
        
        $calendarWeeks = [];
        $currentWeek = array_fill(0, $firstDayOfWeek, null);

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $currentWeek[] = $day;
            if (count($currentWeek) === 7) {
                $calendarWeeks[] = $currentWeek;
                $currentWeek = [];
            }
        }
        if (count($currentWeek) > 0) {
            $calendarWeeks[] = array_merge($currentWeek, array_fill(0, 7 - count($currentWeek), null));
        }

        return view('student.attendance', compact(
            'student', 'classroom', 'activeYear', 'calendarWeeks', 'dailyStatuses', 'recordsByDay',
            'summary', 'displayMonthName', 'year', 'month', 
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    public function grades()
    {
        $student = $this->getStudentProfile();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Profil siswa tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();

        // Load only 'rapor' grade_type grades for this student
        $raporGrades = Grade::where('student_id', $student->id)
            ->where('grade_type', 'rapor')
            ->with(['subject', 'teacher.user', 'academicYear'])
            ->get()
            ->sortByDesc(function($grade) {
                return $grade->academic_year_id;
            });

        return view('student.grades', compact('student', 'raporGrades', 'activeYear'));
    }
}
