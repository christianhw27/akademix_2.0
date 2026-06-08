@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Admin')

@section('nav')
    @include('admin.nav', ['active' => 'dashboard'])
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <h3 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase;">Total Guru</h3>
        <p style="font-size: 36px; font-weight: 700; color: var(--primary);">{{ $total_teachers }}</p>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <h3 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase;">Total Siswa</h3>
        <p style="font-size: 36px; font-weight: 700; color: var(--primary);">{{ $total_students }}</p>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <h3 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase;">Kelas Aktif</h3>
        <p style="font-size: 36px; font-weight: 700; color: var(--primary);">{{ $total_classrooms }}</p>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <h3 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase;">Semester Aktif</h3>
        <p style="font-size: 24px; font-weight: 700; color: var(--primary); margin-top: 0.5rem;">
            @if($active_academic_year)
                {{ $active_academic_year->year_label }} ({{ ucfirst($active_academic_year->semester) }})
            @else
                Belum di set
            @endif
        </p>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 20px;">Aksi Cepat</h2>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">+ Buat Kelas</a>
        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-secondary">Seting Semester</a>
    </div>
</div>
@endsection
