<?php

$models = [
    'User' => "
    protected \$guarded = [];
    protected \$hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }
    public function teacher() { return \$this->hasOne(Teacher::class); }
    public function student() { return \$this->hasOne(Student::class); }
    public function guardian() { return \$this->hasOne(Guardian::class); }
",
    'Teacher' => "
    protected \$guarded = [];
    public function user() { return \$this->belongsTo(User::class); }
    public function subjects() { return \$this->belongsToMany(Subject::class, 'teacher_subjects'); }
    public function classrooms() { return \$this->hasMany(Classroom::class, 'homeroom_teacher_id'); }
",
    'Guardian' => "
    protected \$guarded = [];
    public function user() { return \$this->belongsTo(User::class); }
    public function students() { return \$this->hasMany(Student::class); }
",
    'Student' => "
    protected \$guarded = [];
    public function user() { return \$this->belongsTo(User::class); }
    public function guardian() { return \$this->belongsTo(Guardian::class); }
    public function cohort() { return \$this->belongsTo(Cohort::class); }
    public function classrooms() { return \$this->belongsToMany(Classroom::class, 'classroom_students'); }
",
    'Cohort' => "
    protected \$guarded = [];
    public function students() { return \$this->hasMany(Student::class); }
",
    'Subject' => "
    protected \$guarded = [];
    public function teachers() { return \$this->belongsToMany(Teacher::class, 'teacher_subjects'); }
",
    'StudyClass' => "
    protected \$guarded = [];
    protected \$table = 'classes';
    public function classrooms() { return \$this->hasMany(Classroom::class, 'class_id'); }
",
    'Classroom' => "
    protected \$guarded = [];
    public function academicYear() { return \$this->belongsTo(AcademicYear::class); }
    public function studyClass() { return \$this->belongsTo(StudyClass::class, 'class_id'); }
    public function homeroomTeacher() { return \$this->belongsTo(Teacher::class, 'homeroom_teacher_id'); }
    public function students() { return \$this->belongsToMany(Student::class, 'classroom_students'); }
",
    'AcademicYear' => "
    protected \$guarded = [];
",
    'Schedule' => "
    protected \$guarded = [];
    public function classroom() { return \$this->belongsTo(Classroom::class); }
    public function subject() { return \$this->belongsTo(Subject::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
",
    'Material' => "
    protected \$guarded = [];
    public function classroom() { return \$this->belongsTo(Classroom::class); }
    public function subject() { return \$this->belongsTo(Subject::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
    public function academicYear() { return \$this->belongsTo(AcademicYear::class); }
",
    'Assignment' => "
    protected \$guarded = [];
    public function classroom() { return \$this->belongsTo(Classroom::class); }
    public function subject() { return \$this->belongsTo(Subject::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
    public function academicYear() { return \$this->belongsTo(AcademicYear::class); }
    public function submissions() { return \$this->hasMany(AssignmentSubmission::class); }
",
    'AssignmentSubmission' => "
    protected \$guarded = [];
    public function assignment() { return \$this->belongsTo(Assignment::class); }
    public function student() { return \$this->belongsTo(Student::class); }
",
    'AttendanceSession' => "
    protected \$guarded = [];
    public function classroom() { return \$this->belongsTo(Classroom::class); }
    public function subject() { return \$this->belongsTo(Subject::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
    public function academicYear() { return \$this->belongsTo(AcademicYear::class); }
    public function records() { return \$this->hasMany(AttendanceRecord::class); }
",
    'AttendanceRecord' => "
    protected \$guarded = [];
    public function session() { return \$this->belongsTo(AttendanceSession::class, 'attendance_session_id'); }
    public function student() { return \$this->belongsTo(Student::class); }
",
    'Grade' => "
    protected \$guarded = [];
    public function student() { return \$this->belongsTo(Student::class); }
    public function subject() { return \$this->belongsTo(Subject::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
    public function classroom() { return \$this->belongsTo(Classroom::class); }
    public function academicYear() { return \$this->belongsTo(AcademicYear::class); }
"
];

$dir = __DIR__ . '/app/Models/';

foreach ($models as $model => $code) {
    $file = $dir . $model . '.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Remove standard comments
        if ($model === 'User') {
            $content = preg_replace('/use Illuminate\\\\Foundation\\\\Auth\\\\User as Authenticatable;/', "use Illuminate\\Foundation\\Auth\\User as Authenticatable;\nuse App\\Models\\Teacher;\nuse App\\Models\\Student;\nuse App\\Models\\Guardian;", $content);
            $content = preg_replace('/protected \$fillable = \[.*?\];/s', '', $content);
            $content = preg_replace('/protected \$hidden = \[.*?\];/s', '', $content);
            $content = preg_replace('/protected function casts\(\): array.*?}/s', '', $content);
            
            // Insert our code
            $content = preg_replace('/class User extends Authenticatable\n\{/', "class User extends Authenticatable\n{\n$code", $content);
        } else {
            $content = preg_replace('/class '.$model.' extends Model\n\{\n    \/\/\n\}/', "class $model extends Model\n{\n$code\n}", $content);
        }
        file_put_contents($file, $content);
        echo "Updated $model\n";
    }
}
