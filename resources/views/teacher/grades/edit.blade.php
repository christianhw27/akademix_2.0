@extends('layouts.dashboard')

@section('title', 'Edit Nilai Siswa')
@section('page_title', 'Edit Nilai Siswa')

@section('nav')
    @include('teacher.nav', ['active' => 'grades'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('teacher.grades.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Nilai
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

<form action="{{ route('teacher.grades.update', $grade->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card" style="max-width: 600px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Edit Nilai: {{ $grade->student->user->full_name }}
        </h3>
        
        <div style="font-size: 14px; margin-bottom: 1.5rem; background-color: #f8fafc; padding: 1rem; border-radius: var(--radius); border: 1px solid var(--outline);">
            <p><strong>Siswa:</strong> {{ $grade->student->user->full_name }} (NISN: {{ $grade->student->nis }})</p>
            <p><strong>Mata Pelajaran:</strong> {{ $grade->subject->subject_name }}</p>
            <p><strong>Kelas:</strong> {{ $grade->classroom->name }}</p>
            <p><strong>Tipe Nilai:</strong> {{ ucfirst($grade->grade_type) }}</p>
            <p><strong>Keterangan:</strong> {{ $grade->title }}</p>
        </div>

        <div class="form-group">
            <label for="score" class="form-label">Skor Nilai (0 - 100) <span style="color: red;">*</span></label>
            <input type="number" name="score" id="score" class="form-control" min="0" max="100" step="0.1" value="{{ old('score', $grade->score) }}" required style="font-size: 18px; font-weight: 700; color: var(--primary);">
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Catatan / Keterangan</label>
            <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Masukkan catatan tambahan... style="resize: vertical; font-family: inherit;">{{ old('notes', $grade->notes) }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('teacher.grades.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection
