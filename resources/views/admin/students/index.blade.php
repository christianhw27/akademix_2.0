@extends('layouts.dashboard')

@section('title', 'Kelola Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('nav')
    @include('admin.nav', ['active' => 'students'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="flex-between">
        <h2 style="font-size: 20px; font-weight: 600;">Daftar Siswa</h2>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Angkatan</th>
                    <th>Orang Tua / Wali</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">{{ $student->nis }}</td>
                        <td style="font-weight: 500;">{{ $student->user->full_name }}</td>
                        <td>
                            @if($student->gender === 'L')
                                <span class="badge" style="background-color: #dbeafe; color: #1e40af;">Laki-laki</span>
                            @else
                                <span class="badge" style="background-color: #fce7f3; color: #9d174d;">Perempuan</span>
                            @endif
                        </td>
                        <td>{{ $student->cohort->year_name ?? '-' }}</td>
                        <td>
                            @if($student->guardian)
                                <span style="font-weight: 500;">{{ $student->guardian->user->full_name }}</span>
                                <br><small style="color: #64748b;">{{ $student->guardian->phone }}</small>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Belum di-link</span>
                            @endif
                        </td>
                        <td><code>{{ $student->user->username }}</code></td>
                        <td>
                            @if($student->user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Akun user juga akan terhapus.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                            Tidak ada data siswa. Silakan tambahkan siswa baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
