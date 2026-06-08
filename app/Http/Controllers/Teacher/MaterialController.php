<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            $materials = collect();
        } else {
            $materials = Material::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.studyClass', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('teacher.materials.index', compact('materials', 'activeYear'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.materials.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        // Get unique classrooms and subjects assigned to this teacher in the active schedules
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();

        return view('teacher.materials.create', compact('classrooms', 'subjects'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $activeYear = AcademicYear::where('is_active', 1)->first();

        if (!$activeYear) {
            return redirect()->route('teacher.materials.index')->withErrors(['error' => 'Tidak ada tahun ajaran aktif.']);
        }

        $request->validate([
            'classroom_id'   => 'required|exists:classrooms,id',
            'subject_id'     => 'required|exists:subjects,id',
            'title'          => 'required|string|max:150',
            'content'        => 'required|string',
            'attachments'    => 'nullable|array|max:10',
            'attachments.*'  => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,zip,rar|max:20480',
        ]);

        // Verify scheduled teaching assignment
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
                $paths[] = $file->storeAs('materials', uniqid() . '---' . $file->getClientOriginalName(), 'public');
            }
        }

        Material::create([
            'teacher_id'       => $teacher->id,
            'academic_year_id' => $activeYear->id,
            'classroom_id'     => $request->classroom_id,
            'subject_id'       => $request->subject_id,
            'title'            => $request->title,
            'content'          => $request->content,
            'attachment'       => empty($paths) ? null : $paths,
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        $teacher = auth()->user()->teacher;
        
        if ($material->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $activeYear = AcademicYear::where('is_active', 1)->first();

        // Get unique classrooms and subjects assigned to this teacher in the active schedules
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->whereHas('classroom', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->get();

        $classrooms = Classroom::whereIn('id', $schedules->pluck('classroom_id'))
            ->with('studyClass')->orderBy('grade_level')->get();

        $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();

        return view('teacher.materials.edit', compact('material', 'classrooms', 'subjects'));
    }

    public function update(Request $request, Material $material)
    {
        $teacher = auth()->user()->teacher;
        
        if ($material->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'classroom_id'  => 'required|exists:classrooms,id',
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string|max:150',
            'content'       => 'required|string',
            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,zip,rar|max:20480',
        ]);

        // Verify scheduled teaching assignment
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
            'content'      => $request->content,
        ];

        // Handle removed files (comma-separated indexes to remove)
        $existingPaths = $material->attachment ?? [];
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
                $existingPaths[] = $file->storeAs('materials', uniqid() . '---' . $file->getClientOriginalName(), 'public');
            }
        }

        $updateData['attachment'] = empty($existingPaths) ? null : $existingPaths;
        $material->update($updateData);

        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $teacher = auth()->user()->teacher;
        
        if ($material->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $material->delete();
        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil dihapus.');
    }
}
