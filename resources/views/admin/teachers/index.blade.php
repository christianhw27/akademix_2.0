@extends('layouts.dashboard')

@section('title', 'Kelola Guru')
@section('page_title', 'Kelola Data Guru')

@section('nav')
    @include('admin.nav', ['active' => 'teachers'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="flex-between">
        <h2 style="font-size: 20px; font-weight: 600;">Daftar Guru</h2>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">+ Tambah Guru</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">{{ $teacher->nip }}</td>
                        <td style="font-weight: 500;">{{ $teacher->user->full_name }}</td>
                        <td><code>{{ $teacher->user->username }}</code></td>
                        <td>{{ $teacher->user->email ?: '-' }}</td>
                        <td>{{ $teacher->phone ?: '-' }}</td>
                        <td>
                            @forelse($teacher->subjects as $subject)
                                <span class="badge badge-primary" style="margin-right: 2px;">{{ $subject->name }}</span>
                            @empty
                                <span class="badge" style="color: #64748b;">Belum ada</span>
                            @endforelse
                        </td>
                        <td>
                            @if($teacher->user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini? Akun user juga akan terhapus.');" style="display: inline;">
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
                            Tidak ada data guru. Silakan tambahkan guru baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
