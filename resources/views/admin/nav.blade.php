@php
    $active = $active ?? 'dashboard';
@endphp

<a href="{{ route('admin.dashboard') }}" class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}">Dashboard</a>
<a href="{{ route('admin.teachers.index') }}" class="nav-item {{ $active === 'teachers' ? 'active' : '' }}">Kelola Guru</a>
<a href="{{ route('admin.students.index') }}" class="nav-item {{ $active === 'students' ? 'active' : '' }}">Kelola Siswa</a>
{{-- <a href="{{ route('admin.guardians.index') }}" class="nav-item {{ $active === 'guardians' ? 'active' : '' }}">Kelola Orang Tua</a> --}}
<a href="{{ route('admin.classrooms.index') }}" class="nav-item {{ $active === 'classrooms' ? 'active' : '' }}">Manajemen Kelas</a>
<a href="{{ route('admin.schedules.index') }}" class="nav-item {{ $active === 'schedules' ? 'active' : '' }}">Jadwal Pelajaran</a>
<a href="{{ route('admin.academic-years.index') }}" class="nav-item {{ $active === 'academic-years' ? 'active' : '' }}">Tahun Ajaran</a>
