@extends('layouts.dashboard')

@section('title', 'Absensi Siswa')
@section('page_title', 'Kelola Absensi Siswa')

@section('nav')
    @include('teacher.nav', ['active' => 'attendance'])
@endsection

@section('content')
<div class="flex-between">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Daftar sesi absensi siswa yang Anda buat pada tahun ajaran aktif.
        </p>
    </div>
    <a href="{{ route('teacher.attendance.create') }}" class="btn btn-primary">
        + Catat Absensi Baru
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

<div class="card">
    @if($sessions->isEmpty())
        <div style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
            <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📅</div>
            <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Sesi Absensi</h4>
            <p style="font-size: 14px;">Anda belum melakukan absensi siswa sama sekali pada tahun ajaran aktif ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Absensi</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Catatan Sesi</th>
                        <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">
                                {{ date('d M Y', strtotime($session->attendance_date)) }}
                            </td>
                            <td><span class="badge badge-primary">{{ $session->classroom->name }}</span></td>
                            <td style="font-weight: 500;">{{ $session->subject->subject_name }}</td>
                            <td>{{ $session->notes ?? '-' }}</td>
                            <td style="text-align: right; padding-right: 2rem;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('teacher.attendance.edit', $session->id) }}" class="btn btn-warning btn-sm">Edit Data Absensi</a>
                                    <form action="{{ route('teacher.attendance.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
