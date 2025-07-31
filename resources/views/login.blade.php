<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Administration</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl flex shadow-2xl rounded-3xl overflow-hidden border border-gray-200">
        <!-- Colonne gauche : Logo -->
        <div class="hidden md:flex flex-col justify-center items-center bg-white w-1/2 p-10">
            <img src="/build/assets/logo.png" alt="Logo" class="w-32 h-32 mb-6 drop-shadow-lg">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-2 tracking-tight">Bienvenue</h2>
            <p class="text-blue-400 text-lg text-center">Connectez-vous à votre espace </p>
        </div>
        <!-- Colonne droite : Formulaire -->
        <div class="flex-1 bg-blue-50 flex flex-col justify-center p-10">
            <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center md:text-left">Connexion</h1>
            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-300 text-red-500 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-blue-700 font-semibold mb-1">Email</label>
                    <input type="email" name="email" required autofocus class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-blue-700 font-semibold mb-1">Mot de passe</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-200 focus:outline-none bg-white">
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
