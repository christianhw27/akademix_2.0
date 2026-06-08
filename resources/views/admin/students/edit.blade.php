@extends('layouts.dashboard')

@section('title', 'Edit Data Siswa')
@section('page_title', 'Edit Data Siswa')

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

<form action="{{ route('admin.students.update', $student->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Akun Login Siswa
        </h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $student->user->username) }}" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Isi hanya jika ingin mengganti password">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $student->user->email) }}" placeholder="siswa@sekolah.sch.id">
            </div>

            <div class="form-group">
                <label for="is_active" class="form-label">Status Akun <span style="color: red;">*</span></label>
                <select name="is_active" id="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $student->user->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $student->user->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
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
                <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $student->nis) }}" required>
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $student->user->full_name) }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="gender" class="form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cohort_id" class="form-label">Angkatan <span style="color: red;">*</span></label>
                <select name="cohort_id" id="cohort_id" class="form-select" required>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" {{ old('cohort_id', $student->cohort_id) == $cohort->id ? 'selected' : '' }}>Angkatan {{ $cohort->year_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="guardian_id" class="form-label">Orang Tua / Wali</label>
                <select name="guardian_id" id="guardian_id" class="form-select">
                    <option value="">-- Tidak Ada / Pilih Nanti --</option>
                    @foreach($guardians as $guardian)
                        <option value="{{ $guardian->id }}" {{ old('guardian_id', $student->guardian_id) == $guardian->id ? 'selected' : '' }}>{{ $guardian->user->full_name }} ({{ $guardian->phone ?: 'No telp -' }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon Siswa</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Alamat Rumah</label>
            <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $student->address) }}</textarea>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.students.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
    </div>
</form>
@endsection
