<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard d'administration</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans min-h-screen">

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-blue-700 text-white h-screen fixed top-0 left-0 p-4 shadow-lg">
            <h2 class="text-xl font-bold mb-8 text-center">{{ auth()->user()->name }}</h2>
            <ul class="space-y-2">
                @if(auth()->user()->role === 'admin')
                    @include('partials.sidebar')
                @endif
                <li>
                    <!-- Bouton déconnexion avec logo SVG bien visible -->
                    <a class="text-white no-underline py-2 px-4 flex items-center gap-3 rounded hover:bg-blue-800 transition" href="{{ route('logout') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                        </svg>
                        <span class="text-base font-semibold">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 p-8">
            <div class="max-w-4xl mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
@stack('scripts')
</body>
</html>
