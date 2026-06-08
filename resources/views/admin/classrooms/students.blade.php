@extends('layouts.dashboard')

@section('title', 'Anggota Kelas')
@section('page_title', 'Anggota Kelas: {{ $classroom->grade_level }} {{ $classroom->name }}')

@section('nav')
    @include('admin.nav', ['active' => 'classrooms'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.classrooms.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Rombongan Belajar
    </a>
</div>

<div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-container) 100%); color: white; border: none;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
        <div>
            <small style="opacity: 0.8; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em;">Kelas</small>
            <p style="font-size: 22px; font-weight: 700;">{{ $classroom->grade_level }} {{ $classroom->name }}</p>
        </div>
        <div>
            <small style="opacity: 0.8; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em;">Tahun Ajaran</small>
            <p style="font-size: 16px; font-weight: 600;">{{ $classroom->academicYear->year_label }} ({{ ucfirst($classroom->academicYear->semester) }})</p>
        </div>
        <div>
            <small style="opacity: 0.8; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em;">Jumlah Anggota Saat Ini</small>
            <p style="font-size: 22px; font-weight: 700;">{{ count($currentStudentIds) }} Siswa</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.classrooms.students.save', $classroom->id) }}" method="POST">
    @csrf

    <div class="card">
        <div class="flex-between">
            <h2 style="font-size: 18px; font-weight: 600;">Pilih Siswa yang Masuk ke Kelas Ini</h2>
            <button type="submit" class="btn btn-primary">Simpan Perubahan Anggota</button>
        </div>

        <p style="font-size: 13px; color: var(--on-surface-variant); margin-bottom: 1.25rem;">
            Centang siswa yang akan menjadi anggota kelas ini. Siswa yang sudah terdaftar di kelas lain pada tahun ajaran yang sama tidak akan muncul di daftar.
        </p>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" title="Pilih Semua">
                        </th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Angkatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                    {{ in_array($student->id, $currentStudentIds) ? 'checked' : '' }}>
                            </td>
                            <td style="font-weight: 600; color: var(--primary);">{{ $student->nis }}</td>
                            <td style="font-weight: 500;">{{ $student->user->full_name }}</td>
                            <td>
                                @if($student->gender === 'L')
                                    <span class="badge" style="background-color: #dbeafe; color: #1e40af;">L</span>
                                @else
                                    <span class="badge" style="background-color: #fce7f3; color: #9d174d;">P</span>
                                @endif
                            </td>
                            <td>{{ $student->cohort->year_name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                                Semua siswa sudah dialokasikan ke kelas lain untuk tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="students[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
