@extends('layouts.dashboard')

@section('title', 'Kelola Orang Tua')
@section('page_title', 'Kelola Data Orang Tua')

@section('nav')
    @include('admin.nav', ['active' => 'guardians'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="flex-between">
        <h2 style="font-size: 20px; font-weight: 600;">Daftar Orang Tua / Wali</h2>
        <a href="{{ route('admin.guardians.create') }}" class="btn btn-primary">+ Tambah Orang Tua</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Siswa Terkait (Anak)</th>
                    <th>Status Akun</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guardians as $guardian)
                    <tr>
                        <td style="font-weight: 500; color: var(--primary);">{{ $guardian->user->full_name }}</td>
                        <td><code>{{ $guardian->user->username ?: '(Login via NISN anak)' }}</code></td>
                        <td>{{ $guardian->phone ?: '-' }}</td>
                        <td>{{ $guardian->address ?: '-' }}</td>
                        <td>
                            @forelse($guardian->students as $student)
                                <span class="badge badge-primary" style="margin-bottom: 2px; display: inline-block;">
                                    {{ $student->user->full_name }} (NIS: {{ $student->nis }})
                                </span>
                            @empty
                                <span class="badge" style="color: #64748b;">Belum ada anak terhubung</span>
                            @endforelse
                        </td>
                        <td>
                            @if($guardian->user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.guardians.edit', $guardian->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.guardians.destroy', $guardian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data orang tua ini? Akun user juga akan terhapus.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                            Tidak ada data orang tua. Silakan tambahkan orang tua baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
