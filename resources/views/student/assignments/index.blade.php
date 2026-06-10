@extends('layouts.dashboard')

@section('title', 'Kelas & Tugas Saya')
@section('page_title', 'Kelas Pembelajaran')

@section('nav')
    @include('student.nav', ['active' => 'assignments'])
@endsection

@push('styles')
<style>
    .split-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 1.5rem;
        align-items: start;
        margin-top: 1.5rem;
    }

    /* Left Subject Explorer */
    .left-pane {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--outline);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .pane-header {
        padding: 1.25rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid var(--outline);
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .subject-list {
        display: flex;
        flex-direction: column;
        max-height: 600px;
        overflow-y: auto;
    }

    .subject-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        border-bottom: 1px solid var(--outline);
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        outline: none;
    }

    .subject-btn:last-child {
        border-bottom: none;
    }

    .subject-btn:hover {
        background: #f8fafc;
    }

    .subject-btn.active {
        background: #eef2ff;
        border-left: 4px solid var(--primary);
        padding-left: calc(1.5rem - 4px);
    }

    .subject-code {
        font-size: 11px;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .subject-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--on-surface);
        line-height: 1.3;
    }

    .subject-meta {
        font-size: 12px;
        color: var(--on-surface-variant);
        margin-top: 0.25rem;
    }

    /* Right Subject Contents */
    .right-pane {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--outline);
        padding: 2rem;
        min-height: 500px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .empty-pane-state {
        height: 100%;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--on-surface-variant);
        padding: 2rem;
    }

    .empty-pane-state span {
        font-size: 64px;
        margin-bottom: 1rem;
    }

    .empty-pane-state h4 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .subject-detail-header {
        border-bottom: 1px solid var(--outline);
        padding-bottom: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        position: relative;
    }

    .mobile-back-btn {
        display: none;
        align-self: flex-start;
        background: none;
        border: 1px solid var(--outline);
        padding: 0.4rem 0.8rem;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .mobile-back-btn:hover {
        background: #f1f5f9;
    }

    .detail-subject-code {
        font-size: 12px;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .detail-subject-name {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary);
        line-height: 1.2;
    }

    /* Sub-tabs styles */
    .tab-buttons {
        display: flex;
        border-bottom: 1px solid var(--outline);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 0.75rem 0.25rem;
        font-size: 14px;
        font-weight: 600;
        color: var(--on-surface-variant);
        cursor: pointer;
        position: relative;
        outline: none;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary);
    }

    /* Content Cards list */
    .items-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .item-card {
        border: 1px solid var(--outline);
        border-radius: var(--radius);
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .item-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius);
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .item-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .item-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--on-surface);
        line-height: 1.4;
    }

    .item-desc {
        font-size: 13px;
        color: var(--on-surface-variant);
        line-height: 1.5;
        margin-bottom: 0.25rem;
    }

    .item-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        font-size: 12px;
        color: var(--on-surface-variant);
    }

    .empty-sub-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--on-surface-variant);
        border: 2px dashed var(--outline);
        border-radius: var(--radius);
        font-size: 14px;
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .split-layout {
            grid-template-columns: 1fr;
        }

        .right-pane {
            display: none;
            padding: 1.25rem;
        }

        .mobile-back-btn {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="flex-between" style="margin-bottom: 1.5rem;">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Akses seluruh materi pengajaran dan kumpulkan tugas sesuai mata pelajaran di bawah ini.
        </p>
    </div>
</div>

@if($subjects->isEmpty())
    <div class="card" style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
        <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📖</div>
        <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Kelas</h4>
        <p style="font-size: 14px;">Belum ada jadwal mata pelajaran atau materi untuk kelas Anda saat ini.</p>
    </div>
@else
    <div class="split-layout">
        <!-- Left Subject Navigation -->
        <div class="left-pane">
            <div class="pane-header">Mata Pelajaran</div>
            <div class="subject-list">
                @foreach($subjects as $subject)
                    <button class="subject-btn" onclick="selectSubject({{ $subject->id }})" id="btn-sub-{{ $subject->id }}">
                        <span class="subject-code">{{ $subject->code }}</span>
                        <span class="subject-title">{{ $subject->name }}</span>
                        <span class="subject-meta">
                            {{ $subject->materials_count }} Materi &bull; {{ $subject->assignments_count }} Tugas
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Right Subject Contents -->
        <div class="right-pane">
            <!-- Default Empty Selection View -->
            <div class="empty-pane-state" id="empty-state">
                <span>📖</span>
                <h4>Pilih Mata Pelajaran</h4>
                <p>Klik salah satu mata pelajaran di sebelah kiri untuk melihat materi dan tugas.</p>
            </div>

            <!-- Subject Detail Panels -->
            @foreach($subjects as $subject)
                <div class="subject-detail" id="subject-detail-{{ $subject->id }}" style="display: none;">
                    <button class="mobile-back-btn" onclick="showLeftPane()">
                        &larr; Kembali ke Daftar
                    </button>
                    
                    <div class="subject-detail-header">
                        <span class="detail-subject-code">{{ $subject->code }}</span>
                        <h3 class="detail-subject-name">{{ $subject->name }}</h3>
                    </div>

                    <!-- Sub Tab Navigation -->
                    <div class="tab-buttons">
                        <button class="tab-btn active" onclick="switchSubTab({{ $subject->id }}, 'materials')" id="tab-btn-{{ $subject->id }}-materials">
                            Materi ({{ $subject->materials_count }})
                        </button>
                        <button class="tab-btn" onclick="switchSubTab({{ $subject->id }}, 'assignments')" id="tab-btn-{{ $subject->id }}-assignments">
                            Tugas ({{ $subject->assignments_count }})
                        </button>
                    </div>

                    <!-- Materials Panel -->
                    <div class="tab-panel" id="panel-{{ $subject->id }}-materials">
                        @if($subject->materials->isEmpty())
                            <div class="empty-sub-state">
                                Belum ada materi pembelajaran yang dibagikan.
                            </div>
                        @else
                            <div class="items-list">
                                @foreach($subject->materials as $material)
                                    <div class="item-card">
                                        <div class="item-icon">📗</div>
                                        <div class="item-body">
                                            <h4 class="item-title">{{ $material->title }}</h4>
                                            <p class="item-desc">{{ Str::limit(strip_tags($material->content), 140) }}</p>
                                            <div class="item-meta">
                                                <span>Guru: {{ $material->teacher->user->full_name }}</span>
                                                <span>&bull;</span>
                                                <span>Rilis: {{ $material->created_at ? $material->created_at->format('d M Y H:i') : '-' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('student.materials.show', $material->id) }}" class="btn btn-primary btn-sm" style="white-space: nowrap; flex-shrink: 0;">
                                            Buka Materi
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Assignments Panel -->
                    <div class="tab-panel" id="panel-{{ $subject->id }}-assignments" style="display: none;">
                        @if($subject->assignments->isEmpty())
                            <div class="empty-sub-state">
                                Belum ada tugas untuk mata pelajaran ini.
                            </div>
                        @else
                            <div class="items-list">
                                @foreach($subject->assignments as $assignment)
                                    @php
                                        $sub = $submissions->get($assignment->id);
                                        $isOverdue = now()->greaterThan(Carbon\Carbon::parse($assignment->due_date));
                                    @endphp
                                    <div class="item-card">
                                        <div class="item-icon">📝</div>
                                        <div class="item-body">
                                            <h4 class="item-title">{{ $assignment->title }}</h4>
                                            <p class="item-desc">{{ Str::limit($assignment->description, 140) }}</p>
                                            <div class="item-meta">
                                                <span style="color: {{ ($isOverdue && !$sub) ? '#ef4444' : 'var(--on-surface-variant)' }}; font-weight: 500;">
                                                    Tenggat: {{ date('d M Y H:i', strtotime($assignment->due_date)) }}
                                                    @if($isOverdue && !$sub) (Terlambat) @endif
                                                </span>
                                                <span>&bull;</span>
                                                @if(!$sub)
                                                    <span class="badge badge-danger">Belum Mengumpulkan</span>
                                                @elseif($sub->status === 'submitted')
                                                    @if(Carbon\Carbon::parse($sub->submitted_at)->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                                                        <span class="badge badge-warning" style="background: #fef08a; color: #854d0e;">Dikirim (Terlambat)</span>
                                                    @else
                                                        <span class="badge badge-warning">Sudah Dikirim</span>
                                                    @endif
                                                @elseif($sub->status === 'reviewed')
                                                    @if(Carbon\Carbon::parse($sub->submitted_at)->greaterThan(Carbon\Carbon::parse($assignment->due_date)))
                                                        <span class="badge badge-success" style="background: #dcfce7; color: #166534;">Selesai Dinilai (Terlambat)</span>
                                                    @else
                                                        <span class="badge badge-success">Selesai Dinilai</span>
                                                    @endif
                                                @endif

                                                @if($sub && $sub->status === 'reviewed')
                                                    <span style="font-weight: 700; color: var(--primary); margin-left: 0.5rem;">
                                                        Nilai: {{ number_format($sub->score, 0) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-primary btn-sm" style="white-space: nowrap; flex-shrink: 0;">
                                            {{ $sub ? 'Lihat Detail' : 'Kerjakan →' }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<script>
    function selectSubject(subjectId) {
        // Hide empty pane state
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.style.display = 'none';

        // Hide all subject details
        const details = document.querySelectorAll('.subject-detail');
        details.forEach(d => d.style.display = 'none');

        // Remove active states from buttons
        const buttons = document.querySelectorAll('.subject-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Show selected subject detail
        const currentDetail = document.getElementById('subject-detail-' + subjectId);
        if (currentDetail) currentDetail.style.display = 'block';

        // Set button active
        const currentBtn = document.getElementById('btn-sub-' + subjectId);
        if (currentBtn) currentBtn.classList.add('active');

        // Responsive toggle
        if (window.innerWidth <= 768) {
            document.querySelector('.right-pane').style.display = 'block';
            document.querySelector('.left-pane').style.display = 'none';
        }
    }

    function showLeftPane() {
        document.querySelector('.right-pane').style.display = 'none';
        document.querySelector('.left-pane').style.display = 'block';
    }

    function switchSubTab(subjectId, tabName) {
        // Hide both panels
        document.getElementById('panel-' + subjectId + '-materials').style.display = 'none';
        document.getElementById('panel-' + subjectId + '-assignments').style.display = 'none';

        // Remove active class from tab buttons
        const detailContainer = document.getElementById('subject-detail-' + subjectId);
        const tabs = detailContainer.querySelectorAll('.tab-btn');
        tabs.forEach(t => t.classList.remove('active'));

        // Show panel
        document.getElementById('panel-' + subjectId + '-' + tabName).style.display = 'block';

        // Set active class
        document.getElementById('tab-btn-' + subjectId + '-' + tabName).classList.add('active');
    }
</script>
@endsection
