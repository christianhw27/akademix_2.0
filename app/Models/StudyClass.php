<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyClass extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    protected $table = 'classes';
    public function classrooms() { return $this->hasMany(Classroom::class, 'class_id'); }

    public function getClassNameAttribute()
    {
        return $this->name;
    }

}
