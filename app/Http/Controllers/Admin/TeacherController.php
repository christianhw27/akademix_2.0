<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'subjects'])->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email',
            'nip' => 'required|string|max:50|unique:teachers,nip',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects']);
        $subjects = Subject::all();
        return view('admin.teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $teacher->user_id,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|max:50|unique:teachers,nip,' . $teacher->id,
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $userData = [
                'username' => $request->username,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $teacher->user->update($userData);

            $teacher->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            $subjects = $request->input('subjects', []);
            $teacher->subjects()->sync($subjects);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            // Deleting the user will cascade delete the teacher
            $teacher->user->delete();
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
