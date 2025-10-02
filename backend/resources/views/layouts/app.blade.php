<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            min-height: 100vh;
            color: #1a202c;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff6b35;
            text-decoration: none;
        }

        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #ff6b35;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(255, 107, 53, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #4a5568;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .main-content {
            padding: 2rem 0;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            margin: 2rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2d3748;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            color: #22543d;
        }

        .alert-error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #742a2a;
        }

        .text-center {
            text-align: center;
        }

        .text-orange {
            color: #ff6b35;
        }

        .text-gray {
            color: #4a5568;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .flex {
            display: flex;
        }

        .justify-center {
            justify-content: center;
        }

        .items-center {
            align-items: center;
        }

        .gap-4 {
            gap: 1rem;
        }

        .w-full {
            width: 100%;
        }

        .max-w-md {
            max-width: 28rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .right-3 {
            right: 0.75rem;
        }

        .top-1\/2 {
            top: 50%;
        }

        .transform {
            transform: translateY(-50%);
        }

        .text-orange-500 {
            color: #ff6b35;
        }

        .text-orange-600 {
            color: #e55a2b;
        }

        .hover\:text-orange-600:hover {
            color: #e55a2b;
        }

        .transition-colors {
            transition: color 0.2s ease;
        }

        .w-5 {
            width: 1.25rem;
        }

        .h-5 {
            height: 1.25rem;
        }

        .pr-12 {
            padding-right: 3rem;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-gray-900 {
            color: #111827;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .bg-green-100 {
            background-color: #dcfce7;
        }

        .border-green-400 {
            border-color: #4ade80;
        }

        .text-green-700 {
            color: #15803d;
        }

        .border {
            border-width: 1px;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .p-3 {
            padding: 0.75rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                gap: 1rem;
            }

            .card {
                margin: 1rem 0;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('dashboard') }}" class="logo">RayoChat</a>
                <nav class="nav">
                    <a href="#" class="nav-link">Cookie Policy</a>
                    <a href="#" class="nav-link">Privacy Policy</a>
                    <a href="#" class="nav-link">Terms of Service</a>
                    @auth
                        <span class="text-gray">Welcome, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>
</body>
</html>
