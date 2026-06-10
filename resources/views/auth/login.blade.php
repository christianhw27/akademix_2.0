@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="brand">
            <h1>AKADEMIX</h1>
            <p>Sistem Informasi Akademik</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="auth-tabs" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; background: #f1f5f9; padding: 0.25rem; border-radius: var(--radius); border: 1px solid var(--outline);">
            <button type="button" id="tab-btn-standard" onclick="switchLoginType('standard')" style="flex: 1; padding: 0.5rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); color: var(--primary);">
                Umum
            </button>
            <button type="button" id="tab-btn-parent" onclick="switchLoginType('parent')" style="flex: 1; padding: 0.5rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--on-surface-variant);">
                Orang Tua / Wali
            </button>
        </div>

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf
            <input type="hidden" name="login_type" id="login_type" value="standard">
            
            <!-- Standard Login Section -->
            <div id="section-standard">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" placeholder="Username Siswa/Guru/Admin">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan Password">
                </div>
            </div>

            <!-- Parent Login Section -->
            <div id="section-parent" style="display: none;">
                <div class="form-group">
                    <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" id="nis" name="nis" class="form-input" value="{{ old('nis') }}" placeholder="Contoh: 20240001">
                </div>

                <div class="form-group">
                    <label for="student_username" class="form-label">Username Siswa</label>
                    <input type="text" id="student_username" name="student_username" class="form-input" value="{{ old('student_username') }}" placeholder="Contoh: siswa.raka">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">Masuk</button>
        </form>

        <script>
            function switchLoginType(type) {
                const tabBtnStandard = document.getElementById('tab-btn-standard');
                const tabBtnParent = document.getElementById('tab-btn-parent');
                const sectionStandard = document.getElementById('section-standard');
                const sectionParent = document.getElementById('section-parent');
                const loginTypeInput = document.getElementById('login_type');
                
                const standardInputs = sectionStandard.querySelectorAll('input');
                const parentInputs = sectionParent.querySelectorAll('input');

                if (type === 'parent') {
                    // Update tab buttons
                    tabBtnStandard.style.background = 'transparent';
                    tabBtnStandard.style.boxShadow = 'none';
                    tabBtnStandard.style.color = 'var(--on-surface-variant)';
                    
                    tabBtnParent.style.background = 'white';
                    tabBtnParent.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                    tabBtnParent.style.color = 'var(--primary)';
                    
                    // Show parent section, hide standard
                    sectionStandard.style.display = 'none';
                    sectionParent.style.display = 'block';
                    loginTypeInput.value = 'parent';

                    // Set required attributes dynamically
                    standardInputs.forEach(i => i.removeAttribute('required'));
                    parentInputs.forEach(i => i.setAttribute('required', ''));
                } else {
                    // Update tab buttons
                    tabBtnParent.style.background = 'transparent';
                    tabBtnParent.style.boxShadow = 'none';
                    tabBtnParent.style.color = 'var(--on-surface-variant)';
                    
                    tabBtnStandard.style.background = 'white';
                    tabBtnStandard.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                    tabBtnStandard.style.color = 'var(--primary)';
                    
                    // Show standard section, hide parent
                    sectionParent.style.display = 'none';
                    sectionStandard.style.display = 'block';
                    loginTypeInput.value = 'standard';

                    // Set required attributes dynamically
                    parentInputs.forEach(i => i.removeAttribute('required'));
                    standardInputs.forEach(i => i.setAttribute('required', ''));
                }
            }

            // Restore tab state if there were validation errors on post
            window.addEventListener('DOMContentLoaded', () => {
                const oldType = "{{ old('login_type', 'standard') }}";
                // If there are errors for parent fields or login_type is parent
                if (oldType === 'parent' || "{{ $errors->has('nis') ? '1' : '0' }}" === '1') {
                    switchLoginType('parent');
                } else {
                    switchLoginType('standard');
                }
            });
        </script>
        <p class="mt-5 text-center text-sm color:var(--on-surface-variant);">Belum punya akun? Hubungi administrator sekolah untuk akses.</p>
        <p class="mt-2 text-center text-sm"><a href="{{ url('/') }}" class="text-var(--primary) hover:underline">Kembali ke Beranda</a></p>
    </div>
</div>
@endsection
