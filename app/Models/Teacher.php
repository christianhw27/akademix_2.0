<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    public function user() { return $this->belongsTo(User::class); }
    public function subjects() { return $this->belongsToMany(Subject::class, 'teacher_subjects'); }
    public function classrooms() { return $this->hasMany(Classroom::class, 'homeroom_teacher_id'); }

}
