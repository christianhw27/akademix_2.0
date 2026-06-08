<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    public function teachers() { return $this->belongsToMany(Teacher::class, 'teacher_subjects'); }

    public function getSubjectNameAttribute()
    {
        return $this->name;
    }

}
