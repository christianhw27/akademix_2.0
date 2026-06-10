<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'teacher', 'student', 'parent'])) {
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                'parent' => redirect()->route('parent.dashboard'),
            };
        }
        Auth::logout();
        return redirect('/login')->withErrors('Role tidak dikenal.');
    }
    return view('landing');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect('/login')->withErrors('Role tidak dikenal.'),
        };
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        
        // Teachers CRUD
        Route::resource('/teachers', \App\Http\Controllers\Admin\TeacherController::class);

        // Students CRUD
        Route::resource('/students', \App\Http\Controllers\Admin\StudentController::class);

        // Guardians (Parents) CRUD
        Route::resource('/guardians', \App\Http\Controllers\Admin\GuardianController::class);

        // Classrooms CRUD & Student Allocation
        Route::get('/classrooms/{classroom}/students', [\App\Http\Controllers\Admin\ClassroomController::class, 'manageStudents'])->name('classrooms.students');
        Route::post('/classrooms/{classroom}/students', [\App\Http\Controllers\Admin\ClassroomController::class, 'saveStudents'])->name('classrooms.students.save');
        Route::resource('/classrooms', \App\Http\Controllers\Admin\ClassroomController::class);

        // Schedules CRUD
        Route::resource('/schedules', \App\Http\Controllers\Admin\ScheduleController::class);

        // Academic Years CRUD & Semester Archiving
        Route::post('/academic-years/{academicYear}/activate', [\App\Http\Controllers\Admin\AcademicYearController::class, 'activate'])->name('academic-years.activate');
        Route::resource('/academic-years', \App\Http\Controllers\Admin\AcademicYearController::class);
    });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
        
        // Materials CRUD
        Route::resource('/materials', \App\Http\Controllers\Teacher\MaterialController::class);
        
        // Assignments CRUD & Grading
        Route::get('/assignments/{assignment}/submissions', [\App\Http\Controllers\Teacher\AssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/submissions/{submission}/grade', [\App\Http\Controllers\Teacher\AssignmentController::class, 'gradeSubmission'])->name('submissions.grade');
        Route::resource('/assignments', \App\Http\Controllers\Teacher\AssignmentController::class);
        
        // Attendance
        Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [\App\Http\Controllers\Teacher\AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{session}/edit', [\App\Http\Controllers\Teacher\AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{session}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{session}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Grades
        Route::get('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('grades.index');
        Route::get('/grades/create', [\App\Http\Controllers\Teacher\GradeController::class, 'create'])->name('grades.create');
        Route::post('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'store'])->name('grades.store');
        Route::get('/grades/{grade}/edit', [\App\Http\Controllers\Teacher\GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/grades/{grade}', [\App\Http\Controllers\Teacher\GradeController::class, 'update'])->name('grades.update');
        Route::delete('/grades/{grade}', [\App\Http\Controllers\Teacher\GradeController::class, 'destroy'])->name('grades.destroy');
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/materials', [\App\Http\Controllers\StudentController::class, 'materials'])->name('materials');
        Route::get('/materials/{material}', [\App\Http\Controllers\StudentController::class, 'viewMaterial'])->name('materials.show');
        Route::get('/assignments', [\App\Http\Controllers\StudentController::class, 'assignments'])->name('assignments');
        Route::get('/assignments/{assignment}', [\App\Http\Controllers\StudentController::class, 'viewAssignment'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\StudentController::class, 'submitAssignment'])->name('assignments.submit');
        Route::get('/attendance', [\App\Http\Controllers\StudentController::class, 'attendance'])->name('attendance');
        Route::get('/grades', [\App\Http\Controllers\StudentController::class, 'grades'])->name('grades');
    });

    // Parent Routes
    Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\ParentController::class, 'dashboard'])->name('dashboard');
        Route::get('/attendance', [\App\Http\Controllers\ParentController::class, 'attendance'])->name('attendance');
        Route::get('/assignments', [\App\Http\Controllers\ParentController::class, 'assignments'])->name('assignments');
        Route::get('/grades', [\App\Http\Controllers\ParentController::class, 'grades'])->name('grades');
    });
});
