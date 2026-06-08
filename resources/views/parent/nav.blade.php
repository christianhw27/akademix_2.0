<a href="{{ route('parent.dashboard') }}" class="nav-item {{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
    Dashboard Anak
</a>
<a href="{{ route('parent.assignments') }}" class="nav-item {{ ($active ?? '') === 'assignments' ? 'active' : '' }}">
    Tugas & Status Anak
</a>
<a href="{{ route('parent.attendance') }}" class="nav-item {{ ($active ?? '') === 'attendance' ? 'active' : '' }}">
    Kehadiran Anak
</a>
<a href="{{ route('parent.grades') }}" class="nav-item {{ ($active ?? '') === 'grades' ? 'active' : '' }}">
    Rapor Hasil Belajar Anak
</a>
