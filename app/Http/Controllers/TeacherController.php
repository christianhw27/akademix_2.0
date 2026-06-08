<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect('/login')->withErrors(['username' => 'Profil guru tidak ditemukan.']);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            $schedules = collect();
            $homeroomClassroom = null;
            $stats = [
                'materials_count' => 0,
                'assignments_count' => 0,
                'pending_submissions' => 0,
            ];
        } else {
            // Get schedules for active academic year
            $schedules = Schedule::where('teacher_id', $teacher->id)
                ->whereHas('classroom', function ($query) use ($activeYear) {
                    $query->where('academic_year_id', $activeYear->id);
                })
                ->with(['classroom.studyClass', 'subject'])
                ->get()
                ->sortBy(function($schedule) {
                    $days = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7];
                    return [$days[strtolower($schedule->day_of_week)] ?? 8, $schedule->start_time];
                });

            // Homeroom classroom (if any)
            $homeroomClassroom = Classroom::where('homeroom_teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with('studyClass')
                ->first();

            // Stats
            $materialsCount = Material::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->count();

            $assignmentsCount = Assignment::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->count();

            $pendingSubmissions = AssignmentSubmission::whereHas('assignment', function ($q) use ($teacher, $activeYear) {
                $q->where('teacher_id', $teacher->id)->where('academic_year_id', $activeYear->id);
            })
            ->where('status', 'submitted')
            ->count();

            $stats = [
                'materials_count' => $materialsCount,
                'assignments_count' => $assignmentsCount,
                'pending_submissions' => $pendingSubmissions,
            ];
        }

        return view('teacher.dashboard', compact('teacher', 'activeYear', 'schedules', 'homeroomClassroom', 'stats'));
    }
}
