@extends('layouts.dashboard')

@section('title', 'Materi Pembelajaran')
@section('page_title', 'Kelola Materi Pembelajaran')

@section('nav')
    @include('teacher.nav', ['active' => 'materials'])
@endsection

@section('content')
<div class="flex-between">
    <div>
        <p style="color: var(--on-surface-variant); font-size: 14px;">
            Daftar materi pembelajaran yang telah Anda unggah pada semester aktif.
        </p>
    </div>
    <a href="{{ route('teacher.materials.create') }}" class="btn btn-primary">
        + Unggah Materi Baru
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
    @if($materials->isEmpty())
        <div style="padding: 3rem 1.5rem; text-align: center; color: var(--on-surface-variant);">
            <div style="font-size: 48px; margin-bottom: 1rem; color: var(--outline);">📚</div>
            <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Materi</h4>
            <p style="font-size: 14px;">Anda belum mengunggah materi pelajaran apapun untuk tahun ajaran aktif ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Judul Materi</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tanggal Unggah</th>
                        <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);">{{ $material->title }}</div>
                                <small style="color: var(--on-surface-variant);">{{ Str::limit(strip_tags($material->content), 80) }}</small>
                            </td>
                            <td><span class="badge badge-primary">{{ $material->classroom->name }}</span></td>
                            <td style="font-weight: 500;">{{ $material->subject->subject_name }}</td>
                            <td>{{ $material->created_at ? $material->created_at->format('d M Y H:i') : '-' }}</td>
                            <td style="text-align: right; padding-right: 2rem;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('teacher.materials.edit', $material->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
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
