<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKADEMIX - @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00236f;
            --primary-container: #1e3a8a;
            --secondary: #0d9488;
            --background: #ffffff;
            --surface: #ffffff;
            --on-surface: #191c1e;
            --on-surface-variant: #444651;
            --outline: #e2e8f0;
            --sidebar-width: 280px;
            --radius: 8px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.01em;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
        }

        .nav-item {
            display: block;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-item:hover, .nav-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            background-color: var(--surface);
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid var(--outline);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: none;
            border: 1px solid var(--outline);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            color: var(--error, #ba1a1a);
        }

        .logout-btn:hover {
            background-color: #ffdad6;
        }

        .content-area {
            padding: 2.5rem;
            max-width: 1440px;
            width: 100%;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--primary);
        }

        /* Cards & Tables */
        .card {
            background-color: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: 0px 1px 3px rgba(0,0,0,0.1), 0px 1px 2px rgba(0,0,0,0.06);
            border: 1px solid var(--outline);
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--outline);
        }

        th {
            font-weight: 500;
            color: var(--on-surface-variant);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            background-color: #F1F5F9;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-container);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }

        .btn-secondary:hover {
            filter: brightness(0.9);
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 12px;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius);
            font-size: 12px;
            font-weight: 600;
            background-color: #e2e8f0;
        }
        
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-primary { background-color: #dbeafe; color: #1e40af; }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 0.5rem;
            color: var(--on-surface-variant);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--outline);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 14px;
            color: var(--on-surface);
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--surface);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 35, 111, 0.15);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--outline);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 14px;
            color: var(--on-surface);
            background-color: var(--surface);
            transition: border-color 0.2s;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header" style="padding: 1.5rem 1.5rem;">
            <div style="font-size: 20px; font-weight: 700; letter-spacing: -0.01em;">AKADEMIX</div>
            <div style="font-size: 11px; font-weight: 400; color: rgba(255,255,255,0.6); margin-top: 0.15rem;">Portal Institusi</div>
        </div>
        <nav class="sidebar-nav">
            @yield('nav')
        </nav>
        <div class="sidebar-footer" style="padding: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="nav-item" style="display: flex; align-items: center; gap: 0.75rem; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 13px; font-weight: 600; letter-spacing: 0.05em; padding: 0.75rem 1rem; margin-bottom: 0;">
                <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Keluar
            </a>
        </div>
    </div>

    <div class="main-wrapper">
        <header class="top-header" style="background-color: var(--surface); padding: 1rem 2.5rem; border-bottom: 1px solid var(--outline); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; font-size: 16px; color: var(--on-surface-variant);">@yield('page_title', 'Ringkasan')</div>
            <div class="user-info" style="display: flex; align-items: center; gap: 1.25rem;">
                <!-- Theme Toggle Button -->
                <button type="button" style="background: none; border: none; color: var(--on-surface-variant); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <!-- Notification Bell -->
                <button type="button" style="background: none; border: none; color: var(--on-surface-variant); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0.25rem; position: relative;">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span style="position: absolute; top: 2px; right: 2px; width: 6px; height: 6px; background-color: #ef4444; border-radius: 50%;"></span>
                </button>
                <!-- User Text Info -->
                <div style="text-align: right; line-height: 1.2;">
                    <div style="font-weight: 600; font-size: 14px; color: var(--on-surface);">{{ Auth::user()->full_name }}</div>
                    <div style="font-size: 11px; color: var(--on-surface-variant); font-weight: 500;">
                        @if(Auth::user()->role === 'parent')
                            Orang Tua
                        @elseif(Auth::user()->role === 'student')
                            Siswa
                        @elseif(Auth::user()->role === 'teacher')
                            Guru
                        @else
                            Admin
                        @endif
                    </div>
                </div>
                <!-- User Profile Initials Avatar -->
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #2e446e; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="content-area" style="padding: 2rem 2.5rem; background-color: var(--background); min-height: calc(100vh - 70px);">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
