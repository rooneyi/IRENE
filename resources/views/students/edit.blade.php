@extends('admin')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <div class="bg-white rounded-xl shadow-lg p-10">
        <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Modifier un élève</h2>
        @if ($errors->any())
            <div class="mb-6">
                <ul class="text-red-600 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('students.update', $student->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $student->nom) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900" />
            </div>
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $student->prenom) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Section</label>
                <select name="section_id" id="section-select" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="">-- Sélectionner --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" @if(old('section_id', $student->section_id) == $section->id) selected @endif>
                            {{ $section->nom }} : {{ $section->montant_par_defaut }}$
                        </option>
                    @endforeach
                </select>
                
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Classe</label>
                <select name="classe" id="classe-select" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
    <option value="">-- Sélectionner la classe --</option>
</select>
<div id="classe-location" class="mt-2 text-green-700 font-semibold text-sm" style="display:none;"></div>
            </div>
            <div>
                <label class="block mb-2 text-pink-600 font-semibold">Sexe</label>
                <select name="sexe" required class="w-full border border-pink-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-200 bg-pink-50 text-pink-700">
                    <option value="">-- Sélectionner --</option>
                    <option value="Masculin" @if(old('sexe', $student->sexe)=='Masculin') selected @endif>Masculin</option>
                    <option value="Féminin" @if(old('sexe', $student->sexe)=='Féminin') selected @endif>Féminin</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-blue-600 font-semibold">Date de naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance', $student->date_naissance) }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-blue-50 text-blue-700" />
            </div>
            <div>
                <label class="block mb-2 text-green-600 font-semibold">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $student->telephone) }}" required class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200 bg-green-50 text-green-700" />
            </div>
            <div>
                <label class="block mb-2 text-yellow-600 font-semibold">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $student->adresse) }}" required class="w-full border border-yellow-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-200 bg-yellow-50 text-yellow-700" />
            </div>
            <div class="flex items-center gap-4 mt-8 justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Enregistrer</button>
                <a href="{{ route('students.index') }}" class="bg-gray-200 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.SECTION_KEY_MAP = @json($sections->mapWithKeys(fn($s) => [$s->id => $s->description])->toArray());
</script>
<script src="{{ asset('js/eleve_section_classe.js') }}"></script>
<script>
// Préselection dynamique de la classe si déjà existante
// (après que le JS externe ait rempli les options)
document.addEventListener('DOMContentLoaded', function() {
    var sectionSelect = document.getElementById('section-select');
    var classeSelect = document.getElementById('classe-select');
    var selectedSection = sectionSelect.value;
    var selectedClasse = @json(old('classe', $student->classe));
    if (selectedSection) {
        // Déclenche la mise à jour dynamique
        var event = new Event('change');
        sectionSelect.dispatchEvent(event);
        // Attendre que le JS externe ait rempli les options, puis sélectionner la bonne classe
        setTimeout(function() {
            if (selectedClasse) {
                for (let opt of classeSelect.options) {
                    if (opt.value == selectedClasse) {
                        opt.selected = true;
                        break;
                    }
                }
            }
        }, 200);
    }
});
</script>
@endpush

