<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('academic_years')->insert([
            ['id' => 1, 'year_label' => '2024/2025', 'semester' => 'ganjil', 'start_date' => '2024-07-15', 'end_date' => '2024-12-20', 'is_active' => 0],
            ['id' => 2, 'year_label' => '2024/2025', 'semester' => 'genap', 'start_date' => '2025-01-06', 'end_date' => '2025-06-20', 'is_active' => 0],
            ['id' => 3, 'year_label' => '2025/2026', 'semester' => 'ganjil', 'start_date' => '2025-07-15', 'end_date' => '2025-12-20', 'is_active' => 0],
            ['id' => 4, 'year_label' => '2025/2026', 'semester' => 'genap', 'start_date' => '2026-01-06', 'end_date' => '2026-06-20', 'is_active' => 1],
        ]);

        DB::table('users')->insert([
            ['id' => 1, 'username' => 'admin', 'password' => Hash::make('password'), 'role' => 'admin', 'full_name' => 'Admin Akademix', 'email' => 'admin@akademix.test', 'is_active' => 1],
            ['id' => 2, 'username' => 'guru.budi', 'password' => Hash::make('password'), 'role' => 'teacher', 'full_name' => 'Budi Santoso, S.Pd.', 'email' => 'budi@akademix.test', 'is_active' => 1],
            ['id' => 3, 'username' => 'guru.sari', 'password' => Hash::make('password'), 'role' => 'teacher', 'full_name' => 'Sari Wulandari, M.Pd.', 'email' => 'sari@akademix.test', 'is_active' => 1],
            ['id' => 4, 'username' => 'guru.deni', 'password' => Hash::make('password'), 'role' => 'teacher', 'full_name' => 'Deni Permana, S.Pd.', 'email' => 'deni@akademix.test', 'is_active' => 1],
            ['id' => 5, 'username' => 'ortu.andi', 'password' => Hash::make('password'), 'role' => 'parent', 'full_name' => 'Andi Pratama', 'email' => 'andi@akademix.test', 'is_active' => 1],
            ['id' => 6, 'username' => 'ortu.maya', 'password' => Hash::make('password'), 'role' => 'parent', 'full_name' => 'Maya Lestari', 'email' => 'maya@akademix.test', 'is_active' => 1],
            ['id' => 7, 'username' => 'siswa.raka', 'password' => Hash::make('password'), 'role' => 'student', 'full_name' => 'Raka Mahendra', 'email' => 'raka@akademix.test', 'is_active' => 1],
            ['id' => 8, 'username' => 'siswa.nadia', 'password' => Hash::make('password'), 'role' => 'student', 'full_name' => 'Nadia Putri', 'email' => 'nadia@akademix.test', 'is_active' => 1],
            ['id' => 9, 'username' => 'siswa.fajar', 'password' => Hash::make('password'), 'role' => 'student', 'full_name' => 'Fajar Ramadhan', 'email' => 'fajar@akademix.test', 'is_active' => 1],
            ['id' => 10, 'username' => 'siswa.sinta', 'password' => Hash::make('password'), 'role' => 'student', 'full_name' => 'Sinta Dewi', 'email' => 'sinta@akademix.test', 'is_active' => 1],
            ['id' => 11, 'username' => 'siswa.rizky', 'password' => Hash::make('password'), 'role' => 'student', 'full_name' => 'Ahmad Rizky', 'email' => 'rizky@akademix.test', 'is_active' => 1],
        ]);

        DB::table('teachers')->insert([
            ['id' => 1, 'user_id' => 2, 'nip' => '198712012010011001', 'phone' => '081234567890', 'address' => 'Jl. Melati No. 10'],
            ['id' => 2, 'user_id' => 3, 'nip' => '198903022011012002', 'phone' => '081298765432', 'address' => 'Jl. Kenanga No. 5'],
            ['id' => 3, 'user_id' => 4, 'nip' => '199005032012011003', 'phone' => '081355556666', 'address' => 'Jl. Anggrek No. 8'],
        ]);

        DB::table('guardians')->insert([
            ['id' => 1, 'user_id' => 5, 'phone' => '081211111111', 'address' => 'Jl. Mawar No. 21'],
            ['id' => 2, 'user_id' => 6, 'phone' => '081222222222', 'address' => 'Jl. Flamboyan No. 18'],
        ]);

        DB::table('cohorts')->insert([
            ['id' => 1, 'year_name' => '2024'],
            ['id' => 2, 'year_name' => '2025'],
        ]);

        DB::table('students')->insert([
            ['id' => 1, 'user_id' => 7, 'guardian_id' => 1, 'cohort_id' => 1, 'nis' => '20240001', 'gender' => 'L', 'birth_date' => '2010-04-12', 'phone' => null, 'address' => 'Perum Griya Asri Blok A1'],
            ['id' => 2, 'user_id' => 8, 'guardian_id' => 1, 'cohort_id' => 1, 'nis' => '20240002', 'gender' => 'P', 'birth_date' => '2010-09-08', 'phone' => null, 'address' => 'Perum Griya Asri Blok A1'],
            ['id' => 3, 'user_id' => 9, 'guardian_id' => 2, 'cohort_id' => 1, 'nis' => '20240003', 'gender' => 'L', 'birth_date' => '2009-11-30', 'phone' => null, 'address' => 'Jl. Flamboyan No. 18'],
            ['id' => 4, 'user_id' => 10, 'guardian_id' => 2, 'cohort_id' => 2, 'nis' => '20250001', 'gender' => 'P', 'birth_date' => '2011-03-15', 'phone' => null, 'address' => 'Jl. Flamboyan No. 18'],
            ['id' => 5, 'user_id' => 11, 'guardian_id' => null, 'cohort_id' => 2, 'nis' => '20250002', 'gender' => 'L', 'birth_date' => '2011-07-22', 'phone' => null, 'address' => 'Jl. Dahlia No. 3'],
        ]);

        DB::table('subjects')->insert([
            ['id' => 1, 'code' => 'MAT', 'name' => 'Matematika', 'description' => 'Materi dasar hingga lanjutan matematika sekolah.'],
            ['id' => 2, 'code' => 'BIO', 'name' => 'Biologi', 'description' => 'Pembelajaran konsep dasar biologi.'],
            ['id' => 3, 'code' => 'BIN', 'name' => 'Bahasa Indonesia', 'description' => 'Keterampilan membaca, menulis, dan presentasi.'],
            ['id' => 4, 'code' => 'FIS', 'name' => 'Fisika', 'description' => 'Konsep-konsep dasar fisika.'],
            ['id' => 5, 'code' => 'BIG', 'name' => 'Bahasa Inggris', 'description' => 'Keterampilan bahasa Inggris.'],
        ]);

        DB::table('teacher_subjects')->insert([
            ['teacher_id' => 1, 'subject_id' => 1],
            ['teacher_id' => 1, 'subject_id' => 3],
            ['teacher_id' => 2, 'subject_id' => 2],
            ['teacher_id' => 2, 'subject_id' => 4],
            ['teacher_id' => 3, 'subject_id' => 5],
        ]);

        DB::table('classes')->insert([
            ['id' => 1, 'name' => 'IPA 1'],
            ['id' => 2, 'name' => 'IPA 2'],
            ['id' => 3, 'name' => 'IPS 1'],
        ]);

        DB::table('classrooms')->insert([
            ['id' => 1, 'academic_year_id' => 1, 'class_id' => 1, 'grade_level' => 10, 'homeroom_teacher_id' => 1],
            ['id' => 2, 'academic_year_id' => 2, 'class_id' => 1, 'grade_level' => 10, 'homeroom_teacher_id' => 1],
            ['id' => 3, 'academic_year_id' => 3, 'class_id' => 1, 'grade_level' => 11, 'homeroom_teacher_id' => 1],
            ['id' => 4, 'academic_year_id' => 4, 'class_id' => 1, 'grade_level' => 11, 'homeroom_teacher_id' => 1],
            ['id' => 5, 'academic_year_id' => 4, 'class_id' => 1, 'grade_level' => 10, 'homeroom_teacher_id' => 2],
            ['id' => 6, 'academic_year_id' => 4, 'class_id' => 2, 'grade_level' => 10, 'homeroom_teacher_id' => 3],
        ]);

        DB::table('classroom_students')->insert([
            ['id' => 1, 'classroom_id' => 1, 'student_id' => 1],
            ['id' => 2, 'classroom_id' => 1, 'student_id' => 2],
            ['id' => 3, 'classroom_id' => 2, 'student_id' => 1],
            ['id' => 4, 'classroom_id' => 2, 'student_id' => 2],
            ['id' => 5, 'classroom_id' => 2, 'student_id' => 3],
            ['id' => 6, 'classroom_id' => 3, 'student_id' => 1],
            ['id' => 7, 'classroom_id' => 3, 'student_id' => 2],
            ['id' => 8, 'classroom_id' => 3, 'student_id' => 3],
            ['id' => 9, 'classroom_id' => 4, 'student_id' => 1],
            ['id' => 10, 'classroom_id' => 4, 'student_id' => 2],
            ['id' => 11, 'classroom_id' => 4, 'student_id' => 3],
            ['id' => 12, 'classroom_id' => 5, 'student_id' => 4],
            ['id' => 13, 'classroom_id' => 5, 'student_id' => 5],
            ['id' => 14, 'classroom_id' => 6, 'student_id' => 4],
        ]);

        DB::table('schedules')->insert([
            ['id' => 1, 'classroom_id' => 4, 'subject_id' => 1, 'teacher_id' => 1, 'day_of_week' => 'Senin', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 2, 'classroom_id' => 4, 'subject_id' => 2, 'teacher_id' => 2, 'day_of_week' => 'Senin', 'start_time' => '09:15:00', 'end_time' => '10:45:00'],
            ['id' => 3, 'classroom_id' => 4, 'subject_id' => 3, 'teacher_id' => 1, 'day_of_week' => 'Selasa', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 4, 'classroom_id' => 4, 'subject_id' => 4, 'teacher_id' => 2, 'day_of_week' => 'Selasa', 'start_time' => '09:15:00', 'end_time' => '10:45:00'],
            ['id' => 5, 'classroom_id' => 4, 'subject_id' => 5, 'teacher_id' => 3, 'day_of_week' => 'Rabu', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 6, 'classroom_id' => 4, 'subject_id' => 1, 'teacher_id' => 1, 'day_of_week' => 'Rabu', 'start_time' => '09:15:00', 'end_time' => '10:45:00'],
            ['id' => 7, 'classroom_id' => 4, 'subject_id' => 2, 'teacher_id' => 2, 'day_of_week' => 'Kamis', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 8, 'classroom_id' => 4, 'subject_id' => 3, 'teacher_id' => 1, 'day_of_week' => 'Kamis', 'start_time' => '09:15:00', 'end_time' => '10:45:00'],
            ['id' => 9, 'classroom_id' => 4, 'subject_id' => 4, 'teacher_id' => 2, 'day_of_week' => 'Jumat', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 10, 'classroom_id' => 5, 'subject_id' => 1, 'teacher_id' => 1, 'day_of_week' => 'Senin', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 11, 'classroom_id' => 5, 'subject_id' => 5, 'teacher_id' => 3, 'day_of_week' => 'Selasa', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 12, 'classroom_id' => 5, 'subject_id' => 2, 'teacher_id' => 2, 'day_of_week' => 'Rabu', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 13, 'classroom_id' => 6, 'subject_id' => 3, 'teacher_id' => 1, 'day_of_week' => 'Senin', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
            ['id' => 14, 'classroom_id' => 6, 'subject_id' => 4, 'teacher_id' => 2, 'day_of_week' => 'Selasa', 'start_time' => '07:30:00', 'end_time' => '09:00:00'],
        ]);

        DB::table('materials')->insert([
            ['id' => 1, 'subject_id' => 1, 'classroom_id' => 4, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Fungsi Kuadrat', 'content' => 'Penjelasan bentuk umum fungsi kuadrat dan grafik.', 'created_at' => '2026-02-10 09:00:00'],
            ['id' => 2, 'subject_id' => 2, 'classroom_id' => 4, 'teacher_id' => 2, 'academic_year_id' => 4, 'title' => 'Struktur Sel', 'content' => 'Materi biologi mengenai organel sel.', 'created_at' => '2026-02-15 10:00:00'],
            ['id' => 3, 'subject_id' => 5, 'classroom_id' => 5, 'teacher_id' => 3, 'academic_year_id' => 4, 'title' => 'Tenses Overview', 'content' => 'Overview of English tenses.', 'created_at' => '2026-03-01 08:00:00'],
        ]);

        DB::table('assignments')->insert([
            ['id' => 1, 'subject_id' => 1, 'classroom_id' => 4, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Latihan Fungsi Kuadrat', 'description' => 'Kerjakan 10 soal fungsi kuadrat.', 'due_date' => '2026-05-20 23:59:00', 'created_at' => '2026-05-01 08:00:00'],
            ['id' => 2, 'subject_id' => 3, 'classroom_id' => 4, 'teacher_id' => 1, 'academic_year_id' => 4, 'title' => 'Resume Artikel', 'description' => 'Resume artikel berita pendidikan minimal 300 kata.', 'due_date' => '2026-05-18 21:00:00', 'created_at' => '2026-05-03 09:00:00'],
            ['id' => 3, 'subject_id' => 2, 'classroom_id' => 4, 'teacher_id' => 2, 'academic_year_id' => 4, 'title' => 'Praktikum Sel', 'description' => 'Kirimkan catatan observasi praktikum sel.', 'due_date' => '2026-05-22 20:00:00', 'created_at' => '2026-05-04 10:00:00'],
        ]);

        DB::table('assignment_submissions')->insert([
            ['id' => 1, 'assignment_id' => 1, 'student_id' => 1, 'content' => 'Jawaban latihan fungsi kuadrat sudah saya kerjakan.', 'status' => 'submitted', 'submitted_at' => '2026-05-09 19:30:00', 'score' => null, 'feedback' => null],
            ['id' => 2, 'assignment_id' => 2, 'student_id' => 2, 'content' => 'Resume artikel mengenai literasi digital.', 'status' => 'submitted', 'submitted_at' => '2026-05-10 18:15:00', 'score' => null, 'feedback' => null],
        ]);

        DB::table('attendance_sessions')->insert([
            ['id' => 1, 'classroom_id' => 4, 'subject_id' => 1, 'teacher_id' => 1, 'academic_year_id' => 4, 'attendance_date' => '2026-05-07', 'notes' => 'Pembelajaran reguler', 'created_at' => '2026-05-07 08:00:00'],
            ['id' => 2, 'classroom_id' => 4, 'subject_id' => 2, 'teacher_id' => 2, 'academic_year_id' => 4, 'attendance_date' => '2026-05-08', 'notes' => 'Praktikum laboratorium', 'created_at' => '2026-05-08 08:00:00'],
        ]);

        DB::table('attendance_records')->insert([
            ['id' => 1, 'attendance_session_id' => 1, 'student_id' => 1, 'status' => 'hadir', 'notes' => null],
            ['id' => 2, 'attendance_session_id' => 1, 'student_id' => 2, 'status' => 'izin', 'notes' => 'Izin dokter'],
            ['id' => 3, 'attendance_session_id' => 1, 'student_id' => 3, 'status' => 'hadir', 'notes' => null],
            ['id' => 4, 'attendance_session_id' => 2, 'student_id' => 1, 'status' => 'hadir', 'notes' => null],
            ['id' => 5, 'attendance_session_id' => 2, 'student_id' => 2, 'status' => 'hadir', 'notes' => null],
            ['id' => 6, 'attendance_session_id' => 2, 'student_id' => 3, 'status' => 'sakit', 'notes' => 'Sakit demam'],
        ]);

        DB::table('grades')->insert([
            ['id' => 1, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 4, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'harian', 'title' => 'Kuis Aljabar', 'score' => 88.00, 'notes' => 'Pemahaman baik.', 'created_at' => '2026-05-01 08:00:00'],
            ['id' => 2, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 4, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'tugas', 'title' => 'PR Fungsi Kuadrat', 'score' => 90.00, 'notes' => 'Tepat waktu.', 'created_at' => '2026-05-03 08:00:00'],
            ['id' => 3, 'student_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'classroom_id' => 4, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir Matematika', 'score' => 89.00, 'notes' => 'Konsisten meningkat.', 'created_at' => '2026-05-05 08:00:00'],
            ['id' => 4, 'student_id' => 2, 'subject_id' => 3, 'teacher_id' => 1, 'classroom_id' => 4, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir B. Indonesia', 'score' => 84.00, 'notes' => 'Aktif di kelas.', 'created_at' => '2026-05-05 09:00:00'],
            ['id' => 5, 'student_id' => 3, 'subject_id' => 2, 'teacher_id' => 2, 'classroom_id' => 4, 'academic_year_id' => 4, 'semester' => 'genap', 'grade_type' => 'rapor', 'title' => 'Nilai Akhir Biologi', 'score' => 91.00, 'notes' => 'Sangat baik.', 'created_at' => '2026-05-06 10:00:00'],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
