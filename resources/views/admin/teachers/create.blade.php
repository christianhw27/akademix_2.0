@extends('layouts.dashboard')

@section('title', 'Tambah Guru Baru')
@section('page_title', 'Tambah Guru Baru')

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

<form action="{{ route('admin.teachers.store') }}" method="POST">
    @csrf
    
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Akun Login
        </h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Contoh: guru.budi" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="budi@sekolah.sch.id">
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Akun <span style="color: red;">*</span></label>
                <select name="is_active" id="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
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
                <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip') }}" placeholder="18 digit angka NIP" required>
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="Contoh: Budi Santoso, S.Pd." required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Alamat Lengkap</label>
            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat rumah tinggal...">{{ old('address') }}</textarea>
        </div>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.25rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Mata Pelajaran yang Diajar
        </h3>
        <p style="font-size: 14px; color: var(--on-surface-variant); margin-bottom: 1rem;">
            Pilih mata pelajaran yang dapat diajarkan oleh guru ini:
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 0.5rem;">
            @foreach($subjects as $subject)
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 14px; cursor: pointer; padding: 0.5rem; border: 1px solid var(--outline); border-radius: var(--radius); background-color: var(--background);">
                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" {{ is_array(old('subjects')) && in_array($subject->id, old('subjects')) ? 'checked' : '' }}>
                    <span>{{ $subject->name }} ({{ $subject->code }})</span>
                </label>
            @endforeach
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">Simpan Data Guru</button>
        <a href="{{ route('admin.teachers.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
    </div>
</form>
@endsection
