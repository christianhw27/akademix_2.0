@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal')
@section('page_title', 'Tambah Jadwal Pelajaran')

@section('nav')
    @include('admin.nav', ['active' => 'schedules'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.schedules.index', ['classroom_id' => $selectedClassroomId]) }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Jadwal
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

<form action="{{ route('admin.schedules.store') }}" method="POST">
    @csrf
    
    <div class="card" style="max-width: 800px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Detail Jadwal
        </h3>
        
        <div class="form-group">
            <label for="classroom_id" class="form-label">Kelas <span style="color: red;">*</span></label>
            <select name="classroom_id" id="classroom_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classrooms as $cr)
                    <option value="{{ $cr->id }}" {{ old('classroom_id', $selectedClassroomId) == $cr->id ? 'selected' : '' }}>
                        {{ $cr->studyClass->name }} - T.A {{ $cr->academicYear->year_label }} ({{ ucfirst($cr->academicYear->semester) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="subject_id" class="form-label">Mata Pelajaran <span style="color: red;">*</span></label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="teacher_id" class="form-label">Guru Pengajar <span style="color: red;">*</span></label>
                <select name="teacher_id" id="teacher_id" class="form-select" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="day_of_week" class="form-label">Hari <span style="color: red;">*</span></label>
            <select name="day_of_week" id="day_of_week" class="form-select" required>
                <option value="">-- Pilih Hari --</option>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                    <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_time" class="form-label">Jam Mulai <span style="color: red;">*</span></label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
            </div>

            <div class="form-group">
                <label for="end_time" class="form-label">Jam Selesai <span style="color: red;">*</span></label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            <a href="{{ route('admin.schedules.index', ['classroom_id' => $selectedClassroomId]) }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection
