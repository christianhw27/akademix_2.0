<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\AcademicYear;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            'total_teachers' => Teacher::count(),
            'total_students' => Student::count(),
            'active_academic_year' => AcademicYear::where('is_active', 1)->first(),
            'total_classrooms' => Classroom::whereHas('academicYear', function($q) {
                $q->where('is_active', 1);
            })->count(),
        ];
        return view('admin.dashboard', $data);
    }
}
