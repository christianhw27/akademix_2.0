@extends('layouts.dashboard')

@section('title', 'Rapor Hasil Belajar')
@section('page_title', 'Rapor Hasil Belajar')

@section('nav')
    @include('student.nav', ['active' => 'grades'])
@endsection

@push('styles')
<style>
    .rapor-section {
        margin-bottom: 2.5rem;
    }
    
    .rapor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .rapor-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .rapor-table-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--outline);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .rapor-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rapor-table th {
        background: #f8fafc;
        color: var(--on-surface-variant);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--outline);
    }

    .rapor-table td {
        padding: 1.25rem;
        border-bottom: 1px solid var(--outline);
        font-size: 14px;
        vertical-align: middle;
    }

    .rapor-table tr:last-child td {
        border-bottom: none;
    }

    .subject-info {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .subject-name {
        font-weight: 700;
        color: var(--on-surface);
    }

    .subject-code {
        font-size: 11px;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
    }

    .score-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        width: 46px;
        height: 46px;
        border-radius: 50%;
    }

    .score-high {
        background: #e6fdf5;
        color: #059669;
        border: 2px solid #a7f3d0;
    }

    .score-mid {
        background: #fffbeb;
        color: #d97706;
        border: 2px solid #fde68a;
    }

    .score-low {
        background: #fef2f2;
        color: #ef4444;
        border: 2px solid #fca5a5;
    }

    .notes-text {
        font-size: 13px;
        color: var(--on-surface-variant);
        line-height: 1.45;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<div class="flex-between" style="margin-bottom: 1.5rem;">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Berikut adalah lembar rapor hasil belajar resmi Anda. Nilai Rapor diisi langsung oleh masing-masing guru pengajar mata pelajaran.
        </p>
    </div>
</div>

@php
    $gradesByYear = $raporGrades->groupBy('academic_year_id');
@endphp

@if($gradesByYear->isEmpty())
    <div class="card" style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
        <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📊</div>
        <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Data Nilai Kosong</h4>
        <p style="font-size: 14px;">Belum ada rekaman nilai rapor resmi untuk Anda pada sistem Akademix.</p>
    </div>
@else
    @foreach($gradesByYear as $yearId => $yearGrades)
        @php
            $firstGrade = $yearGrades->first();
            $academicYear = $firstGrade->academicYear;
            $isActive = $activeYear && ($activeYear->id == $yearId);
        @endphp
        <div class="rapor-section">
            <div class="rapor-header">
                <div class="rapor-title">
                    Tahun Ajaran: {{ $academicYear->year_label }} (Semester {{ ucfirst($academicYear->semester) }})
                </div>
                @if($isActive)
                    <span class="badge badge-success">Semester Aktif</span>
                @else
                    <span class="badge" style="background-color: #cbd5e1; color: #475569;">Arsip</span>
                @endif
            </div>

            <div class="rapor-table-card">
                <table class="rapor-table">
                    <thead>
                        <tr>
                            <th style="width: 300px;">Mata Pelajaran</th>
                            <th style="width: 250px;">Guru Pengajar</th>
                            <th style="text-align: center; width: 100px;">Nilai Rapor</th>
                            <th>Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($yearGrades as $grade)
                            @php
                                $scoreVal = (float)$grade->score;
                                $scoreClass = 'score-mid';
                                if ($scoreVal >= 85) {
                                    $scoreClass = 'score-high';
                                } elseif ($scoreVal < 75) {
                                    $scoreClass = 'score-low';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="subject-info">
                                        <span class="subject-code">{{ $grade->subject->code }}</span>
                                        <span class="subject-name">{{ $grade->subject->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--on-surface);">
                                        {{ $grade->teacher->user->full_name }}
                                    </div>
                                    <div style="font-size: 11px; color: var(--on-surface-variant);">
                                        NIP: {{ $grade->teacher->nip ?? '-' }}
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div class="score-badge {{ $scoreClass }}">
                                        {{ number_format($scoreVal, 0) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="notes-text">
                                        {{ $grade->notes ?? 'Tidak ada catatan tambahan.' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif
@endsection
