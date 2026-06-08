<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKADEMIX - @yield('title', 'Sistem Informasi Sekolah')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00236f;
            --primary-container: #1e3a8a;
            --secondary: #006a61;
            --secondary-container: #86f2e4;
            --background: #f7f9fb;
            --surface: #ffffff;
            --on-surface: #191c1e;
            --on-surface-variant: #444651;
            --outline: #c5c5d3;
            --error: #ba1a1a;
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
        }

        a {
            text-decoration: none;
            color: var(--primary);
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .auth-card {
            background-color: var(--surface);
            width: 100%;
            max-width: 400px;
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--outline);
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand h1 {
            color: var(--primary);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .brand p {
            color: var(--on-surface-variant);
            font-size: 14px;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--on-surface-variant);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--outline);
            border-radius: var(--radius);
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background-color: var(--surface);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 35, 111, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-container);
            transform: translateY(-1px);
        }

        .error-message {
            color: var(--error);
            font-size: 14px;
            margin-bottom: 1.5rem;
            text-align: center;
            background-color: #ffdad6;
            padding: 0.75rem;
            border-radius: var(--radius);
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
</body>
</html>
