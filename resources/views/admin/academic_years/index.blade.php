@extends('layouts.dashboard')

@section('title', 'Tahun Ajaran')
@section('page_title', 'Kelola Tahun Ajaran & Semester')

@section('nav')
    @include('admin.nav', ['active' => 'academic-years'])
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="card">
    <div class="flex-between">
        <h2 style="font-size: 20px; font-weight: 600;">Daftar Tahun Ajaran</h2>
        <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($academicYears as $ay)
                    <tr style="{{ $ay->is_active ? 'background-color: #f0fdf4;' : '' }}">
                        <td style="font-weight: 600; color: var(--primary);">{{ $ay->year_label }}</td>
                        <td><span class="badge badge-primary">{{ ucfirst($ay->semester) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($ay->start_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ay->end_date)->format('d M Y') }}</td>
                        <td>
                            @if($ay->is_active)
                                <span class="badge badge-success" style="font-size: 13px; padding: 0.35rem 1rem;">✓ AKTIF</span>
                            @else
                                <span class="badge">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end; flex-wrap: wrap;">
                                @if(!$ay->is_active)
                                    <form action="{{ route('admin.academic-years.activate', $ay->id) }}" method="POST" onsubmit="return confirm('Aktifkan tahun ajaran ini? Tahun ajaran lain akan otomatis dinonaktifkan.');" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm">Aktifkan</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.academic-years.edit', $ay->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @if(!$ay->is_active)
                                    <form action="{{ route('admin.academic-years.destroy', $ay->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--on-surface-variant); padding: 2rem;">
                            Tidak ada data tahun ajaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
