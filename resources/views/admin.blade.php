<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard d'administration</title>
    @vite('resources/css/app.css')
    <style>
        /* Supprimer tout le CSS custom ici pour ne garder que Tailwind */
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <div class="sidebar w-64 bg-blue-600 text-white h-screen fixed top-0 left-0 p-2">
            <h2 class="text-center text-2xl font-bold mb-8">{{ auth()->user()->name }}</h2>
            <ul class="list-none p-0">
                @if(auth()->user()->role === 'admin')
                    @include('partials.sidebar')
                @else
                    <li class="mb-4 @if(request()->routeIs('user.dashboard')) bg-gray-700 @endif">
                        <a class="text-white no-underline py-2 px-4 block" href="{{ route('user.dashboard') }}">Accueil</a>
                    </li>
                    <li class="mb-4 @if(request()->routeIs('payments.*')) bg-gray-700 @endif">
                        <a class="text-white no-underline py-2 px-4 block" href="{{ route('payments.index') }}">Mes paiements</a>
                    </li>
                @endif
                <li>
                    <a class="text-white no-underline py-2 px-4 block" href="{{ route('logout') }}">Déconnexion</a>
                </li>
            </ul>
        </div>
        <div class="main-content flex-1 ml-64 p-6">
            @yield('content')
        </div>
    </div>
</body>
</html>
