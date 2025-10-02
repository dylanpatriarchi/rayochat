<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Site Owner') - RayoChat</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange': {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles for better compatibility */
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-item:hover {
            background-color: #fff7ed;
            color: #ea580c;
        }
        .sidebar-item.active {
            background-color: #fed7aa;
            color: #c2410c;
        }
        .btn-primary {
            background-color: #f97316;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .btn-primary:hover {
            background-color: #ea580c;
        }
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        .btn-danger {
            background-color: #ef4444;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        .card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        .stat-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            transition: box-shadow 0.2s ease;
        }
        .stat-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Additional utility classes */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .space-x-4 > * + * { margin-left: 1rem; }
        .space-x-6 > * + * { margin-left: 1.5rem; }
        .space-y-1 > * + * { margin-top: 0.25rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .w-5 { width: 1.25rem; }
        .h-5 { height: 1.25rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-64 { width: 16rem; }
        .h-screen { height: 100vh; }
        .flex-1 { flex: 1 1 0%; }
        .flex-col { flex-direction: column; }
        .overflow-hidden { overflow: hidden; }
        .overflow-x-hidden { overflow-x: hidden; }
        .overflow-y-auto { overflow-y: auto; }
        .bg-white { background-color: white; }
        .bg-gray-50 { background-color: #f9fafb; }
        .bg-orange-100 { background-color: #fed7aa; }
        .bg-orange-500 { background-color: #f97316; }
        .text-white { color: white; }
        .text-gray-500 { color: #6b7280; }
        .text-gray-700 { color: #374151; }
        .text-gray-900 { color: #111827; }
        .text-orange-600 { color: #ea580c; }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .text-xl { font-size: 1.25rem; }
        .font-medium { font-weight: 500; }
        .font-bold { font-weight: 700; }
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .border-r { border-right-width: 1px; }
        .border-b { border-bottom-width: 1px; }
        .border-gray-200 { border-color: #e5e7eb; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
        .pb-4 { padding-bottom: 1rem; }
        .mr-3 { margin-right: 0.75rem; }
        .ml-3 { margin-left: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .container { max-width: 1200px; margin: 0 auto; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .transition-colors { transition: color 0.2s ease; }
        .hover\:text-gray-700:hover { color: #374151; }
        .inline { display: inline; }
        .inline-flex { display: inline-flex; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm border-r border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-gray-900">RayoChat</span>
                </div>
            </div>
            
            <nav class="px-4 pb-4">
                <div class="space-y-1">
                    <a href="{{ route('site-owner.dashboard') }}" class="sidebar-item {{ request()->routeIs('site-owner.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('site-owner.sites.create') }}" class="sidebar-item {{ request()->routeIs('site-owner.sites.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                        </svg>
                        Gestione Siti
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500 mt-1">@yield('page-description', 'Gestisci i tuoi siti')</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->first() }}</p>
                            </div>
                            
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200">
                <div class="container mx-auto px-6 py-4">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-sm text-gray-500 mb-2 md:mb-0">
                            © {{ date('Y') }} RayoChat. Tutti i diritti riservati.
                        </div>
                        <div class="flex space-x-6 text-sm">
                            <a href="#" class="text-gray-500 hover:text-gray-700">Privacy Policy</a>
                            <a href="#" class="text-gray-500 hover:text-gray-700">Termini di Servizio</a>
                            <a href="#" class="text-gray-500 hover:text-gray-700">Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
