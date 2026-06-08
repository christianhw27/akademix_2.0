<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function records() { return $this->hasMany(AttendanceRecord::class); }

}
