@extends('layouts.dashboard')

@section('title', 'Edit Data Guru')
@section('page_title', 'Edit Data Guru')

@section('nav')
    @include('admin.nav', ['active' => 'teachers'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.teachers.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Guru
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Akun Login
        </h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $teacher->user->username) }}" placeholder="guru.username" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Isi hanya jika ingin mengganti password">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $teacher->user->email) }}" placeholder="budi@sekolah.sch.id">
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Akun <span style="color: red;">*</span></label>
                <select name="is_active" id="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $teacher->user->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $teacher->user->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Profil & Biodata Guru
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="nip" class="form-label">NIP (Nomor Induk Pegawai) <span style="color: red;">*</span></label>
                <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip', $teacher->nip) }}" placeholder="18 digit angka NIP" required>
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $teacher->user->full_name) }}" placeholder="Nama beserta gelar" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}" placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Alamat Lengkap</label>
            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat rumah tinggal...">{{ old('address', $teacher->address) }}</textarea>
        </div>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.25rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Mata Pelajaran yang Diajar
        </h3>
        <p style="font-size: 14px; color: var(--on-surface-variant); margin-bottom: 1rem;">
            Pilih mata pelajaran yang dapat diajarkan oleh guru ini:
        </p>

        @php
            $currentSubjects = $teacher->subjects->pluck('id')->toArray();
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 0.5rem;">
            @foreach($subjects as $subject)
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 14px; cursor: pointer; padding: 0.5rem; border: 1px solid var(--outline); border-radius: var(--radius); background-color: var(--background);">
                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $currentSubjects)) ? 'checked' : '' }}>
                    <span>{{ $subject->name }} ({{ $subject->code }})</span>
                </label>
            @endforeach
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.teachers.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
    </div>
</form>
@endsection
