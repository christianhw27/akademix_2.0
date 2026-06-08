@extends('layouts.dashboard')

@section('title', 'Manajemen Kelas')
@section('page_title', 'Manajemen Kelas')

@section('nav')
    @include('admin.nav', ['active' => 'classrooms'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="flex-between">
        <h2 style="font-size: 20px; font-weight: 600;">Daftar Rombongan Belajar (Kelas)</h2>
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">+ Buat Kelas Baru</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Tingkat</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">
                            {{ $classroom->grade_level }} {{ $classroom->name }}
                        </td>
                        <td>
                            <span class="badge {{ $classroom->academicYear->is_active ? 'badge-success' : '' }}">
                                {{ $classroom->academicYear->year_label }} ({{ ucfirst($classroom->academicYear->semester) }})
                            </span>
                        </td>
                        <td>Tingkat {{ $classroom->grade_level }}</td>
                        <td style="font-weight: 500;">
                            {{ $classroom->homeroomTeacher ? $classroom->homeroomTeacher->user->full_name : '-' }}
                            @if($classroom->homeroomTeacher)
                                <br><small style="color: #64748b;">NIP: {{ $classroom->homeroomTeacher->nip }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $classroom->students_count }} Siswa</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.classrooms.students', $classroom->id) }}" class="btn btn-secondary btn-sm" style="background-color: var(--secondary);">Anggota Siswa</a>
                                <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini? Data kehadiran, jadwal, dan nilai terkait kelas ini akan ikut terpengaruh.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                            Tidak ada rombongan belajar (kelas). Silakan buat kelas baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
