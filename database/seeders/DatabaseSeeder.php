<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Truncate all tables
        $tables = [
            'academic_years', 'subjects', 'cohorts', 'users', 'teachers', 'teacher_subjects',
            'students', 'guardians', 'classes', 'classrooms', 'classroom_students', 'schedules',
            'materials', 'assignments', 'assignment_submissions', 'attendance_sessions',
            'attendance_records', 'grades'
        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // 2. Academic Years
        DB::table('academic_years')->insert([
            ['id' => 1, 'year_label' => '2024/2025', 'semester' => 'ganjil', 'start_date' => '2024-07-15', 'end_date' => '2024-12-20', 'is_active' => 0],
            ['id' => 2, 'year_label' => '2024/2025', 'semester' => 'genap', 'start_date' => '2025-01-06', 'end_date' => '2025-06-20', 'is_active' => 0],
            ['id' => 3, 'year_label' => '2025/2026', 'semester' => 'ganjil', 'start_date' => '2025-07-15', 'end_date' => '2025-12-20', 'is_active' => 0],
            ['id' => 4, 'year_label' => '2025/2026', 'semester' => 'genap', 'start_date' => '2026-01-06', 'end_date' => '2026-06-20', 'is_active' => 1],
        ]);

        // 3. Subjects
        $subjects = [
            ['id' => 1, 'code' => 'MAT', 'name' => 'Matematika', 'description' => 'Matematika Wajib dan Peminatan'],
            ['id' => 2, 'code' => 'BIO', 'name' => 'Biologi', 'description' => 'Biologi Dasar dan Menengah'],
            ['id' => 3, 'code' => 'BIN', 'name' => 'Bahasa Indonesia', 'description' => 'Bahasa dan Sastra Indonesia'],
            ['id' => 4, 'code' => 'FIS', 'name' => 'Fisika', 'description' => 'Fisika Teoretis dan Praktikum'],
            ['id' => 5, 'code' => 'BIG', 'name' => 'Bahasa Inggris', 'description' => 'Bahasa Inggris Wajib'],
        ];
        DB::table('subjects')->insert($subjects);

        // 4. Cohorts
        DB::table('cohorts')->insert([
            ['id' => 1, 'year_name' => '2024'],
            ['id' => 2, 'year_name' => '2025'],
        ]);

        // 5. Users: Admin
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'full_name' => 'Admin Akademix',
            'email' => 'admin@akademix.test',
            'is_active' => 1
        ]);

        // 6. Users: 15 Teachers
        $teacherNames = [
            'Hendra Wijaya, S.Pd.', // Matematika (ID: 1)
            'Budi Santoso, S.Pd.', // Matematika (ID: 2)
            'Rian Hidayat, S.Pd.', // Matematika (ID: 3)
            'Sari Wulandari, M.Pd.', // Biologi (ID: 4)
            'Eko Prasetyo, S.Pd.', // Biologi (ID: 5)
            'Deni Permana, S.Pd.', // Biologi (ID: 6)
            'Ani Lestari, S.Pd.', // Bahasa Indonesia (ID: 7)
            'Tina Marlina, S.Pd.', // Bahasa Indonesia (ID: 8)
            'Yusuf Bachdim, S.Pd.', // Bahasa Indonesia (ID: 9)
            'Gede Wahyu, S.Pd.', // Fisika (ID: 10)
            'Made Surya, S.Pd.', // Fisika (ID: 11)
            'Nyoman Dharma, S.Pd.', // Fisika (ID: 12)
            'Ketut Adi, S.Pd.', // Bahasa Inggris (ID: 13)
            'Wayan Juni, S.Pd.', // Bahasa Inggris (ID: 14)
            'Komang Tri, S.Pd.', // Bahasa Inggris (ID: 15)
        ];

        // Subject taught by each teacher
        $teacherSubjects = [
            1 => 1, 2 => 1, 3 => 1, // Matematika
            4 => 2, 5 => 2, 6 => 2, // Biologi
            7 => 3, 8 => 3, 9 => 3, // Bahasa Indonesia
            10 => 4, 11 => 4, 12 => 4, // Fisika
            13 => 5, 14 => 5, 15 => 5, // Bahasa Inggris
        ];

        $teacherUserIds = [];
        $currentUserId = 2;

        foreach ($teacherNames as $index => $name) {
            $username = ($index === 0) ? 'guru.hendra104' : 'guru.teacher' . ($index + 1);
            DB::table('users')->insert([
                'id' => $currentUserId,
                'username' => $username,
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'full_name' => $name,
                'email' => str_replace(' ', '', strtolower($name)) . '@akademix.test',
                'is_active' => 1
            ]);
            $teacherUserIds[$index + 1] = $currentUserId;
            $currentUserId++;
        }

        // Populate teachers table and teacher_subjects
        foreach ($teacherUserIds as $teacherId => $userId) {
            DB::table('teachers')->insert([
                'id' => $teacherId,
                'user_id' => $userId,
                'nip' => '198' . rand(0,9) . rand(10,99) . rand(100,999) . '201501' . rand(100,999),
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => 'Jl. Pendidikan No. ' . $teacherId,
            ]);

            DB::table('teacher_subjects')->insert([
                'teacher_id' => $teacherId,
                'subject_id' => $teacherSubjects[$teacherId],
            ]);
        }

        // 7. Users: 50 Students
        $studentUserIds = [];
        // First student is Raka Mahendra (student_id = 1, user_id = $currentUserId)
        $rakaUserId = $currentUserId;
        DB::table('users')->insert([
            'id' => $rakaUserId,
            'username' => 'siswa.raka',
            'password' => Hash::make('password'),
            'role' => 'student',
            'full_name' => 'Raka Mahendra',
            'email' => 'raka@akademix.test',
            'is_active' => 1
        ]);
        $studentUserIds[1] = $rakaUserId;
        $currentUserId++;

        // Add 49 other dummy students
        $firstNames = ['Ahmad', 'Dimas', 'Eka', 'Fajar', 'Gilang', 'Hendra', 'Indra', 'Joko', 'Kevin', 'Lutfi', 'Naufal', 'Pratama', 'Rizky', 'Wahyu', 'Yudi', 'Siti', 'Dewi', 'Nadia', 'Maya', 'Sinta', 'Zahra', 'Indah', 'Lestari', 'Putri', 'Sari'];
        $lastNames = ['Mahendra', 'Saputra', 'Wijaya', 'Santoso', 'Hidayat', 'Pratama', 'Permana', 'Fauzi', 'Widodo', 'Wulandari', 'Rahmawati', 'Lestari', 'Putri', 'Sari', 'Dewi'];

        for ($i = 2; $i <= 50; $i++) {
            $fullName = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $username = 'siswa.' . $i;
            DB::table('users')->insert([
                'id' => $currentUserId,
                'username' => $username,
                'password' => Hash::make('password'),
                'role' => 'student',
                'full_name' => $fullName,
                'email' => "siswa.{$i}@akademix.test",
                'is_active' => 1
            ]);
            $studentUserIds[$i] = $currentUserId;
            $currentUserId++;
        }

        // Populate students table
        foreach ($studentUserIds as $studentId => $userId) {
            DB::table('students')->insert([
                'id' => $studentId,
                'user_id' => $userId,
                'guardian_id' => null,
                'cohort_id' => ($studentId % 2 === 0) ? 2 : 1, // split cohort
                'nis' => ($studentId === 1) ? '20240001' : '2024' . sprintf('%04d', $studentId),
                'gender' => ($studentId % 3 === 0) ? 'P' : 'L',
                'birth_date' => '2010-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                'phone' => '0857' . rand(10000000, 99999999),
                'address' => 'Perumahan Indah Blok ' . chr(rand(65, 75)) . rand(1, 20),
            ]);
        }

        // 8. Classes (named "1" to "5")
        for ($i = 1; $i <= 5; $i++) {
            DB::table('classes')->insert([
                'id' => $i,
                'name' => (string) $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 9. Classrooms (X 1-5, XI 1-5, XII 1-5)
        // We have 15 classrooms.
        // Grade levels: 10 (X), 11 (XI), 12 (XII)
        // Classes: 1, 2, 3, 4, 5
        $classrooms = [];
        $classroomId = 1;
        
        $gradeLevels = [10, 11, 12];
        $classIds = [1, 2, 3, 4, 5];

        foreach ($gradeLevels as $grade) {
            foreach ($classIds as $classId) {
                // Homeroom teacher is classrooms 1..15
                DB::table('classrooms')->insert([
                    'id' => $classroomId,
                    'academic_year_id' => 4,
                    'class_id' => $classId,
                    'grade_level' => $grade,
                    'homeroom_teacher_id' => $classroomId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $classrooms[$classroomId] = [
                    'id' => $classroomId,
                    'grade_level' => $grade,
                    'class_id' => $classId
                ];
                $classroomId++;
            }
        }

        // 10. Classroom Students (allocating students to classrooms)
        // Raka Mahendra (student_id = 1) is in classroom XI 1 -> Classroom ID 6 (Grade level 11, class 1)
        DB::table('classroom_students')->insert([
            'classroom_id' => 6,
            'student_id' => 1,
        ]);

        // Allocate remaining students to classrooms
        for ($studentId = 2; $studentId <= 50; $studentId++) {
            // Distribute students. XI 1 gets students 1..10
            if ($studentId <= 12) {
                $classIdToAssign = 6;
            } else {
                $classIdToAssign = rand(1, 15);
                if ($classIdToAssign === 6) {
                    $classIdToAssign = 7;
                }
            }
            
            DB::table('classroom_students')->insert([
                'classroom_id' => $classIdToAssign,
                'student_id' => $studentId,
            ]);
        }

        // 11. Conflict-Free Schedule Generator
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $slots = [
            ['start' => '07:30:00', 'end' => '09:00:00'],
            ['start' => '09:15:00', 'end' => '10:45:00'],
            ['start' => '11:00:00', 'end' => '12:30:00'],
        ];

        // Track busy teachers per day and slot to avoid overlaps
        $busyTeachers = [];
        foreach ($days as $day) {
            $busyTeachers[$day] = [0 => [], 1 => [], 2 => []];
        }

        $scheduleId = 1;

        // Hendra Wijaya (teacher_id = 1) teaches XI 1 (classroom_id = 6) on Monday Slot 1 and Wednesday Slot 2
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 1,
            'teacher_id' => 1,
            'day_of_week' => 'Senin',
            'start_time' => $slots[0]['start'],
            'end_time' => $slots[0]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Senin'][0][] = 1;

        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 1,
            'teacher_id' => 1,
            'day_of_week' => 'Rabu',
            'start_time' => $slots[1]['start'],
            'end_time' => $slots[1]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Rabu'][1][] = 1;

        // Seed other slots for XI 1 (Raka's class)
        // Monday Slot 2: Fisika (Teacher 10, Gede Wahyu)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 4,
            'teacher_id' => 10,
            'day_of_week' => 'Senin',
            'start_time' => $slots[1]['start'],
            'end_time' => $slots[1]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Senin'][1][] = 10;

        // Tuesday Slot 1: Biologi (Teacher 4, Sari Wulandari)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 2,
            'teacher_id' => 4,
            'day_of_week' => 'Selasa',
            'start_time' => $slots[0]['start'],
            'end_time' => $slots[0]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Selasa'][0][] = 4;

        // Tuesday Slot 2: Bahasa Indonesia (Teacher 7, Ani Lestari)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 3,
            'teacher_id' => 7,
            'day_of_week' => 'Selasa',
            'start_time' => $slots[1]['start'],
            'end_time' => $slots[1]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Selasa'][1][] = 7;

        // Wednesday Slot 1: Bahasa Inggris (Teacher 13, Ketut Adi)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 5,
            'teacher_id' => 13,
            'day_of_week' => 'Rabu',
            'start_time' => $slots[0]['start'],
            'end_time' => $slots[0]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Rabu'][0][] = 13;

        // Thursday Slot 1: Fisika (Teacher 10, Gede Wahyu)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 4,
            'teacher_id' => 10,
            'day_of_week' => 'Kamis',
            'start_time' => $slots[0]['start'],
            'end_time' => $slots[0]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Kamis'][0][] = 10;

        // Thursday Slot 2: Bahasa Indonesia (Teacher 7, Ani Lestari)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 3,
            'teacher_id' => 7,
            'day_of_week' => 'Kamis',
            'start_time' => $slots[1]['start'],
            'end_time' => $slots[1]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Kamis'][1][] = 7;

        // Friday Slot 1: Bahasa Inggris (Teacher 13, Ketut Adi)
        DB::table('schedules')->insert([
            'id' => $scheduleId++,
            'classroom_id' => 6,
            'subject_id' => 5,
            'teacher_id' => 13,
            'day_of_week' => 'Jumat',
            'start_time' => $slots[0]['start'],
            'end_time' => $slots[0]['end'],
            'room_number' => 'XI-1'
        ]);
        $busyTeachers['Jumat'][0][] = 13;

        // Group teachers by subject
        $teachersBySubject = [
            1 => [1, 2, 3], // Matematika
            2 => [4, 5, 6], // Biologi
            3 => [7, 8, 9], // Bahasa Indonesia
            4 => [10, 11, 12], // Fisika
            5 => [13, 14, 15], // Bahasa Inggris
        ];

        // Populate schedules for all other classrooms conflict-free
        for ($classId = 1; $classId <= 15; $classId++) {
            if ($classId === 6) {
                continue;
            }
            
            $gradeLabel = $classrooms[$classId]['grade_level'];
            $roomName = ($gradeLabel === 10 ? 'X' : ($gradeLabel === 11 ? 'XI' : 'XII')) . '-' . $classrooms[$classId]['class_id'];

            foreach ($days as $day) {
                for ($slotIdx = 0; $slotIdx < 2; $slotIdx++) {
                    $slot = $slots[$slotIdx];
                    
                    $subjectOrder = [1, 2, 3, 4, 5];
                    shuffle($subjectOrder);
                    
                    $assigned = false;
                    foreach ($subjectOrder as $subjectId) {
                        $availableTeachers = $teachersBySubject[$subjectId];
                        foreach ($availableTeachers as $teacherId) {
                            if (!in_array($teacherId, $busyTeachers[$day][$slotIdx])) {
                                DB::table('schedules')->insert([
                                    'id' => $scheduleId++,
                                    'classroom_id' => $classId,
                                    'subject_id' => $subjectId,
                                    'teacher_id' => $teacherId,
                                    'day_of_week' => $day,
                                    'start_time' => $slot['start'],
                                    'end_time' => $slot['end'],
                                    'room_number' => $roomName
                                ]);
                                $busyTeachers[$day][$slotIdx][] = $teacherId;
                                $assigned = true;
                                break;
                            }
                        }
                        if ($assigned) break;
                    }
                }
            }
        }

        // 12. Materials & Assignments for Classroom XI 1
        DB::table('materials')->insert([
            ['id' => 1, 'subject_id' => 1, 'classroom_id' => 6, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Matriks dan Operasinya', 'content' => 'Materi mengenai konsep dasar matriks, baris, kolom, ordo, penjumlahan, pengurangan, dan perkalian matriks.', 'created_at' => '2026-05-10 09:00:00'],
            ['id' => 2, 'subject_id' => 1, 'classroom_id' => 6, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Determinan dan Invers Matriks', 'content' => 'Materi tentang perhitungan nilai determinan matriks 2x2 dan 3x3 serta mencari invers matriks.', 'created_at' => '2026-05-17 10:00:00'],
            ['id' => 3, 'subject_id' => 4, 'classroom_id' => 6, 'teacher_id' => 10, 'academic_year_id' => 4, 'title' => 'Hukum Newton tentang Gerak', 'content' => 'Penjelasan Hukum I, II, dan III Newton mengenai gerak benda serta aplikasinya pada katrol dan bidang miring.', 'created_at' => '2026-05-12 08:00:00'],
            ['id' => 4, 'subject_id' => 5, 'classroom_id' => 6, 'teacher_id' => 13, 'academic_year_id' => 4, 'title' => 'Conditional Sentences', 'content' => 'Review of Type 0, 1, 2, and 3 conditional sentences with structures and practical examples.', 'created_at' => '2026-05-14 09:00:00'],
        ]);

        DB::table('assignments')->insert([
            ['id' => 1, 'subject_id' => 1, 'classroom_id' => 6, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Tugas 1: Operasi Matriks', 'description' => 'Kerjakan 5 soal perhitungan matriks dari file buku cetak halaman 42.', 'due_date' => '2026-05-25 23:59:00', 'created_at' => '2026-05-11 08:00:00'],
            ['id' => 2, 'subject_id' => 1, 'classroom_id' => 6, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Kuis Matriks & Determinan', 'description' => 'Kuis evaluasi pemahaman determinan dan invers matriks.', 'due_date' => '2026-05-30 23:59:00', 'created_at' => '2026-05-18 08:00:00'],
            ['id' => 3, 'subject_id' => 4, 'classroom_id' => 6, 'teacher_id' => 10, 'academic_year_id' => 4, 'title' => 'Laporan Praktikum Hukum Newton', 'description' => 'Tulis laporan hasil praktikum katrol gantung minimal 3 halaman.', 'due_date' => '2026-05-20 20:00:00', 'created_at' => '2026-05-13 10:00:00'],
        ]);

        // Submit for Raka (student_id = 1)
        DB::table('assignment_submissions')->insert([
            ['id' => 1, 'assignment_id' => 1, 'student_id' => 1, 'content' => 'Jawaban saya sudah saya tulis lengkap di kertas dan saya lampirkan fotonya.', 'status' => 'submitted', 'submitted_at' => '2026-05-24 15:30:00', 'score' => 88.00, 'feedback' => 'Bagus sekali, perhitungan ordo 3x3 sudah benar.'],
            ['id' => 2, 'assignment_id' => 2, 'student_id' => 1, 'content' => 'Mengumpulkan jawaban kuis matriks.', 'status' => 'submitted', 'submitted_at' => '2026-05-29 20:15:00', 'score' => 90.00, 'feedback' => 'Sangat teliti.'],
        ]);

        // 13. Grades for Raka Mahendra (student_id = 1)
        DB::table('grades')->insert([
            ['id' => 1, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 6, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'harian', 'title' => 'Kuis Matriks', 'score' => 90.00, 'notes' => 'Sangat teliti.', 'created_at' => '2026-05-29 20:15:00'],
            ['id' => 2, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 6, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'tugas', 'title' => 'Operasi Matriks', 'score' => 88.00, 'notes' => 'Bagus sekali.', 'created_at' => '2026-05-24 15:30:00'],
            ['id' => 3, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 6, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir Matematika', 'score' => 89.00, 'notes' => 'Pemahaman matriks sangat baik.', 'created_at' => '2026-06-05 08:00:00'],
            ['id' => 4, 'student_id' => 1, 'subject_id' => 4, 'teacher_id' => 10, 'classroom_id' => 6, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir Fisika', 'score' => 85.00, 'notes' => 'Aktif dalam praktikum Newton.', 'created_at' => '2026-06-05 09:00:00'],
            ['id' => 5, 'student_id' => 1, 'subject_id' => 5, 'teacher_id' => 13, 'classroom_id' => 6, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir B. Inggris', 'score' => 87.00, 'notes' => 'Sangat lancar dalam writing.', 'created_at' => '2026-06-05 10:00:00'],
        ]);

        // 14. Attendance Sessions & Records for Raka Mahendra (student_id = 1) in Classroom XI 1 (id = 6)
        $start = Carbon::create(2026, 5, 1);
        $end = Carbon::create(2026, 6, 10);

        $classroomSchedules = DB::table('schedules')->where('classroom_id', 6)->get();

        $dayMap = [
            'Senin' => Carbon::MONDAY,
            'Selasa' => Carbon::TUESDAY,
            'Rabu' => Carbon::WEDNESDAY,
            'Kamis' => Carbon::THURSDAY,
            'Jumat' => Carbon::FRIDAY,
        ];

        $sessionId = 1;
        $recordId = 1;

        $classStudents = DB::table('classroom_students')->where('classroom_id', 6)->pluck('student_id')->toArray();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }

            $dayName = '';
            foreach ($dayMap as $indName => $carbonDay) {
                if ($date->dayOfWeek === $carbonDay) {
                    $dayName = $indName;
                    break;
                }
            }

            $daySchedules = $classroomSchedules->where('day_of_week', $dayName);

            foreach ($daySchedules as $sched) {
                DB::table('attendance_sessions')->insert([
                    'id' => $sessionId,
                    'classroom_id' => 6,
                    'subject_id' => $sched->subject_id,
                    'teacher_id' => $sched->teacher_id,
                    'academic_year_id' => 4,
                    'attendance_date' => $date->toDateString(),
                    'notes' => 'Pembelajaran reguler ' . $dayName,
                    'created_at' => Carbon::parse($date->toDateString() . ' ' . $sched->start_time),
                ]);

                foreach ($classStudents as $studentId) {
                    $status = 'hadir';
                    
                    if ($studentId === 1) {
                        $dayNum = $date->day;
                        $monthNum = $date->month;
                        
                        if ($monthNum === 5) {
                            if ($dayNum === 5 && $sched->subject_id === 2) {
                                $status = 'izin';
                            } elseif ($dayNum === 11 && $sched->subject_id === 4) {
                                $status = 'sakit';
                            } elseif ($dayNum === 18) {
                                $status = 'alpha';
                            } elseif ($dayNum === 25) {
                                $status = 'alpha';
                            }
                        } elseif ($monthNum === 6) {
                            if ($dayNum === 4 && $sched->subject_id === 4) {
                                $status = 'izin';
                            } elseif ($dayNum === 8 && $sched->subject_id === 1) {
                                $status = 'sakit';
                            } elseif ($dayNum === 9) {
                                $status = 'alpha';
                            }
                        }
                    } else {
                        if ($studentId % 5 === 0 && $date->day % 7 === 0) {
                            $status = 'sakit';
                        }
                    }

                    DB::table('attendance_records')->insert([
                        'id' => $recordId++,
                        'attendance_session_id' => $sessionId,
                        'student_id' => $studentId,
                        'status' => $status,
                        'notes' => $status !== 'hadir' ? ucfirst($status) : null,
                    ]);
                }
                $sessionId++;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
