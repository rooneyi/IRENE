@extends('admin')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <div class="bg-blue-50 rounded-xl shadow-lg p-10">
        <h2 class="text-3xl font-extrabold mb-8 text-blue-700 tracking-tight text-center">Ajouter un élève</h2>
        @if ($errors->any())
            <div class="mb-6">
                <ul class="text-blue-600 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Classe</label>
                <input type="text" name="classe" value="{{ old('classe') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Sexe</label>
                <select name="sexe" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="">-- Sélectionner --</option>
                    <option value="Masculin" @if(old('sexe')=='Masculin') selected @endif>Masculin</option>
                    <option value="Féminin" @if(old('sexe')=='Féminin') selected @endif>Féminin</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Date de naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Section</label>
                <select name="section_id" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="">-- Sélectionner --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @if(old('section_id') == $section->id) selected @endif>
                            {{ $section->nom }} ({{ number_format($section->montant_par_defaut, 0, ',', ' ') }} F - Montant total à payer)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-4 mt-8 justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Enregistrer</button>
                <a href="{{ route('students.index') }}" class="bg-white border border-blue-600 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
