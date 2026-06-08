@extends('layouts.dashboard')

@section('title', 'Kehadiran Anak')
@section('page_title', 'Kehadiran Anak')

@section('nav')
    @include('parent.nav', ['active' => 'attendance'])
@endsection

@push('styles')
<style>
    /* Stats Summary Grid */
    .attendance-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--outline);
        padding: 1.25rem 1.5rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
    }

    .summary-card .label {
        font-size: 12px;
        font-weight: 700;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 0.25rem;
    }

    .summary-card .value {
        font-size: 32px;
        font-weight: 800;
    }

    .summary-card.hadir { border-bottom: 4px solid #10b981; }
    .summary-card.hadir .value { color: #059669; }
    .summary-card.izin { border-bottom: 4px solid #f59e0b; }
    .summary-card.izin .value { color: #d97706; }
    .summary-card.sakit { border-bottom: 4px solid #06b6d4; }
    .summary-card.sakit .value { color: #0891b2; }
    .summary-card.alpha { border-bottom: 4px solid #ef4444; }
    .summary-card.alpha .value { color: #dc2626; }

    /* Calendar Styling */
    .calendar-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--outline);
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .calendar-ctrl-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--outline);
        padding-bottom: 1rem;
    }

    .calendar-month-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--primary);
    }

    .calendar-nav-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .calendar-nav-btn {
        background: white;
        border: 1px solid var(--outline);
        color: var(--primary);
        font-size: 16px;
        font-weight: 700;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .calendar-nav-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: scale(1.05);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .day-name-cell {
        text-align: center;
        font-weight: 700;
        font-size: 12px;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.5rem 0;
    }

    .calendar-cell {
        min-height: 85px;
        border: 1px solid var(--outline);
        border-radius: var(--radius);
        padding: 0.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: white;
        transition: all 0.2s ease;
    }

    .calendar-cell:hover:not(.empty-cell) {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.04);
    }

    .calendar-cell.empty-cell {
        background: #f8fafc;
        border-color: transparent;
    }

    .calendar-cell .day-num {
        font-size: 14px;
        font-weight: 700;
        color: var(--on-surface);
    }

    .calendar-cell .cell-status-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        padding: 0.15rem 0.35rem;
        border-radius: 4px;
        align-self: flex-start;
        text-align: center;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Status Colors */
    .calendar-cell.status-hadir {
        background: #e6fdf5;
        border-color: #a7f3d0;
    }
    .calendar-cell.status-hadir .cell-status-label {
        background: #10b981;
        color: white;
    }

    .calendar-cell.status-sebagian {
        background: #fffbeb;
        border-color: #fde68a;
    }
    .calendar-cell.status-sebagian .cell-status-label {
        background: #d97706;
        color: white;
    }

    .calendar-cell.status-tidak-hadir {
        background: #fef2f2;
        border-color: #fca5a5;
    }
    .calendar-cell.status-tidak-hadir .cell-status-label {
        background: #ef4444;
        color: white;
    }

    .calendar-cell.status-libur {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }
    .calendar-cell.status-libur .day-num {
        color: #94a3b8;
    }
    .calendar-cell.status-libur .cell-status-label {
        background: #64748b;
        color: white;
    }

    /* Legend */
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--outline);
        font-size: 12px;
        font-weight: 600;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 1px solid var(--outline);
    }

    @media (max-width: 768px) {
        .attendance-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .calendar-cell {
            min-height: 70px;
            padding: 0.25rem;
        }
        .calendar-cell .day-num {
            font-size: 12px;
        }
        .calendar-cell .cell-status-label {
            font-size: 8px;
            padding: 0.1rem 0.2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="flex-between" style="margin-bottom: 1.5rem;">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Berikut adalah tingkat kehadiran anak Anda: <strong>{{ $student->user->full_name }}</strong>. Gunakan navigasi panah untuk berpindah bulan.
        </p>
    </div>
</div>

{{-- Attendance Statistics Summary --}}
<div class="attendance-summary-grid">
    <div class="summary-card hadir">
        <span class="label">Hadir</span>
        <div class="value">{{ $summary['hadir'] }}</div>
    </div>
    <div class="summary-card izin">
        <span class="label">Izin</span>
        <div class="value">{{ $summary['izin'] }}</div>
    </div>
    <div class="summary-card sakit">
        <span class="label">Sakit</span>
        <div class="value">{{ $summary['sakit'] }}</div>
    </div>
    <div class="summary-card alpha">
        <span class="label">Alpha</span>
        <div class="value">{{ $summary['alpha'] }}</div>
    </div>
</div>

{{-- Calendar Card --}}
@if(!$classroom)
    <div class="card" style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
        <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">✅</div>
        <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Kelas Tidak Aktif</h4>
        <p style="font-size: 14px;">Anak Anda belum terdaftar di kelas aktif mana pun.</p>
    </div>
@else
    <div class="calendar-card">
        <div class="calendar-ctrl-header">
            <h3 class="calendar-month-title">{{ $displayMonthName }}</h3>
            <div class="calendar-nav-buttons">
                <a href="{{ route('parent.attendance', ['year' => $prevYear, 'month' => $prevMonth]) }}" class="calendar-nav-btn" title="Bulan Sebelumnya">
                    &larr;
                </a>
                <a href="{{ route('parent.attendance', ['year' => $nextYear, 'month' => $nextMonth]) }}" class="calendar-nav-btn" title="Bulan Berikutnya">
                    &rarr;
                </a>
            </div>
        </div>

        <div class="calendar-grid">
            <!-- Days of Week labels -->
            <div class="day-name-cell">Minggu</div>
            <div class="day-name-cell">Senin</div>
            <div class="day-name-cell">Selasa</div>
            <div class="day-name-cell">Rabu</div>
            <div class="day-name-cell">Kamis</div>
            <div class="day-name-cell">Jumat</div>
            <div class="day-name-cell">Sabtu</div>

            <!-- Calendar Cells -->
            @foreach($calendarWeeks as $week)
                @foreach($week as $day)
                    @if($day === null)
                        <div class="calendar-cell empty-cell"></div>
                    @else
                        @php
                            $status = $dailyStatuses[$day] ?? null;
                            $cellClass = '';
                            if ($status === 'HADIR PENUH') {
                                $cellClass = 'status-hadir';
                            } elseif ($status === 'SEBAGIAN') {
                                $cellClass = 'status-sebagian';
                            } elseif ($status === 'TIDAK HADIR') {
                                $cellClass = 'status-tidak-hadir';
                            } elseif ($status === 'LIBUR') {
                                $cellClass = 'status-libur';
                            }
                        @endphp
                        <div class="calendar-cell {{ $cellClass }}">
                            <span class="day-num">{{ $day }}</span>
                            @if($status)
                                <span class="cell-status-label" title="{{ $status }}">{{ $status }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>

        <!-- Legend -->
        <div class="calendar-legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #e6fdf5; border-color: #a7f3d0;"></div>
                <span>Hadir Penuh</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #fffbeb; border-color: #fde68a;"></div>
                <span>Izin / Sakit (Sebagian)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #fef2f2; border-color: #fca5a5;"></div>
                <span>Alpha (Tidak Hadir)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f1f5f9; border-color: #e2e8f0;"></div>
                <span>Hari Libur</span>
            </div>
        </div>
    </div>
@endif
@endsection
