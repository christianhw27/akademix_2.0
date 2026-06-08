<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'attachment' => 'array',
    ];

    protected $guarded = [];
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }

}
