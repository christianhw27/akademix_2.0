@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran')

@section('nav')
    @include('student.nav', ['active' => 'materials'])
@endsection

@push('styles')
<style>
    .schedule-weekly-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
        align-items: start;
    }

    .day-column {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        background: #f8fafc;
        border-radius: var(--radius);
        border: 1px solid var(--outline);
        padding: 0.75rem;
        min-height: 450px;
        transition: all 0.2s ease;
    }

    .day-column.today {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(0, 35, 111, 0.08);
    }

    .day-header {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        text-align: center;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--outline);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    .day-column.today .day-header {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .today-indicator {
        background: var(--primary);
        color: white;
        font-size: 10px;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-weight: 700;
    }

    .class-card {
        background: white;
        border: 1px solid var(--outline);
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .class-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border-color: var(--primary);
    }

    .class-time {
        font-size: 11px;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .class-time svg {
        width: 12px;
        height: 12px;
    }

    .class-subject {
        font-size: 13px;
        font-weight: 700;
        color: var(--on-surface);
        margin-bottom: 0.35rem;
        line-height: 1.3;
    }

    .class-teacher {
        font-size: 11px;
        color: var(--on-surface-variant);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .class-teacher svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
        opacity: 0.7;
    }

    .empty-day {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed var(--outline);
        border-radius: 6px;
        padding: 1.5rem 0.5rem;
        color: var(--on-surface-variant);
        text-align: center;
        opacity: 0.7;
        background: transparent;
    }

    .empty-day span {
        font-size: 24px;
        margin-bottom: 0.25rem;
    }

    .empty-text {
        font-size: 11px;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .schedule-weekly-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .day-column {
            min-height: auto;
        }
    }

    @media (max-width: 768px) {
        .schedule-weekly-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="flex-between">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Jadwal mingguan pelajaran aktif untuk Kelas: 
            <strong>{{ $classroom ? $classroom->name : 'Belum Ditentukan' }}</strong> 
            ({{ $activeYear ? $activeYear->year_label . ' - ' . ucfirst($activeYear->semester) : '-' }})
        </p>
    </div>
</div>

@if(!$classroom)
    <div class="card" style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
        <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📅</div>
        <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Kelas Tidak Aktif</h4>
        <p style="font-size: 14px;">Anda belum terdaftar di kelas manapun untuk tahun ajaran aktif ini.</p>
    </div>
@else
    @php
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Map PHP dayOfWeek to Indonesian day name
        $dayMap = [
            \Carbon\Carbon::MONDAY    => 'Senin',
            \Carbon\Carbon::TUESDAY   => 'Selasa',
            \Carbon\Carbon::WEDNESDAY => 'Rabu',
            \Carbon\Carbon::THURSDAY  => 'Kamis',
            \Carbon\Carbon::FRIDAY    => 'Jumat',
            \Carbon\Carbon::SATURDAY  => 'Sabtu',
        ];
        $todayName = $dayMap[\Carbon\Carbon::now()->dayOfWeek] ?? '';
    @endphp

    <div class="schedule-weekly-grid">
        @foreach($days as $day)
            @php
                $isToday = ($day === $todayName);
                $daySchedules = $schedulesByDay->get($day) ?? collect();
            @endphp
            <div class="day-column {{ $isToday ? 'today' : '' }}">
                <div class="day-header">
                    <span>{{ $day }}</span>
                    @if($isToday)
                        <span class="today-indicator">Hari Ini</span>
                    @endif
                </div>

                @if($daySchedules->isEmpty())
                    <div class="empty-day">
                        <span>☕</span>
                        <div class="empty-text">Kosong</div>
                    </div>
                @else
                    @foreach($daySchedules as $schedule)
                        <div class="class-card">
                            <div class="class-time">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </div>
                            <div class="class-subject" title="{{ $schedule->subject->name }}">
                                {{ $schedule->subject->name }}
                            </div>
                            <div class="class-teacher">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                {{ $schedule->teacher->user->full_name }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection
