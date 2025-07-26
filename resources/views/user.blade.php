@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-8">Bienvenue sur votre espace utilisateur</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Mes paiements</h2>
        <ul class="list-disc pl-6 text-gray-700">
            <li>Consultez vos paiements récents et leur statut.</li>
            <li>Imprimez vos reçus ou effectuez une nouvelle opération si autorisé.</li>
        </ul>
        <a href="{{ route('payments.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Voir mes paiements</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Informations</h2>
        <ul class="list-disc pl-6 text-gray-700">
            <li>Contactez l’administration pour toute question ou réclamation.</li>
            <li>Vos droits d’accès sont limités selon votre rôle.</li>
        </ul>
    </div>
</div>
@endsection
