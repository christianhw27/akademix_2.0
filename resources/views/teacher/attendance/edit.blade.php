@extends('layouts.dashboard')

@section('title', 'Edit Absensi')
@section('page_title', 'Edit Data Absensi')

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

<form action="{{ route('teacher.attendance.update', $session->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card" style="max-width: 900px; margin-bottom: 2rem;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Absensi ({{ $session->classroom->name }} - {{ $session->subject->subject_name }})
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="attendance_date" class="form-label">Tanggal Absensi <span style="color: red;">*</span></label>
                <input type="date" name="attendance_date" id="attendance_date" class="form-control" value="{{ old('attendance_date', $session->attendance_date) }}" required>
            </div>
            <div class="form-group">
                <label for="notes" class="form-label">Catatan Sesi Absensi (Opsional)</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes', $session->notes) }}" placeholder="Contoh: Pertemuan 1">
            </div>
        </div>
    </div>

    <div class="card" style="max-width: 900px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Daftar Hadir Siswa
        </h3>

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
                        @php
                            $record = $records->get($student->id);
                            $status = $record ? $record->status : 'hadir';
                            $recNotes = $record ? $record->notes : '';
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $student->user->full_name }}</div>
                            </td>
                            <td style="font-family: monospace;">{{ $student->nis }}</td>
                            <td>
                                <div style="display: flex; gap: 1rem; justify-content: center; align-items: center;">
                                    <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer;">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="hadir" {{ $status === 'hadir' ? 'checked' : '' }}> Hadir
                                    </label>
                                    <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #b45309;">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="izin" {{ $status === 'izin' ? 'checked' : '' }}> Izin
                                    </label>
                                    <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #047857;">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="sakit" {{ $status === 'sakit' ? 'checked' : '' }}> Sakit
                                    </label>
                                    <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 500; cursor: pointer; color: #b91c1c;">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="alpha" {{ $status === 'alpha' ? 'checked' : '' }}> Alpha
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="record_notes[{{ $student->id }}]" class="form-control" value="{{ $recNotes }}" placeholder="Catatan..." style="padding: 0.35rem 0.5rem; font-size: 13px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('teacher.attendance.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection
