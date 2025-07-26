<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Administration')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="w-full bg-white border-b border-gray-200 shadow-sm py-4 px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/build/assets/logo.png" alt="Logo" class="w-10 h-10">
                <span class="text-xl font-extrabold text-blue-600 tracking-tight">Mon École</span>
            </div>
            <nav class="hidden md:flex gap-6">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-700 font-semibold hover:text-blue-900 transition {{ request()->routeIs('admin.dashboard') ? 'underline underline-offset-4' : '' }}">Dashboard</a>
                <a href="{{ route('users.index') }}" class="text-blue-700 font-semibold hover:text-blue-900 transition {{ request()->routeIs('users.*') ? 'underline underline-offset-4' : '' }}">Utilisateurs</a>
                <a href="{{ route('students.index') }}" class="text-blue-700 font-semibold hover:text-blue-900 transition {{ request()->routeIs('students.*') ? 'underline underline-offset-4' : '' }}">Élèves</a>
                <a href="{{ route('payments.index') }}" class="text-blue-700 font-semibold hover:text-blue-900 transition {{ request()->routeIs('payments.*') ? 'underline underline-offset-4' : '' }}">Paiements</a>
                <a href="{{ route('settings.fees.edit') }}" class="text-blue-700 font-semibold hover:text-blue-900 transition {{ request()->routeIs('settings.*') ? 'underline underline-offset-4' : '' }}">Paramètres</a>
            </nav>
            <div class="flex items-center gap-4">
                @auth
                    <span class="text-blue-700 font-semibold">{{ auth()->user()->name }}</span>
                    <a href="{{ route('logout') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow transition">Déconnexion</a>
                @endauth
            </div>
        </header>
        <!-- Main Content -->
        <main class="flex-1 w-full max-w-7xl mx-auto py-8 px-4 md:px-8">
            @yield('content')
        </main>
        <!-- Footer -->
        <footer class="w-full bg-white border-t border-gray-200 py-4 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} Mon École. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
