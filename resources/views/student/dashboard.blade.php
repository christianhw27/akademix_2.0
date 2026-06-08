@extends('layouts.dashboard')

@section('title', 'Ringkasan Siswa')
@section('page_title', 'Ringkasan')

@section('nav')
    @include('student.nav', ['active' => 'dashboard'])
@endsection

@push('styles')
<style>
    /* ── Dashboard Header ── */
    .dash-header {
        margin-bottom: 2rem;
    }
    .dash-header .subtitle {
        color: var(--on-surface-variant);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .dash-header h2 {
        font-size: 26px;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: 0.25rem;
    }
    .dash-header .lead {
        color: var(--on-surface-variant);
        font-size: 14px;
    }

    /* ── Stats Row ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--outline);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }
    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--on-surface-variant);
        margin-bottom: 0.5rem;
    }
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }
    /* Decorative circle */
    .stat-card::after {
        content: '';
        position: absolute;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        right: -10px;
        bottom: -15px;
        opacity: 0.12;
    }
    .stat-card.materi { border-left: 4px solid var(--primary); }
    .stat-card.materi .stat-value { color: var(--primary); }
    .stat-card.materi::after { background: var(--primary); }

    .stat-card.tugas { border-left: 4px solid var(--secondary); }
    .stat-card.tugas .stat-value { color: var(--secondary); }
    .stat-card.tugas::after { background: var(--secondary); }

    .stat-card.izin { border-left: 4px solid #6366f1; }
    .stat-card.izin .stat-value { color: #6366f1; }
    .stat-card.izin::after { background: #6366f1; }

    .stat-card.sakit { border-left: 4px solid #f59e0b; }
    .stat-card.sakit .stat-value { color: #d97706; }
    .stat-card.sakit::after { background: #f59e0b; }

    .stat-card.alfa { border-left: 4px solid #ef4444; }
    .stat-card.alfa .stat-value { color: #ef4444; }
    .stat-card.alfa::after { background: #ef4444; }

    /* ── Section Card (white wrapper) ── */
    .section-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.75rem 2rem;
        border: 1px solid var(--outline);
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .section-card .section-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-card .section-title .accent-bar {
        width: 4px;
        height: 22px;
        background: var(--primary);
        border-radius: 4px;
        display: inline-block;
    }

    /* ── Modul Aktif ── */
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .module-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-radius: var(--radius);
        border: 1px solid var(--outline);
        text-decoration: none;
        color: var(--on-surface);
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }
    .module-link:hover {
        background: #eef2ff;
        border-color: var(--primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,35,111,0.08);
    }
    .module-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .module-icon.jadwal { background: #dbeafe; }
    .module-icon.materi { background: #d1fae5; }
    .module-icon.kehadiran { background: #fef3c7; }
    .module-icon.rapor { background: #ede9fe; }

    /* ── Jadwal Hari Ini ── */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }
    .schedule-card {
        background: #f8fafc;
        border: 1px solid var(--outline);
        border-radius: var(--radius);
        padding: 1.25rem;
        transition: all 0.2s;
        position: relative;
    }
    .schedule-card:hover {
        background: #eef2ff;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,35,111,0.08);
    }
    .schedule-card .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 12px;
        color: var(--on-surface-variant);
        font-weight: 500;
        margin-bottom: 0.6rem;
    }
    .schedule-card .time-badge svg {
        width: 14px;
        height: 14px;
        opacity: 0.6;
    }
    .schedule-card .subject-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .schedule-card .teacher-name {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 13px;
        color: var(--on-surface-variant);
        font-weight: 500;
    }
    .schedule-card .teacher-name svg {
        width: 14px;
        height: 14px;
        opacity: 0.5;
    }
    .schedule-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--on-surface-variant);
    }
    .schedule-empty .empty-icon {
        font-size: 40px;
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .stats-row { grid-template-columns: repeat(3, 1fr); }
        .modules-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .modules-grid { grid-template-columns: 1fr; }
        .schedule-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- Flash Message --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Dashboard Header --}}
<div class="dash-header">
    <div class="subtitle">DASHBOARD</div>
    <h2>Ringkasan Siswa</h2>
    <div class="lead">Akses cepat ke modul utama AKADEMIX.</div>
</div>

{{-- Stats Row --}}
<div class="stats-row">
    <div class="stat-card materi">
        <div class="stat-label">Materi Tersedia</div>
        <div class="stat-value">{{ $stats['materials_count'] }}</div>
    </div>
    <div class="stat-card tugas">
        <div class="stat-label">Tugas Aktif</div>
        <div class="stat-value">{{ $stats['assignments_count'] }}</div>
    </div>
    <div class="stat-card izin">
        <div class="stat-label">Izin</div>
        <div class="stat-value">{{ $stats['izin_count'] }}</div>
    </div>
    <div class="stat-card sakit">
        <div class="stat-label">Sakit</div>
        <div class="stat-value">{{ $stats['sakit_count'] }}</div>
    </div>
    <div class="stat-card alfa">
        <div class="stat-label">Alfa</div>
        <div class="stat-value">{{ $stats['alpha_count'] }}</div>
    </div>
</div>

{{-- Modul Aktif --}}
<div class="section-card">
    <div class="section-title">
        <span class="accent-bar"></span>
        Modul Aktif
    </div>
    <div class="modules-grid">
        <a href="#jadwal-section" class="module-link">
            <div class="module-icon jadwal">📅</div>
            Jadwal Pelajaran
        </a>
        <a href="{{ route('student.materials') }}" class="module-link">
            <div class="module-icon materi">📗</div>
            Materi &amp; Tugas
        </a>
        <a href="{{ route('student.attendance') }}" class="module-link">
            <div class="module-icon kehadiran">✅</div>
            Lihat Kehadiran
        </a>
        <a href="{{ route('student.grades') }}" class="module-link">
            <div class="module-icon rapor">📊</div>
            Lihat Rapor
        </a>
    </div>
</div>

{{-- Jadwal Pelajaran Hari Ini --}}
<div class="section-card" id="jadwal-section">
    <div class="section-title">
        <span class="accent-bar"></span>
        📅 Jadwal Pelajaran Hari Ini ({{ $todayName }})
    </div>

    @if($todaySchedules->isEmpty())
        <div class="schedule-empty">
            <div class="empty-icon">📭</div>
            <p style="font-weight: 600; margin-bottom: 0.25rem;">Tidak ada jadwal hari ini</p>
            <p style="font-size: 13px;">Hari {{ $todayName }} tidak memiliki mata pelajaran terjadwal untuk kelas Anda.</p>
        </div>
    @else
        <div class="schedule-grid">
            @foreach($todaySchedules as $schedule)
                <div class="schedule-card">
                    <div class="time-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </div>
                    <div class="subject-name">{{ $schedule->subject->name }}</div>
                    <div class="teacher-name">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ $schedule->teacher->user->full_name }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
