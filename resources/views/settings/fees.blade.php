@extends('admin')

@section('content')
<div class="container mx-auto px-8 py-8">
    <h1 class="text-2xl font-bold mb-8 text-blue-700">Configuration des frais annuels</h1>
    <form method="POST" action="{{ route('settings.fees.update') }}" class="max-w-lg bg-white rounded-lg shadow p-6">
        @csrf
        <div class="mb-4">
            <label class="block text-blue-700 font-semibold mb-2">Frais d'inscription</label>
            <input type="number" name="frais_inscription" step="0.01" min="0" value="{{ old('frais_inscription', $fraisInscription ?? '') }}" class="w-full border rounded px-3 py-2" required>
            <small class="text-gray-500">Ce montant correspond au frais d'inscription unique à l'année.</small>
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-semibold mb-2">Frais scolaire direct annuel</label>
            <input type="number" name="total_a_payer" step="0.01" min="0" value="{{ old('total_a_payer', $totalAPayer) }}" class="w-full border rounded px-3 py-2" required>
            <small class="text-gray-500">Ce montant correspond au frais scolaire annuel hors inscription.</small>
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-semibold mb-2">Nombre de mois de répartition</label>
            <input type="number" name="mois_repartition" min="1" max="12" value="{{ old('mois_repartition', $moisRepartition) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Frais par section</h2>
            @php $hasSection = isset($sections) && count($sections) > 0; @endphp
            @if($hasSection)
                @foreach($sections as $i => $section)
                    <div class="mb-4 border p-4 rounded bg-gray-50">
                        <label class="block text-blue-700 font-semibold mb-2">Nom de la section</label>
                        <select name="sections[{{ $i }}][name]" class="w-full border rounded px-3 py-2 mb-2" required>
    <option value="maternelle" @if(old('sections.'.$i.'.name', $section->nom) == 'maternelle') selected @endif>Maternelle</option>
    <option value="primaire" @if(old('sections.'.$i.'.name', $section->nom) == 'primaire') selected @endif>Primaire</option>
    <option value="secondaire_generale" @if(old('sections.'.$i.'.name', $section->nom) == 'secondaire_generale') selected @endif>Secondaire général</option>
    <option value="technique" @if(old('sections.'.$i.'.name', $section->nom) == 'technique') selected @endif>Technique</option>
</select>

                        <label class="block text-blue-700 font-semibold mb-2">Description</label>
                        <input type="text" name="sections[{{ $i }}][description]" value="{{ old('sections.'.$i.'.description', $section->description) }}" class="w-full border rounded px-3 py-2 mb-2">

                        <label class="block text-blue-700 font-semibold mb-2">Montant du frais</label>
                        <input type="number" name="sections[{{ $i }}][montant]" step="0.01" min="0" value="{{ old('sections.'.$i.'.montant', $section->montant_par_defaut) }}" class="w-full border rounded px-3 py-2" required>
                    </div>
                @endforeach
            @else
                <div class="mb-4 border p-4 rounded bg-gray-50">
                    <label class="block text-blue-700 font-semibold mb-2">Nom de la section</label>
                    <input type="text" name="sections[0][name]" class="w-full border rounded px-3 py-2 mb-2" required>

                    <label class="block text-blue-700 font-semibold mb-2">Description</label>
                    <input type="text" name="sections[0][description]" class="w-full border rounded px-3 py-2 mb-2">

                    <label class="block text-blue-700 font-semibold mb-2">Montant du frais</label>
                    <input type="number" name="sections[0][montant]" step="0.01" min="0" class="w-full border rounded px-3 py-2" required>
                </div>
            @endif
            <div id="new-sections"></div>
            <button type="button" id="add-section" class="mt-2 bg-green-600 text-white px-3 py-1 rounded">Ajouter une section</button>
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Enregistrer</button>
        @if(session('success'))
            <div class="mt-4 text-green-600">{{ session('success') }}</div>
        @endif
    </form>
</div>

<script>
    document.getElementById('add-section').addEventListener('click', function() {
        var index = document.querySelectorAll('[id^="section-"]').length;
        var newSection = document.createElement('div');
        newSection.classList.add('mb-4', 'border', 'p-4', 'rounded', 'bg-gray-50');
        newSection.setAttribute('id', 'section-' + index);

        newSection.innerHTML = `
            <label class="block text-blue-700 font-semibold mb-2">Nom de la section</label>
            <input type="text" name="sections[` + index + `][name]" class="w-full border rounded px-3 py-2 mb-2" required>

            <label class="block text-blue-700 font-semibold mb-2">Description</label>
            <input type="text" name="sections[` + index + `][description]" class="w-full border rounded px-3 py-2 mb-2">

            <label class="block text-blue-700 font-semibold mb-2">Montant du frais</label>
            <input type="number" name="sections[` + index + `][montant]" step="0.01" min="0" class="w-full border rounded px-3 py-2" required>
        `;

        document.getElementById('new-sections').appendChild(newSection);
    });
</script>
@endsection
