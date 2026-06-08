@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('nav')
    @include('teacher.nav', ['active' => 'dashboard'])
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-weight: 500; font-size: 20px; color: var(--on-surface-variant);">
        Selamat Datang, {{ $teacher->user->full_name }}!
    </h2>
    <p style="color: var(--on-surface-variant); font-size: 14px;">
        Tahun Ajaran Aktif: <strong>{{ $activeYear ? $activeYear->year_label . ' (' . ucfirst($activeYear->semester) . ')' : 'Tidak ada' }}</strong>
    </p>
</div>

<!-- Stats Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid var(--primary);">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Materi Saya</h4>
            <div style="font-size: 36px; font-weight: 700; color: var(--primary); margin: 0.5rem 0;">{{ $stats['materials_count'] }}</div>
        </div>
        <a href="{{ route('teacher.materials.index') }}" style="color: var(--primary); font-size: 13px; font-weight: 500; text-decoration: none;">Kelola Materi &rarr;</a>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid var(--secondary);">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Tugas Dibuat</h4>
            <div style="font-size: 36px; font-weight: 700; color: var(--secondary); margin: 0.5rem 0;">{{ $stats['assignments_count'] }}</div>
        </div>
        <a href="{{ route('teacher.assignments.index') }}" style="color: var(--secondary); font-size: 13px; font-weight: 500; text-decoration: none;">Kelola Tugas &rarr;</a>
    </div>

    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-left: 4px solid #f59e0b;">
        <div>
            <h4 style="color: var(--on-surface-variant); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Submisi Belum Dinilai</h4>
            <div style="font-size: 36px; font-weight: 700; color: #d97706; margin: 0.5rem 0;">{{ $stats['pending_submissions'] }}</div>
        </div>
        <a href="{{ route('teacher.assignments.index') }}" style="color: #d97706; font-size: 13px; font-weight: 500; text-decoration: none;">Periksa Pengumpulan &rarr;</a>
    </div>
</div>

@if($homeroomClassroom)
    <div class="card" style="background-color: #eff6ff; border-color: #bfdbfe; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1.5rem;">
        <div style="background-color: #dbeafe; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 24px; font-weight: 700;">
            W
        </div>
        <div>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--primary);">Anda adalah Wali Kelas: {{ $homeroomClassroom->name }}</h3>
            <p style="font-size: 14px; color: var(--on-surface-variant); margin-top: 0.25rem;">
                Tanggung jawab wali kelas meliputi memantau kehadiran dan mengesahkan rapor siswa di kelas ini.
            </p>
        </div>
    </div>
@endif

<!-- Schedule Card -->
<div class="card">
    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem;">
        Jadwal Mengajar Saya
    </h3>
    
    @if($schedules->isEmpty())
        <div style="padding: 2rem; text-align: center; color: var(--on-surface-variant);">
            <p>Tidak ada jadwal mengajar untuk tahun ajaran aktif ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Pelajaran</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td style="font-weight: 600; text-transform: capitalize;">{{ $schedule->day_of_week }}</td>
                            <td style="font-family: monospace;">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</td>
                            <td style="font-weight: 500; color: var(--primary);">{{ $schedule->subject->subject_name }}</td>
                            <td><span class="badge badge-primary">{{ $schedule->classroom->name }}</span></td>
                            <td>{{ $schedule->room_number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
