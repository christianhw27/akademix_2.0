@extends('layouts.dashboard')

@section('title', 'Tugas & Evaluasi')
@section('page_title', 'Kelola Tugas & Evaluasi')

@section('nav')
    @include('teacher.nav', ['active' => 'assignments'])
@endsection

@section('content')
<div class="flex-between">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Daftar tugas yang telah Anda publikasikan kepada siswa.
        </p>
    </div>
    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">
        + Buat Tugas Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    @if($assignments->isEmpty())
        <div style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
            <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📝</div>
            <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Tugas</h4>
            <p style="font-size: 14px;">Anda belum membuat tugas untuk tahun ajaran aktif ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Judul Tugas</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tenggat Waktu (Due Date)</th>
                        <th>Submisi</th>
                        <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);">{{ $assignment->title }}</div>
                                <small style="color: var(--on-surface-variant);">{{ Str::limit($assignment->description, 80) }}</small>
                            </td>
                            <td><span class="badge badge-primary" style="white-space: nowrap;">{{ $assignment->classroom->name }}</span></td>
                            <td style="font-weight: 500;">{{ $assignment->subject->subject_name }}</td>
                            <td style="color: #ba1a1a; font-weight: 500;">
                                {{ date('d M Y H:i', strtotime($assignment->due_date)) }}
                            </td>
                            <td>
                                <a href="{{ route('teacher.assignments.submissions', $assignment->id) }}" class="btn btn-secondary btn-sm">
                                    Lihat & Nilai ({{ $assignment->submissions_count }} Terkumpul)
                                </a>
                            </td>
                            <td style="text-align: right; padding-right: 2rem;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Semua data pengumpulan siswa juga akan terhapus.')">
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
