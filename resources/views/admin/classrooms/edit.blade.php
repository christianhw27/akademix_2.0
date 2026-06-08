@extends('layouts.dashboard')

@section('title', 'Edit Rombongan Belajar')
@section('page_title', 'Edit Rombongan Belajar')

@section('nav')
    @include('admin.nav', ['active' => 'classrooms'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.classrooms.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Rombongan Belajar
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

<form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card" style="max-width: 800px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Konfigurasi Rombongan Belajar
        </h3>
        
        <div class="form-group">
            <label for="academic_year_id" class="form-label">Tahun Ajaran & Semester <span style="color: red;">*</span></label>
            <select name="academic_year_id" id="academic_year_id" class="form-select" required>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ old('academic_year_id', $classroom->academic_year_id) == $ay->id ? 'selected' : '' }}>
                        Tahun Ajaran {{ $ay->year_label }} - Semester {{ ucfirst($ay->semester) }} {{ $ay->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="grade_level" class="form-label">Tingkat Kelas <span style="color: red;">*</span></label>
                <select name="grade_level" id="grade_level" class="form-select" required>
                    @for($i = 7; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('grade_level', $classroom->grade_level) == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="class_id" class="form-label">Nama Rombel <span style="color: red;">*</span></label>
                <select name="class_id" id="class_id" class="form-select" required>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id', $classroom->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="homeroom_teacher_id" class="form-label">Wali Kelas <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Optional)</span></label>
            <select name="homeroom_teacher_id" id="homeroom_teacher_id" class="form-select">
                <option value="">-- Pilih Wali Kelas --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $classroom->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->user->full_name }} (NIP: {{ $teacher->nip }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.classrooms.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection
