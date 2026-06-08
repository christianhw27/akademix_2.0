<a href="{{ route('teacher.dashboard') }}" class="nav-item {{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
    Dashboard
</a>
<a href="{{ route('teacher.materials.index') }}" class="nav-item {{ ($active ?? '') === 'materials' ? 'active' : '' }}">
    Materi Pembelajaran
</a>
<a href="{{ route('teacher.assignments.index') }}" class="nav-item {{ ($active ?? '') === 'assignments' ? 'active' : '' }}">
    Tugas & Evaluasi
</a>
<a href="{{ route('teacher.attendance.index') }}" class="nav-item {{ ($active ?? '') === 'attendance' ? 'active' : '' }}">
    Absensi Siswa
</a>
<a href="{{ route('teacher.grades.index') }}" class="nav-item {{ ($active ?? '') === 'grades' ? 'active' : '' }}">
    Input Nilai Siswa
</a>
