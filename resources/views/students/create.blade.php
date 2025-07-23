@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-xl">
    <h2 class="text-2xl font-bold mb-4">Ajouter un élève</h2>
    <form method="POST" action="{{ route('students.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nom</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom') }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Matricule</label>
            <input type="text" name="matricule" value="{{ old('matricule') }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Classe</label>
            <input type="text" name="classe" value="{{ old('classe') }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Date de naissance</label>
            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Sexe</label>
            <select name="sexe" class="w-full border rounded px-3 py-2">
                <option value="">-- Sélectionner --</option>
                <option value="M" @if(old('sexe')=='M') selected @endif>Masculin</option>
                <option value="F" @if(old('sexe')=='F') selected @endif>Féminin</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Tuteur</label>
            <input type="text" name="tuteur" value="{{ old('tuteur') }}" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Téléphone du tuteur</label>
            <input type="text" name="telephone_tuteur" value="{{ old('telephone_tuteur') }}" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            <a href="{{ route('students.index') }}" class="text-gray-600 hover:underline py-2">Annuler</a>
        </div>
    </form>
</div>
@endsection
