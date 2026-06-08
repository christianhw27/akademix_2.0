@extends('layouts.dashboard')

@section('title', 'Catat Absensi')
@section('page_title', 'Catat Absensi Baru')

@section('nav')
    @include('teacher.nav', ['active' => 'attendance'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('teacher.attendance.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Absensi
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Step 1: Select Classroom & Subject -->
<div class="card" style="max-width: 800px; margin-bottom: 2rem;">
    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
        Pilih Kelas & Mata Pelajaran
    </h3>
    
    <form action="{{ route('teacher.attendance.create') }}" method="GET">
        <div class="form-row" style="align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="classroom_id" class="form-label">Kelas <span style="color: red;">*</span></label>
                <select name="classroom_id" id="classroom_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ (request('classroom_id') == $classroom->id || ($selectedClassroom && $selectedClassroom->id == $classroom->id)) ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="subject_id" class="form-label">Mata Pelajaran <span style="color: red;">*</span></label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ (request('subject_id') == $subject->id || ($selectedSubject && $selectedSubject->id == $subject->id)) ? 'selected' : '' }}>
                            {{ $subject->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; width: 100%;">
                    Pilih & Muat Siswa
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Step 2: Student List Form -->
@if($selectedClassroom && $selectedSubject)
    <form action="{{ route('teacher.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
        <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">

        <div class="card" style="max-width: 900px; margin-bottom: 2rem;">
            <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
                Informasi Absensi ({{ $selectedClassroom->name }} - {{ $selectedSubject->subject_name }})
            </h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="attendance_date" class="form-label">Tanggal Absensi <span style="color: red;">*</span></label>
                    <input type="date" name="attendance_date" id="attendance_date" class="form-control" value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Catatan Sesi Absensi (Opsional)</label>
                    <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}" placeholder="Contoh: Pertemuan 1 - Pengenalan Materi">
                </div>
            </div>
        </div>

        <div class="card" style="max-width: 900px;">
            <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
                Daftar Hadir Siswa
            </h3>

            @if($students->isEmpty())
                <div style="padding: 2rem; text-align: center; color: var(--on-surface-variant);">
                    Tidak ada siswa terdaftar di kelas ini. Silakan hubungi Admin untuk alokasi siswa.
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 250px;">Nama Siswa</th>
                                <th style="width: 150px;">NISN</th>
                                <th style="text-align: center;">Kehadiran</th>
                                <th>Catatan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $student->user->full_name }}</div>
                                    </td>
                                    <td style="font-family: monospace;">{{ $student->nis }}</td>
                                    <td>
                                        <div style="display: flex; gap: 1rem; justify-content: center; align-items: center;">
                                            <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer;">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="hadir" checked> Hadir
                                            </label>
                                            <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #b45309;">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="izin"> Izin
                                            </label>
                                            <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #047857;">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="sakit"> Sakit
                                            </label>
                                            <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #b91c1c;">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="alpha"> Alpha
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="record_notes[{{ $student->id }}]" class="form-control" placeholder="Tulis catatan jika terlambat/absen..." style="padding: 0.35rem 0.5rem; font-size: 13px;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    <a href="{{ route('teacher.attendance.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
                </div>
            @endif
        </div>
    </form>
@endif
@endsection
