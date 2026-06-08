@extends('layouts.dashboard')

@section('title', 'Edit Materi Pembelajaran')
@section('page_title', 'Edit Materi Pembelajaran')

@section('nav')
    @include('teacher.nav', ['active' => 'materials'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('teacher.materials.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Materi
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

<form action="{{ route('teacher.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card" style="max-width: 800px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Edit Detail Materi
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="classroom_id" class="form-label">Kelas Sasaran <span style="color: red;">*</span></label>
                <select name="classroom_id" id="classroom_id" class="form-select" required>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $material->classroom_id) == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject_id" class="form-label">Mata Pelajaran <span style="color: red;">*</span></label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Judul Materi <span style="color: red;">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $material->title) }}" placeholder="Masukkan judul materi..." required>
        </div>

        <div class="form-group">
            <label for="content" class="form-label">Konten Materi <span style="color: red;">*</span></label>
            <textarea name="content" id="content" class="form-control" rows="8" placeholder="Tulis konten pembelajaran secara detail di sini..." required style="resize: vertical; font-family: inherit;">{{ old('content', $material->content) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Lampiran File <small style="color: var(--on-surface-variant); font-weight: 400;">(Opsional — PDF, Word, Excel, PPT, Gambar, ZIP, maks. 20 MB/file)</small></label>
            @include('components.attachment-current', ['attachment' => $material->attachment, 'inputId' => 'mat-edit'])
            @include('components.file-uploader', ['inputName' => 'attachments[]', 'uploaderId' => 'mat-edit-new'])
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('teacher.materials.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@include('components.file-preview-script')
@endpush
