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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="username" class="form-label">Username / NISN</label>
                <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" required autofocus placeholder="Masukkan Username atau NISN">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="Masukkan Password">
            </div>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>
    </div>
</div>
@endsection
