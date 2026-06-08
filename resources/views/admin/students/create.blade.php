@extends('layouts.dashboard')

@section('title', 'Tambah Siswa Baru')
@section('page_title', 'Tambah Siswa Baru')

@section('nav')
    @include('admin.nav', ['active' => 'students'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.students.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Siswa
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

<form action="{{ route('admin.students.store') }}" method="POST">
    @csrf
    
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Akun Login Siswa
        </h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Contoh: siswa.raka" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="siswa@sekolah.sch.id">
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
            Profil & Biodata Siswa
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="nis" class="form-label">NIS (Nomor Induk Siswa) <span style="color: red;">*</span></label>
                <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis') }}" placeholder="8 digit angka NIS" required>
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="Nama Lengkap Siswa" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="gender" class="form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cohort_id" class="form-label">Angkatan <span style="color: red;">*</span></label>
                <select name="cohort_id" id="cohort_id" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Angkatan --</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" {{ old('cohort_id') == $cohort->id ? 'selected' : '' }}>Angkatan {{ $cohort->year_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="guardian_id" class="form-label">Orang Tua / Wali <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Optional)</span></label>
                <select name="guardian_id" id="guardian_id" class="form-select">
                    <option value="">-- Tidak Ada / Pilih Nanti --</option>
                    @foreach($guardians as $guardian)
                        <option value="{{ $guardian->id }}" {{ old('guardian_id') == $guardian->id ? 'selected' : '' }}>{{ $guardian->user->full_name }} ({{ $guardian->phone ?: 'No telp -' }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon Siswa <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Optional)</span></label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Alamat Rumah</label>
            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat lengkap tinggal siswa...">{{ old('address') }}</textarea>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
        <a href="{{ route('admin.students.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
    </div>
</form>
@endsection
