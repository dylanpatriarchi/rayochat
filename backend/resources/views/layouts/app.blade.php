<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RayoChat')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-white: #ffffff;
            --color-black: #0A0A0A;
            --color-orange: #FF6B35;
            --color-orange-dark: #E55A2B;
            --color-gray-50: #F9FAFB;
            --color-gray-100: #F3F4F6;
            --color-gray-200: #E5E7EB;
            --color-gray-300: #D1D5DB;
            --color-gray-600: #4B5563;
            --color-gray-700: #374151;
            --color-gray-900: #111827;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--color-gray-50);
            color: var(--color-black);
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: var(--color-white);
            border-bottom: 1px solid var(--color-gray-200);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-black);
            text-decoration: none;
        }

        .navbar-brand span {
            color: var(--color-orange);
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-link {
            color: var(--color-gray-700);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .navbar-link:hover {
            color: var(--color-orange);
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-user-name {
            font-weight: 500;
            color: var(--color-gray-900);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .container-wide {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Page header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-black);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--color-gray-600);
        }

        /* Cards */
        .card {
            background: var(--color-white);
            border: 1px solid var(--color-gray-200);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-black);
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--color-orange);
            color: var(--color-white);
        }

        .btn-primary:hover {
            background: var(--color-orange-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--color-gray-100);
            color: var(--color-gray-900);
        }

        .btn-secondary:hover {
            background: var(--color-gray-200);
        }

        .btn-ghost {
            background: transparent;
            color: var(--color-gray-700);
            border: 1px solid var(--color-gray-300);
        }

        .btn-ghost:hover {
            border-color: var(--color-gray-400);
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--color-gray-900);
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--color-gray-300);
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--color-orange);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--color-white);
            border: 1px solid var(--color-gray-200);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--color-gray-600);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-black);
        }

        .stat-value.highlight {
            color: var(--color-orange);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: var(--color-gray-50);
            border-bottom: 2px solid var(--color-gray-200);
        }

        .table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--color-gray-900);
            font-size: 0.9rem;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--color-gray-200);
            color: var(--color-gray-700);
        }

        .table tbody tr:hover {
            background: var(--color-gray-50);
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-error {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-gray {
            background: var(--color-gray-200);
            color: var(--color-gray-700);
        }
    </style>
    @stack('styles')
</head>
<body>
    @if(session('user_id'))
    <nav class="navbar">
        <a href="{{ session('user_role') === 'admin' ? route('admin.dashboard') : route('site-owner.dashboard') }}" class="navbar-brand">
            Rayo<span>Chat</span>
        </a>
        <div class="navbar-menu">
            @if(session('user_role') === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('admin.site-owners') }}" class="navbar-link">Site Owners</a>
            @else
                <a href="{{ route('site-owner.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('site-owner.documents') }}" class="navbar-link">Documenti</a>
                <a href="{{ route('site-owner.analytics') }}" class="navbar-link">Analitiche</a>
                <a href="{{ route('site-owner.api-key') }}" class="navbar-link">API Key</a>
            @endif
            <div class="navbar-user">
                <span class="navbar-user-name">{{ session('user_name') }}</span>
                <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="padding: 0.5rem 1rem;">Esci</button>
                </form>
            </div>
        </div>
    </nav>
    @endif

    @if(session('success'))
        <div class="container">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container">
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>
