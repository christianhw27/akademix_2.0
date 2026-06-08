@extends('layouts.dashboard')

@section('title', 'Detail Tugas')
@section('page_title', 'Tugas: ' . $assignment->title)

@section('nav')
    @include('student.nav', ['active' => 'assignments'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('student.assignments') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Tugas
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem; align-items: start;">
    <!-- Assignment detail & content -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--outline); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 18px; font-weight: 600; color: var(--primary);">
                    Instruksi Tugas
                </h3>
                <span class="badge badge-success">{{ $assignment->subject->subject_name }}</span>
            </div>

            <div style="font-size: 15px; line-height: 1.8; color: var(--on-surface); white-space: pre-wrap; margin-bottom: 2rem;">
                {{ $assignment->description }}
            </div>

            @include('components.attachment-view', ['attachment' => $assignment->attachment, 'label' => 'Lampiran Soal dari Guru'])
            
            <div style="font-size: 12px; color: var(--on-surface-variant); border-top: 1px solid var(--outline); padding-top: 1rem; margin-top: 1.5rem;">
                Dibuat oleh Guru: <strong>{{ $assignment->teacher->user->full_name }}</strong>
            </div>
        </div>

        <!-- Student Submission / Answer Card -->
        @if(!$submission)
            <!-- Form to Submit -->
            <div class="card">
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
                    Kirim Jawaban Anda
                </h3>
                
                @if(now()->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                    <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
                        Tenggat waktu pengerjaan tugas telah berakhir. Anda masih bisa mengumpulkan, tetapi akan tercatat sebagai <strong>Terlambat</strong>.
                    </div>
                @endif
                <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="content" class="form-label">Jawaban Tugas (Teks)</label>
                        <textarea name="content" id="content" class="form-control" rows="8" placeholder="Ketik atau tempel jawaban tugas Anda secara mendalam di sini..." style="resize: vertical; font-family: inherit;"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran File Jawaban <small style="color: var(--on-surface-variant); font-weight: 400;">(Opsional — bisa tambah berkali-kali, maks. 20 MB/file)</small></label>
                        @include('components.file-uploader', ['inputName' => 'attachments[]', 'uploaderId' => 'sub-new'])
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            Kirim Jawaban Sekarang
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Submitted Answer -->
            <div class="card">
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
                    Jawaban Anda
                </h3>

                <div style="background-color: #f8fafc; padding: 1.25rem; border-radius: var(--radius); border: 1px solid var(--outline); margin-bottom: 1.5rem;">
                    <div style="font-size: 12px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase; margin-bottom: 0.75rem;">
                        Teks Jawaban dikirim pada {{ date('d M Y, H:i', strtotime($submission->submitted_at)) }}:
                    </div>
                    <div style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;">{{ $submission->content }}</div>
                    
                    <div style="margin-top: 1rem;">
                        @include('components.attachment-view', ['attachment' => $submission->attachment, 'label' => 'Lampiran Jawaban Anda'])
                    </div>
                </div>

                @if($submission->status === 'submitted' && auth()->user()->role === 'student')
                    <!-- Allow update of submission before due date -->
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 1rem; color: var(--on-surface);">Revisi Jawaban</h4>
                    <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control" rows="6" style="resize: vertical; font-family: inherit;">{{ $submission->content }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tambah / Ganti Lampiran <small style="color: var(--on-surface-variant); font-weight: 400;">(maks. 20 MB/file)</small></label>
                        @include('components.attachment-current', ['attachment' => $submission->attachment ?? null, 'inputId' => 'sub-revise'])
                        @include('components.file-uploader', ['inputName' => 'attachments[]', 'uploaderId' => 'sub-revise-new'])
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            Perbarui Jawaban
                        </button>
                    </div>
                </form>
                @endif
            </div>
        @endif
    </div>

    <!-- Sidebar details -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 1rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem;">
                Informasi Tugas
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 13px;">
                <div>
                    <span style="color: var(--on-surface-variant); display: block;">Tenggat Waktu (Due Date)</span>
                    <strong style="color: #ef4444; font-size: 14px;">{{ date('d M Y, H:i', strtotime($assignment->due_date)) }}</strong>
                </div>
                <div>
                    <span style="color: var(--on-surface-variant); display: block;">Status Pengerjaan</span>
                    @if(!$submission)
                        <span class="badge badge-danger">Belum Mengumpulkan</span>
                    @elseif($submission->status === 'submitted')
                        @if(Carbon\Carbon::parse($submission->submitted_at)->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                            <span class="badge badge-warning" style="background: #fef08a; color: #854d0e;">Terlambat Mengumpulkan (Menunggu Koreksi)</span>
                        @else
                            <span class="badge badge-warning">Sudah Dikirim (Tepat Waktu)</span>
                        @endif
                    @elseif($submission->status === 'reviewed')
                        <span class="badge badge-success">Sudah Diperiksa</span>
                    @endif
                </div>
            </div>
        </div>

        @if($submission && $submission->status === 'reviewed')
            <div class="card" style="border-left: 4px solid #10b981; margin-bottom: 0;">
                <h3 style="font-size: 16px; font-weight: 600; color: #059669; margin-bottom: 1rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem;">
                    Hasil Penilaian
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="text-align: center; padding: 1rem 0;">
                        <span style="color: var(--on-surface-variant); display: block; font-size: 12px; text-transform: uppercase;">Nilai Anda</span>
                        <strong style="font-size: 48px; font-weight: 800; color: var(--primary);">{{ number_format($submission->score, 0) }}</strong>
                        <span style="display: block; font-size: 12px; color: var(--on-surface-variant); margin-top: 0.25rem;">Skor 0 - 100</span>
                    </div>

                    <div style="font-size: 13px; border-top: 1px solid var(--outline); padding-top: 0.75rem;">
                        <span style="color: var(--on-surface-variant); font-weight: 500; display: block; margin-bottom: 0.25rem;">Catatan/Feedback Guru:</span>
                        <p style="font-style: italic; color: var(--on-surface); line-height: 1.5; background-color: #f0fdf4; padding: 0.5rem; border-radius: var(--radius);">
                            "{{ $submission->feedback ?? 'Tidak ada umpan balik tertulis.' }}"
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@include('components.file-preview-script')
@endpush
