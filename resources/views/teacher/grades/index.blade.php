@extends('layouts.dashboard')

@section('title', 'Daftar Nilai Siswa')
@section('page_title', 'Kelola Nilai Siswa')

@section('nav')
    @include('teacher.nav', ['active' => 'grades'])
@endsection

@section('content')
<div class="flex-between">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Kelola dan input nilai harian, nilai tugas, maupun nilai rapor siswa.
        </p>
    </div>
    <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary">
        + Input Nilai Baru (Kolektif)
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Filters Card -->
<div class="card" style="margin-bottom: 2rem;">
    <form action="{{ route('teacher.grades.index') }}" method="GET">
        <div class="form-row" style="align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="classroom_id" class="form-label">Filter Kelas</label>
                <select name="classroom_id" id="classroom_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="grade_type" class="form-label">Filter Tipe Nilai</label>
                <select name="grade_type" id="grade_type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="harian" {{ request('grade_type') === 'harian' ? 'selected' : '' }}>Harian (Ulangan/Kuis)</option>
                    <option value="tugas" {{ request('grade_type') === 'tugas' ? 'selected' : '' }}>Tugas Mandiri/Kelompok</option>
                    <option value="rapor" {{ request('grade_type') === 'rapor' ? 'selected' : '' }}>Rapor Akhir Semester</option>
                </select>
            </div>

            <div style="margin-bottom: 0; display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-secondary" style="padding: 0.75rem 1.25rem;">Filter</button>
                @if(request()->filled('classroom_id') || request()->filled('grade_type'))
                    <a href="{{ route('teacher.grades.index') }}" class="btn" style="background-color: #cbd5e1; color: var(--on-surface); padding: 0.75rem 1.25rem;">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Grades Table Card -->
<div class="card">
    @if($grades->isEmpty())
        <div style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
            <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📊</div>
            <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Data Nilai</h4>
            <p style="font-size: 14px;">Tidak ditemukan data nilai siswa yang sesuai dengan kriteria filter.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>NISN</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tipe</th>
                        <th>Judul/Keterangan Nilai</th>
                        <th style="text-align: center;">Skor</th>
                        <th>Catatan</th>
                        <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                        <tr>
                            <td style="font-weight: 600; color: var(--on-surface);">{{ $grade->student->user->full_name }}</td>
                            <td style="font-family: monospace;">{{ $grade->student->nis }}</td>
                            <td style="font-weight: 500;">{{ $grade->subject->subject_name }}</td>
                            <td><span class="badge badge-primary">{{ $grade->classroom->name }}</span></td>
                            <td>
                                @if($grade->grade_type === 'harian')
                                    <span class="badge badge-warning">Harian</span>
                                @elseif($grade->grade_type === 'tugas')
                                    <span class="badge badge-success">Tugas</span>
                                @elseif($grade->grade_type === 'rapor')
                                    <span class="badge badge-danger" style="background-color: #fca5a5; color: #7f1d1d;">Rapor</span>
                                @endif
                            </td>
                            <td style="font-weight: 500; color: var(--primary);">{{ $grade->title }}</td>
                            <td style="text-align: center; font-size: 16px; font-weight: 700; color: var(--primary);">
                                {{ number_format($grade->score, 1) }}
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $grade->notes ?? '-' }}
                            </td>
                            <td style="text-align: right; padding-right: 2rem;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('teacher.grades.edit', $grade->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('teacher.grades.destroy', $grade->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data nilai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
