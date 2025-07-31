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
                @include('partials.sidebar')
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
