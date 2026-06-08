<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $classrooms = Classroom::with(['academicYear', 'studyClass'])->orderBy('grade_level')->get();
        
        $selectedClassroomId = $request->input('classroom_id') ?: ($classrooms->first()->id ?? null);
        
        $schedules = [];
        if ($selectedClassroomId) {
            $schedules = Schedule::with(['subject', 'teacher.user'])
                ->where('classroom_id', $selectedClassroomId)
                ->orderByRaw("FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                ->orderBy('start_time')
                ->get();
        }

        return view('admin.schedules.index', compact('classrooms', 'selectedClassroomId', 'schedules'));
    }

    public function create(Request $request)
    {
        $classrooms = Classroom::with(['academicYear', 'studyClass'])->orderBy('grade_level')->get();
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        
        $selectedClassroomId = $request->input('classroom_id');

        return view('admin.schedules.create', compact('classrooms', 'subjects', 'teachers', 'selectedClassroomId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $classroom_id = $request->classroom_id;
        $teacher_id = $request->teacher_id;
        $day = $request->day_of_week;
        $start = $request->start_time;
        $end = $request->end_time;

        // Collision Check 1: Classroom schedule conflict
        $classroomConflict = Schedule::where('classroom_id', $classroom_id)
            ->where('day_of_week', $day)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });
            })->exists();

        if ($classroomConflict) {
            return back()->withErrors(['start_time' => 'Jadwal bentrok dengan mata pelajaran lain di kelas ini pada waktu tersebut.'])->withInput();
        }

        // Collision Check 2: Teacher schedule conflict
        $teacherConflict = Schedule::where('teacher_id', $teacher_id)
            ->where('day_of_week', $day)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });
            })->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teacher_id' => 'Guru tersebut sudah memiliki jadwal mengajar di kelas lain pada hari dan jam yang sama.'])->withInput();
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index', ['classroom_id' => $classroom_id])->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $classrooms = Classroom::with(['academicYear', 'studyClass'])->orderBy('grade_level')->get();
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.schedules.edit', compact('schedule', 'classrooms', 'subjects', 'teachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $classroom_id = $request->classroom_id;
        $teacher_id = $request->teacher_id;
        $day = $request->day_of_week;
        $start = $request->start_time;
        $end = $request->end_time;

        // Collision Check 1: Classroom conflict (excluding self)
        $classroomConflict = Schedule::where('classroom_id', $classroom_id)
            ->where('day_of_week', $day)
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })->exists();

        if ($classroomConflict) {
            return back()->withErrors(['start_time' => 'Jadwal bentrok dengan mata pelajaran lain di kelas ini pada waktu tersebut.'])->withInput();
        }

        // Collision Check 2: Teacher conflict (excluding self)
        $teacherConflict = Schedule::where('teacher_id', $teacher_id)
            ->where('day_of_week', $day)
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teacher_id' => 'Guru tersebut sudah memiliki jadwal mengajar di kelas lain pada hari dan jam yang sama.'])->withInput();
        }

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index', ['classroom_id' => $classroom_id])->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $classroom_id = $schedule->classroom_id;
        $schedule->delete();
        return redirect()->route('admin.schedules.index', ['classroom_id' => $classroom_id])->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
