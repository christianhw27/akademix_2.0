<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function studyClass() { return $this->belongsTo(StudyClass::class, 'class_id'); }
    public function homeroomTeacher() { return $this->belongsTo(Teacher::class, 'homeroom_teacher_id'); }
    public function students() { return $this->belongsToMany(Student::class, 'classroom_students'); }

    public function getNameAttribute()
    {
        $className = $this->studyClass ? $this->studyClass->name : '';
        $romanGrades = [
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        $gradeLabel = $romanGrades[$this->grade_level] ?? $this->grade_level;
        return $gradeLabel ? $gradeLabel . ' ' . $className : $className;
    }

}
