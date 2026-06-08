@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran')

@section('nav')
    @include('admin.nav', ['active' => 'schedules'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="flex-between" style="margin-bottom: 0;">
        <h2 style="font-size: 18px; font-weight: 600;">Filter berdasarkan Kelas</h2>
        <a href="{{ route('admin.schedules.create', ['classroom_id' => $selectedClassroomId]) }}" class="btn btn-primary">+ Tambah Jadwal</a>
    </div>

    <form method="GET" action="{{ route('admin.schedules.index') }}" style="margin-top: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: flex-end;">
            <div style="flex: 1; max-width: 500px;">
                <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $cr)
                        <option value="{{ $cr->id }}" {{ $selectedClassroomId == $cr->id ? 'selected' : '' }}>
                            {{ $cr->studyClass->name }} - T.A {{ $cr->academicYear->year_label }} ({{ ucfirst($cr->academicYear->semester) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

@if($selectedClassroomId)
<div class="card">
    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 1.25rem;">Jadwal Pelajaran Kelas</h2>
    
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $lastDay = ''; @endphp
                @forelse($schedules as $schedule)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">
                            @if($schedule->day_of_week !== $lastDay)
                                {{ $schedule->day_of_week }}
                                @php $lastDay = $schedule->day_of_week; @endphp
                            @endif
                        </td>
                        <td><span class="badge badge-primary">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span></td>
                        <td><span class="badge">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span></td>
                        <td style="font-weight: 500;">{{ $schedule->subject->name }} <small style="color: #64748b;">({{ $schedule->subject->code }})</small></td>
                        <td>{{ $schedule->teacher->user->full_name }}</td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                            Belum ada jadwal untuk kelas ini. Silakan tambahkan jadwal baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
