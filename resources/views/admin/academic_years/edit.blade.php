@extends('layouts.dashboard')

@section('title', 'Edit Tahun Ajaran')
@section('page_title', 'Edit Tahun Ajaran')

@section('nav')
    @include('admin.nav', ['active' => 'academic-years'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.academic-years.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Tahun Ajaran
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

<form action="{{ route('admin.academic-years.update', $academicYear->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card" style="max-width: 700px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Detail Tahun Ajaran
        </h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="year_label" class="form-label">Label Tahun Ajaran <span style="color: red;">*</span></label>
                <input type="text" name="year_label" id="year_label" class="form-control" value="{{ old('year_label', $academicYear->year_label) }}" placeholder="Contoh: 2025/2026" required>
            </div>

            <div class="form-group">
                <label for="semester" class="form-label">Semester <span style="color: red;">*</span></label>
                <select name="semester" id="semester" class="form-select" required>
                    <option value="ganjil" {{ old('semester', $academicYear->semester) === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ old('semester', $academicYear->semester) === 'genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date" class="form-label">Tanggal Mulai <span style="color: red;">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $academicYear->start_date) }}" required>
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">Tanggal Selesai <span style="color: red;">*</span></label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $academicYear->end_date) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="is_active" class="form-label">Status Keaktifan <span style="color: red;">*</span></label>
            <select name="is_active" id="is_active" class="form-select" required>
                <option value="0" {{ old('is_active', $academicYear->is_active) == '0' ? 'selected' : '' }}>Tidak Aktif (Arsip)</option>
                <option value="1" {{ old('is_active', $academicYear->is_active) == '1' ? 'selected' : '' }}>Aktif (Semester Berjalan)</option>
            </select>
            <small style="color: var(--on-surface-variant); font-size: 12px; margin-top: 0.25rem; display: block;">
                Jika diaktifkan, semester aktif yang lain akan otomatis dinonaktifkan.
            </small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Perbarui Tahun Ajaran</button>
            <a href="{{ route('admin.academic-years.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection
