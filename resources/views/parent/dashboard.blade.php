@extends('layouts.dashboard')

@section('title', 'Dashboard Orang Tua')
@section('page_title', 'Dashboard Wali Siswa')

@section('nav')
    @include('parent.nav', ['active' => 'dashboard'])
@endsection

@section('content')
<div style="margin-bottom: 2rem; background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.25rem; border-radius: var(--radius);">
    <h2 style="font-weight: 600; font-size: 18px; color: #15803d; margin-bottom: 0.25rem;">
        Sistem Pemantauan Siswa
    </h2>
    <p style="color: #166534; font-size: 14px;">
        Anda sedang memantau perkembangan akademik anak Anda: <strong>{{ $student->user->full_name }}</strong> (NISN: {{ $student->nis }}).
    </p>
</div>

<div style="margin-bottom: 2rem;">
    <p style="color: var(--on-surface-variant); font-size: 14px;">
        Kelas Saat Ini: <strong>{{ $classroom ? $classroom->name : 'Belum Dialokasikan' }}</strong> | 
        Semester: <strong>{{ $activeYear ? $activeYear->year_label . ' (' . ucfirst($activeYear->semester) . ')' : 'Tidak ada' }}</strong>
    </p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid var(--primary); margin-bottom: 0;">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Materi Pembelajaran</h4>
            <div style="font-size: 32px; font-weight: 700; color: var(--primary); margin: 0.5rem 0;">{{ $stats['materials_count'] }}</div>
        </div>
        <p style="color: var(--on-surface-variant); font-size: 12px; font-style: italic;">Materi dapat dilihat di akun anak.</p>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid var(--secondary); margin-bottom: 0;">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Tugas & Evaluasi</h4>
            <div style="font-size: 32px; font-weight: 700; color: var(--secondary); margin: 0.5rem 0;">{{ $stats['assignments_count'] }}</div>
        </div>
        <a href="{{ route('parent.assignments') }}" style="color: var(--secondary); font-size: 13px; font-weight: 500; text-decoration: none;">Pantau Tugas &rarr;</a>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #10b981; margin-bottom: 0;">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Kehadiran Anak</h4>
            <div style="font-size: 32px; font-weight: 700; color: #059669; margin: 0.5rem 0;">{{ $stats['attendance_rate'] }}%</div>
        </div>
        <a href="{{ route('parent.attendance') }}" style="color: #059669; font-size: 13px; font-weight: 500; text-decoration: none;">Pantau Kehadiran &rarr;</a>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #f59e0b; margin-bottom: 0;">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Rerata Nilai Rapor</h4>
            <div style="font-size: 32px; font-weight: 700; color: #d97706; margin: 0.5rem 0;">{{ $stats['average_score'] }}</div>
        </div>
        <a href="{{ route('parent.grades') }}" style="color: #d97706; font-size: 13px; font-weight: 500; text-decoration: none;">Buka Rapor &rarr;</a>
    </div>
</div>

<!-- Timetable / Schedules -->
<div class="card">
    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem;">
        Jadwal Belajar Anak
    </h3>

    @if($schedules->isEmpty())
        <div style="padding: 2rem; text-align: center; color: var(--on-surface-variant);">
            <p>Belum ada jadwal pelajaran yang dirilis untuk kelas anak Anda.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Pelajaran</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th>Ruang Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td style="font-weight: 600; text-transform: capitalize;">{{ $schedule->day_of_week }}</td>
                            <td style="font-family: monospace;">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</td>
                            <td style="font-weight: 600; color: var(--primary);">{{ $schedule->subject->subject_name }}</td>
                            <td>{{ $schedule->teacher->user->full_name }}</td>
                            <td><span class="badge badge-primary">{{ $schedule->room_number }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
