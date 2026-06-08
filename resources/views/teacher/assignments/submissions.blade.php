@extends('layouts.dashboard')

@section('title', 'Submisi Tugas')
@section('page_title', 'Submisi Tugas: ' . $assignment->title)

@section('nav')
    @include('teacher.nav', ['active' => 'assignments'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('teacher.assignments.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Tugas
    </a>
</div>

<div class="card" style="background-color: #f8fafc; border-color: var(--outline); margin-bottom: 2rem;">
    <h3 style="font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">Detail Tugas</h3>
    <div style="font-size: 14px; color: var(--on-surface-variant);">
        <p><strong>Kelas:</strong> {{ $assignment->classroom->name }}</p>
        <p><strong>Mata Pelajaran:</strong> {{ $assignment->subject->subject_name }}</p>
        <p><strong>Tenggat Waktu:</strong> {{ date('d M Y H:i', strtotime($assignment->due_date)) }}</p>
        <p style="margin-top: 0.5rem; border-top: 1px solid var(--outline); padding-top: 0.5rem;">
            <strong>Deskripsi:</strong><br>
            {{ $assignment->description }}
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1rem; color: var(--primary);">
    Daftar Siswa & Status Pengumpulan
</h3>

<div style="display: grid; gap: 1.5rem;">
    @foreach($students as $student)
        @php
            $submission = $submissions->get($student->id);
        @endphp
        
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--outline); padding-bottom: 1rem; margin-bottom: 1rem;">
                <div>
                    <h4 style="font-size: 16px; font-weight: 600; color: var(--on-surface);">
                        {{ $student->user->full_name }}
                    </h4>
                    <p style="font-size: 12px; color: var(--on-surface-variant);">
                        NIS: {{ $student->nis }}
                    </p>
                </div>
                
                <div>
                    @if(!$submission)
                        <span class="badge badge-danger">Belum Mengumpulkan</span>
                    @elseif($submission->status === 'submitted')
                        @if(Carbon\Carbon::parse($submission->submitted_at)->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                            <span class="badge badge-warning" style="background: #fef08a; color: #854d0e;">Terlambat Mengumpulkan (Menunggu Koreksi)</span>
                        @else
                            <span class="badge badge-warning">Menunggu Koreksi (Tepat Waktu)</span>
                        @endif
                    @elseif($submission->status === 'reviewed')
                        @if(Carbon\Carbon::parse($submission->submitted_at)->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                            <span class="badge badge-success" style="background: #dcfce7; color: #166534;">Selesai Diperiksa (Terlambat) - Nilai: {{ number_format($submission->score, 0) }}</span>
                        @else
                            <span class="badge badge-success">Selesai Diperiksa (Nilai: {{ number_format($submission->score, 0) }})</span>
                        @endif
                    @endif
                </div>
            </div>

            @if($submission)
                <div style="font-size: 14px; margin-bottom: 1rem;">
                    <div style="background-color: #f1f5f9; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
                        <p style="font-weight: 600; margin-bottom: 0.25rem; font-size: 12px; color: var(--on-surface-variant); text-transform: uppercase;">
                            Konten Jawaban Siswa (Dikirim: {{ date('d M Y H:i', strtotime($submission->submitted_at)) }}):
                        </p>
                        <div style="white-space: pre-wrap; font-family: inherit; line-height: 1.6;">{{ $submission->content }}</div>
                    </div>

                    @include('components.attachment-view', ['attachment' => $submission->attachment, 'label' => 'Lampiran Jawaban Siswa'])

                    <!-- Grading Form -->
                    <form action="{{ route('teacher.submissions.grade', $submission->id) }}" method="POST" style="background-color: #f8fafc; border: 1px dashed var(--outline); padding: 1.25rem; border-radius: var(--radius);">
                        @csrf
                        <h5 style="font-size: 14px; font-weight: 600; color: var(--primary); margin-bottom: 1rem;">
                            {{ $submission->status === 'reviewed' ? 'Perbarui Penilaian' : 'Beri Nilai & Feedback' }}
                        </h5>
                        
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="score_{{ $submission->id }}" class="form-label">Skor Nilai (0 - 100) <span style="color: red;">*</span></label>
                                <input type="number" name="score" id="score_{{ $submission->id }}" class="form-control" min="0" max="100" step="1" value="{{ old('score', $submission->score) }}" required>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="feedback_{{ $submission->id }}" class="form-label">Umpan Balik / Catatan Guru</label>
                                <input type="text" name="feedback" id="feedback_{{ $submission->id }}" class="form-control" value="{{ old('feedback', $submission->feedback) }}" placeholder="Contoh: Kerja bagus! Pertahankan prestasimu.">
                            </div>
                        </div>

                        <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="padding: 1rem 0; text-align: center; color: var(--on-surface-variant); font-size: 14px; font-style: italic;">
                    Siswa belum mengumpulkan tugas ini.
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
