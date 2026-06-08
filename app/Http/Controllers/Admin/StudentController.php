<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;
use App\Models\Guardian;
use App\Models\Cohort;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'guardian.user', 'cohort'])->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $guardians = Guardian::with('user')->get();
        $cohorts = Cohort::all();
        return view('admin.students.create', compact('guardians', 'cohorts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email',
            'nis' => 'required|string|max:50|unique:students,nis',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'guardian_id' => 'nullable|exists:guardians,id',
            'cohort_id' => 'required|exists:cohorts,id',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ]);

            Student::create([
                'user_id' => $user->id,
                'guardian_id' => $request->guardian_id,
                'cohort_id' => $request->cohort_id,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $student->load(['user', 'guardian', 'cohort']);
        $guardians = Guardian::with('user')->get();
        $cohorts = Cohort::all();
        return view('admin.students.edit', compact('student', 'guardians', 'cohorts'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $student->user_id,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|max:50|unique:students,nis,' . $student->id,
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'guardian_id' => 'nullable|exists:guardians,id',
            'cohort_id' => 'required|exists:cohorts,id',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $student) {
            $userData = [
                'username' => $request->username,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);

            $student->update([
                'guardian_id' => $request->guardian_id,
                'cohort_id' => $request->cohort_id,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user->delete();
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
