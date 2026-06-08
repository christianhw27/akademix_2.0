<?php
$dir = __DIR__ . '/database/migrations/';
$files = scandir($dir);

// First rename problem files to enforce order
foreach ($files as $file) {
    if (str_contains($file, 'classroom_students')) {
        rename($dir . $file, str_replace('081018_', '081019_', $dir . $file));
    }
    if (str_contains($file, 'assignment_submissions')) {
        rename($dir . $file, str_replace('081019_', '081020_', $dir . $file));
    }
    if (str_contains($file, 'attendance_records')) {
        rename($dir . $file, str_replace('081020_', '081021_', $dir . $file));
    }
    if (str_contains($file, 'grades')) {
        rename($dir . $file, str_replace('081020_', '081022_', $dir . $file));
    }
}

// Re-read files after renaming
$files = scandir($dir);

$schema = [
    'create_users_table' => <<<PHP
        Schema::create('users', function (Blueprint \$table) {
            \$table->id();
            \$table->string('username', 50)->nullable()->unique();
            \$table->string('password');
            \$table->enum('role', ['admin', 'teacher', 'student', 'parent']);
            \$table->string('full_name', 120);
            \$table->string('email', 120)->nullable()->unique();
            \$table->boolean('is_active')->default(true);
            \$table->timestamp('email_verified_at')->nullable();
            \$table->rememberToken();
            \$table->timestamps();
        });
PHP,
    'create_academic_years_table' => <<<PHP
        Schema::create('academic_years', function (Blueprint \$table) {
            \$table->id();
            \$table->string('year_label', 20);
            \$table->enum('semester', ['ganjil', 'genap']);
            \$table->date('start_date');
            \$table->date('end_date');
            \$table->boolean('is_active')->default(false);
            \$table->timestamps();
        });
PHP,
    'create_teachers_table' => <<<PHP
        Schema::create('teachers', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->string('nip', 50)->unique();
            \$table->string('phone', 40)->nullable();
            \$table->text('address')->nullable();
            \$table->timestamps();
        });
PHP,
    'create_guardians_table' => <<<PHP
        Schema::create('guardians', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->string('phone', 40)->nullable();
            \$table->text('address')->nullable();
            \$table->timestamps();
        });
PHP,
    'create_cohorts_table' => <<<PHP
        Schema::create('cohorts', function (Blueprint \$table) {
            \$table->id();
            \$table->string('year_name', 20)->unique();
            \$table->timestamps();
        });
PHP,
    'create_students_table' => <<<PHP
        Schema::create('students', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('guardian_id')->nullable()->constrained('guardians')->nullOnDelete()->cascadeOnUpdate();
            \$table->foreignId('cohort_id')->constrained('cohorts')->restrictOnDelete()->cascadeOnUpdate();
            \$table->string('nis', 50)->unique();
            \$table->enum('gender', ['L', 'P']);
            \$table->date('birth_date')->nullable();
            \$table->string('phone', 40)->nullable();
            \$table->text('address')->nullable();
            \$table->timestamps();
        });
PHP,
    'create_subjects_table' => <<<PHP
        Schema::create('subjects', function (Blueprint \$table) {
            \$table->id();
            \$table->string('code', 20)->unique();
            \$table->string('name', 100);
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
PHP,
    'create_teacher_subjects_table' => <<<PHP
        Schema::create('teacher_subjects', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->unique(['teacher_id', 'subject_id']);
            \$table->timestamps();
        });
PHP,
    'create_study_classes_table' => <<<PHP
        Schema::create('classes', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name', 50)->unique();
            \$table->timestamps();
        });
PHP,
    'create_classrooms_table' => <<<PHP
        Schema::create('classrooms', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete()->cascadeOnUpdate();
            \$table->foreignId('class_id')->constrained('classes')->restrictOnDelete()->cascadeOnUpdate();
            \$table->integer('grade_level');
            \$table->foreignId('homeroom_teacher_id')->nullable()->constrained('teachers')->nullOnDelete()->cascadeOnUpdate();
            \$table->timestamps();
        });
PHP,
    'create_classroom_students_table' => <<<PHP
        Schema::create('classroom_students', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->unique(['classroom_id', 'student_id']);
            \$table->timestamps();
        });
PHP,
    'create_schedules_table' => <<<PHP
        Schema::create('schedules', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            \$table->time('start_time');
            \$table->time('end_time');
            \$table->timestamps();
        });
PHP,
    'create_materials_table' => <<<PHP
        Schema::create('materials', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete()->cascadeOnUpdate();
            \$table->string('title', 150);
            \$table->text('content');
            \$table->timestamps();
        });
PHP,
    'create_assignments_table' => <<<PHP
        Schema::create('assignments', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete()->cascadeOnUpdate();
            \$table->string('title', 150);
            \$table->text('description');
            \$table->dateTime('due_date');
            \$table->timestamps();
        });
PHP,
    'create_assignment_submissions_table' => <<<PHP
        Schema::create('assignment_submissions', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->text('content');
            \$table->enum('status', ['belum', 'submitted', 'reviewed'])->default('submitted');
            \$table->dateTime('submitted_at')->nullable();
            \$table->decimal('score', 5, 2)->nullable();
            \$table->text('feedback')->nullable();
            \$table->unique(['assignment_id', 'student_id']);
            \$table->timestamps();
        });
PHP,
    'create_attendance_sessions_table' => <<<PHP
        Schema::create('attendance_sessions', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete()->cascadeOnUpdate();
            \$table->date('attendance_date');
            \$table->string('notes', 255)->nullable();
            \$table->timestamps();
        });
PHP,
    'create_attendance_records_table' => <<<PHP
        Schema::create('attendance_records', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('attendance_session_id')->constrained('attendance_sessions')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            \$table->string('notes', 255)->nullable();
            \$table->timestamps();
        });
PHP,
    'create_grades_table' => <<<PHP
        Schema::create('grades', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete()->cascadeOnUpdate();
            \$table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete()->cascadeOnUpdate();
            \$table->enum('semester', ['ganjil', 'genap']);
            \$table->enum('grade_type', ['harian', 'tugas', 'rapor']);
            \$table->string('title', 150);
            \$table->decimal('score', 5, 2);
            \$table->text('notes')->nullable();
            \$table->timestamps();
        });
PHP
];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    foreach ($schema as $key => $content) {
        if (str_contains($file, $key)) {
            $fullPath = $dir . $file;
            $fileContent = file_get_contents($fullPath);
            
            if ($key === 'create_users_table') {
                $fileContent = preg_replace('/Schema::create\(\'users\', function \(Blueprint \$table\) \{.*?\}\);/s', $content, $fileContent);
            } else if ($key === 'create_study_classes_table') {
                $fileContent = preg_replace('/Schema::create\(\'study_classes\', function \(Blueprint \$table\) \{.*?\}\);/s', $content, $fileContent);
                $fileContent = str_replace("Schema::dropIfExists('study_classes')", "Schema::dropIfExists('classes')", $fileContent);
            } else {
                $tableName = str_replace('create_', '', $key);
                $tableName = str_replace('_table', '', $tableName);
                $fileContent = preg_replace('/Schema::create\(\''.$tableName.'\', function \(Blueprint \$table\) \{.*?\}\);/s', $content, $fileContent);
            }
            file_put_contents($fullPath, $fileContent);
            echo "Updated $file\n";
        }
    }
}
