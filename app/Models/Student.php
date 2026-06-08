<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    public function user() { return $this->belongsTo(User::class); }
    public function guardian() { return $this->belongsTo(Guardian::class); }
    public function cohort() { return $this->belongsTo(Cohort::class); }
    public function classrooms() { return $this->belongsToMany(Classroom::class, 'classroom_students'); }

}
