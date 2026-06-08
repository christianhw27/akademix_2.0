<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            $assignments = collect();
        } else {
            $assignments = Assignment::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.studyClass', 'subject'])
                ->withCount('submissions')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('teacher.assignments.index', compact('assignments', 'activeYear'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.assignments.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        // Get classrooms and subjects assigned to this teacher
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();

        return view('teacher.assignments.create', compact('classrooms', 'subjects'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.assignments.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $request->validate([
            'classroom_id'  => 'required|exists:classrooms,id',
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string|max:150',
            'description'   => 'required|string',
            'due_date'      => 'required|date|after:now',
            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,zip,rar|max:20480',
        ]);

        $hasSchedule = Schedule::where('teacher_id', $teacher->id)
            ->where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$hasSchedule) {
            return back()->withErrors(['error' => 'Anda tidak dijadwalkan mengajar mata pelajaran ini di kelas tersebut.'])->withInput();
        }

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->storeAs('assignments', uniqid() . '---' . $file->getClientOriginalName(), 'public');
            }
        }

        Assignment::create([
            'teacher_id'       => $teacher->id,
            'academic_year_id' => $activeYear->id,
            'classroom_id'     => $request->classroom_id,
            'subject_id'       => $request->subject_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'due_date'         => $request->due_date,
            'attachment'       => empty($paths) ? null : $paths,
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Assignment $assignment)
    {
        $teacher = auth()->user()->teacher;

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();

        return view('teacher.assignments.edit', compact('assignment', 'classrooms', 'subjects'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $teacher = auth()->user()->teacher;

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'classroom_id'  => 'required|exists:classrooms,id',
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string|max:150',
            'description'   => 'required|string',
            'due_date'      => 'required|date',
            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,zip,rar|max:20480',
        ]);

        $hasSchedule = Schedule::where('teacher_id', $teacher->id)
            ->where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$hasSchedule) {
            return back()->withErrors(['error' => 'Anda tidak dijadwalkan mengajar mata pelajaran ini di kelas tersebut.'])->withInput();
        }

        $updateData = [
            'classroom_id' => $request->classroom_id,
            'subject_id'   => $request->subject_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'due_date'     => $request->due_date,
        ];

        $existingPaths = $assignment->attachment ?? [];
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
                $existingPaths[] = $file->storeAs('assignments', uniqid() . '---' . $file->getClientOriginalName(), 'public');
            }
        }

        $updateData['attachment'] = empty($existingPaths) ? null : $existingPaths;
        $assignment->update($updateData);

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment)
    {
        $teacher = auth()->user()->teacher;

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $assignment->delete();
        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function submissions(Assignment $assignment)
    {
        $teacher = auth()->user()->teacher;

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        // Get all students in this classroom
        $students = $assignment->classroom->students()->with('user')->get();

        // Get submissions for this assignment
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.assignments.submissions', compact('assignment', 'students', 'submissions'));
    }

    public function gradeSubmission(Request $request, AssignmentSubmission $submission)
    {
        $teacher = auth()->user()->teacher;
        $assignment = $submission->assignment;

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $submission, $assignment, $teacher) {
            // Update the submission
            $submission->update([
                'status' => 'reviewed',
                'score' => $request->score,
                'feedback' => $request->feedback,
            ]);

            // Sync with Grades table as a 'tugas' type grade
            Grade::updateOrCreate(
                [
                    'student_id' => $submission->student_id,
                    'subject_id' => $assignment->subject_id,
                    'classroom_id' => $assignment->classroom_id,
                    'academic_year_id' => $assignment->academic_year_id,
                    'semester' => $assignment->academicYear->semester,
                    'grade_type' => 'tugas',
                    'title' => 'Tugas: ' . $assignment->title,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'score' => $request->score,
                    'notes' => 'Dinilai otomatis dari Tugas: ' . $assignment->title . '. Feedback: ' . ($request->feedback ?? '-'),
                ]
            );
        });

        return back()->with('success', 'Pengumpulan tugas berhasil dinilai.');
    }
}
