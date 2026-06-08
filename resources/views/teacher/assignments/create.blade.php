@extends('layouts.dashboard')

@section('title', 'Buat Tugas Baru')
@section('page_title', 'Buat Tugas Baru')

@section('nav')
    @include('teacher.nav', ['active' => 'assignments'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('teacher.assignments.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Tugas
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

<form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card" style="max-width: 800px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Formulir Tugas Baru
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="classroom_id" class="form-label">Kelas Sasaran <span style="color: red;">*</span></label>
                <select name="classroom_id" id="classroom_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject_id" class="form-label">Mata Pelajaran <span style="color: red;">*</span></label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="title" class="form-label">Judul Tugas <span style="color: red;">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Tugas Mandiri Aljabar Linier" required>
            </div>

            <div class="form-group">
                <label for="due_date" class="form-label">Tenggat Waktu Pengumpulan <span style="color: red;">*</span></label>
                <input type="datetime-local" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi / Detail Instruksi Tugas <span style="color: red;">*</span></label>
            <textarea name="description" id="description" class="form-control" rows="8" placeholder="Masukkan detail tugas, instruksi pengerjaan, dan format jawaban..." required style="resize: vertical; font-family: inherit;">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Lampiran File Soal <small style="color: var(--on-surface-variant); font-weight: 400;">(Opsional — PDF, Word, Gambar, ZIP, maks. 20 MB/file)</small></label>
            @include('components.file-uploader', ['inputName' => 'attachments[]', 'uploaderId' => 'asgn-create'])
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan & Publikasikan</button>
            <a href="{{ route('teacher.assignments.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@include('components.file-preview-script')
@endpush
