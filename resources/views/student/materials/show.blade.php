@extends('layouts.dashboard')

@section('title', $material->title)
@section('page_title', $material->title)

@section('nav')
    @include('student.nav', ['active' => 'materials'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('student.materials') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Materi
    </a>
</div>

<div class="card">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; border-bottom: 1px solid var(--outline); padding-bottom: 1rem; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                {{ $material->title }}
            </h2>
            <p style="font-size: 14px; color: var(--on-surface-variant);">
                Oleh Guru: <strong>{{ $material->teacher->user->full_name }}</strong>
            </p>
        </div>
        
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <span class="badge badge-primary">{{ $material->classroom->name }}</span>
            <span class="badge badge-success">{{ $material->subject->subject_name }}</span>
            <span class="badge" style="background-color: #f1f5f9;">{{ $material->academicYear->year_label }} ({{ ucfirst($material->academicYear->semester) }})</span>
        </div>
    </div>

    <!-- Content Readability Area -->
    <div style="font-size: 16px; line-height: 1.8; color: var(--on-surface); max-width: 900px; margin: 0 auto; padding: 0 1rem; white-space: pre-wrap;">
        {!! nl2br(e($material->content)) !!}
    </div>

    @if($material->attachment)
    <div style="max-width: 900px; margin: 2rem auto 0; padding: 0 1rem;">
        @include('components.attachment-view', ['attachment' => $material->attachment, 'label' => 'Lampiran Materi'])
    </div>
    @endif


    <div style="margin-top: 4rem; border-top: 1px solid var(--outline); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--on-surface-variant);">
        <span>Diterbitkan pada: {{ $material->created_at->format('d M Y, H:i') }}</span>
        <span>Terakhir diperbarui: {{ $material->updated_at->format('d M Y, H:i') }}</span>
    </div>
</div>
@endsection
