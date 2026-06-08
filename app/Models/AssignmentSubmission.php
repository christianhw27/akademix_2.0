<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['attachment' => 'array'];
    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student() { return $this->belongsTo(Student::class); }

}
