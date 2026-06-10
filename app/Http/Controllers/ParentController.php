<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\Grade;
use Carbon\Carbon;

/**
 * Controller untuk mengelola fitur-fitur pemantauan yang dapat diakses oleh Orang Tua (Guardian).
 */
class ParentController extends Controller
{
    /**
     * Mengambil data siswa (anak) yang sedang aktif dipantau dalam session.
     *
     * @return \App\Models\Student|null
     */
    protected function getActiveStudent()
    {
        $studentId = session('active_student_id');
        if (!$studentId) {
            // Find first student of this guardian
            $guardian = auth()->user()->guardian;
            if ($guardian) {
                $student = $guardian->students()->first();
                if ($student) {
                    session(['active_student_id' => $student->id]);
                    return $student;
                }
            }
            return null;
        }
        return Student::find($studentId);
    }

    /**
     * Menampilkan dashboard pemantauan orang tua (profil anak, jadwal pelajaran, nilai rata-rata, dan kehadiran).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        $student = $this->getActiveStudent();
        if (!$student) {
            return redirect('/login')->withErrors(['username' => 'Data anak tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();
        $classroom = null;
        $schedules = collect();
        $stats = [
            'materials_count' => 0,
            'assignments_count' => 0,
            'attendance_rate' => 100,
            'average_score' => 0,
        ];

        if ($activeYear) {
            $classroom = $student->classrooms()->where('academic_year_id', $activeYear->id)->with('studyClass')->first();

            if ($classroom) {
                // Schedules
                $schedules = Schedule::where('classroom_id', $classroom->id)
                    ->with(['teacher', 'subject'])
                    ->get()
                    ->sortBy(function($schedule) {
                        $days = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7];
                        return [$days[strtolower($schedule->day_of_week)] ?? 8, $schedule->start_time];
                    });

                // Stats
                $materialsCount = Material::where('classroom_id', $classroom->id)->count();
                $assignmentsCount = Assignment::where('classroom_id', $classroom->id)->count();

                // Attendance
                $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', function($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })->get();
                
                $totalSessions = $attendanceRecords->count();
                $presentSessions = $attendanceRecords->where('status', 'hadir')->count();
                $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 1) : 100;

                // Grades average
                $averageScore = Grade::where('student_id', $student->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->avg('score') ?? 0;

                $stats = [
                    'materials_count' => $materialsCount,
                    'assignments_count' => $assignmentsCount,
                    'attendance_rate' => $attendanceRate,
                    'average_score' => round($averageScore, 1),
                ];
            }
        }

        return view('parent.dashboard', compact('student', 'activeYear', 'classroom', 'schedules', 'stats'));
    }

    /**
     * Menampilkan riwayat presensi bulanan anak dalam bentuk kalender akademik orang tua.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function attendance(Request $request)
    {
        $student = $this->getActiveStudent();
        if (!$student) {
            return redirect('/login');
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

        return view('parent.attendance', compact(
            'student', 'classroom', 'activeYear', 'calendarWeeks', 'dailyStatuses', 'recordsByDay',
            'summary', 'displayMonthName', 'year', 'month', 
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    /**
     * Menampilkan daftar materi dan tugas anak yang dikelompokkan berdasarkan mata pelajaran.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function assignments()
    {
        $student = $this->getActiveStudent();
        if (!$student) {
            return redirect('/login');
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

        return view('parent.assignments', compact('student', 'classroom', 'activeYear', 'subjects', 'submissions'));
    }

    /**
     * Menampilkan daftar nilai rapor akademik anak yang diperoleh selama sekolah.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function grades()
    {
        $student = $this->getActiveStudent();
        if (!$student) {
            return redirect('/login');
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

        return view('parent.grades', compact('student', 'raporGrades', 'activeYear'));
    }
}
