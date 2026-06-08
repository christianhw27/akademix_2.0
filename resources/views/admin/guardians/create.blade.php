@extends('layouts.dashboard')

@section('title', 'Tambah Orang Tua Baru')
@section('page_title', 'Tambah Orang Tua Baru')

@section('nav')
    @include('admin.nav', ['active' => 'guardians'])
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.guardians.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
        &larr; Kembali ke Daftar Orang Tua
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

<form action="{{ route('admin.guardians.store') }}" method="POST">
    @csrf
    
    <div class="card">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--outline); padding-bottom: 0.5rem; color: var(--primary);">
            Informasi Akun Login Orang Tua
        </h3>
        
        <p style="font-size: 13px; color: var(--on-surface-variant); margin-bottom: 1rem; line-height: 1.4;">
            <strong>Catatan:</strong> Orang tua dapat login menggunakan username mandiri yang Anda set di bawah ini, ATAU menggunakan <strong>NISN/NIS anak mereka</strong> dengan password akun orang tua ini.
        </p>

        <div class="form-row">
            <div class="form-group">
                <label for="username" class="form-label">Username Mandiri <span style="font-size: 12px; font-weight: normal; color: #64748b;">(Optional)</span></label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="Contoh: ortu.andi">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email" class="form-label">Email Orang Tua</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="ortu@sekolah.sch.id">
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
            Profil & Biodata Orang Tua
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap Orang Tua <span style="color: red;">*</span></label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="Nama Lengkap Ayah / Ibu / Wali" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 081211111111">
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Alamat Rumah</label>
            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat rumah tinggal...">{{ old('address') }}</textarea>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary">Simpan Orang Tua</button>
        <a href="{{ route('admin.guardians.index') }}" class="btn" style="background-color: #e2e8f0; color: var(--on-surface);">Batal</a>
    </div>
</form>
@endsection
