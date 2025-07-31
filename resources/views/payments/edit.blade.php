@extends('admin')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <div class="bg-blue-50 rounded-xl shadow-lg p-10">
        <h2 class="text-3xl font-extrabold mb-8 text-blue-700 tracking-tight text-center">Modifier un paiement</h2>
        @if ($errors->any())
            <div class="mb-6">
                <ul class="text-blue-600 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('payments.update', $payment->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PUT')
            <script>
                // Injection des sections et montants pour le JS
                const sections = @json($sections);
            </script>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Élève</label>
                <select id="eleve-select" name="eleve_id" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="">-- Sélectionner un élève --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" data-section="{{ $student->section_id }}" @if($payment->eleve_id == $student->id) selected @endif>{{ $student->nom }} {{ $student->prenom }} ({{ $student->classe }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Montant payé</label>
                <input type="number" name="montant" min="0" value="{{ $payment->montant }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Date de paiement</label>
                <input type="date" name="date_paiement" value="{{ $payment->date_paiement }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Statut</label>
                <select name="statut" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="Payé" @if($payment->statut=='Payé') selected @endif>Payé</option>
                    <option value="En attente" @if($payment->statut=='En attente') selected @endif>En attente</option>
                    <option value="Incomplet" @if($payment->statut=='Incomplet') selected @endif>Incomplet</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Mois concernés</label>
                <input type="text" id="mois_payes" name="mois_payes_affichage" readonly value="{{ $payment->mois_payes ? implode(', ', json_decode($payment->mois_payes, true)) : '' }}" class="w-full border border-blue-200 rounded-lg px-4 py-2 bg-gray-100 text-blue-900" placeholder="Sélectionnez un élève et saisissez le montant pour voir les mois" />
            </div>
            <div>
                <label class="block mb-2 text-blue-700 font-semibold">Devise</label>
                <select name="devise" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                    <option value="FC" @if($payment->devise=='FC') selected @endif>Franc congolais (FC)</option>
                    <option value="USD" @if($payment->devise=='USD') selected @endif>Dollar ($)</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-center gap-4 mt-8 justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Enregistrer</button>
                <a href="{{ route('payments.index') }}" class="bg-gray-200 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Annuler</a>
            </div>
        </form>
    </div>
</div>
<script>
    // Récupération dynamique des mois à payer
    const students = @json($students);
    document.querySelector('select[name="eleve_id"]').addEventListener('change', updateMoisPayes);
    document.querySelector('input[name="montant"]').addEventListener('input', updateMoisPayes);
    document.querySelector('select[name="devise"]').addEventListener('change', updateMoisPayes);
    function updateMoisPayes() {
        const eleveId = document.querySelector('select[name="eleve_id"]').value;
        const montant = parseFloat(document.querySelector('input[name="montant"]').value) || 0;
        const devise = document.querySelector('select[name="devise"]').value;
        if (!eleveId || montant <= 0) {
            document.getElementById('mois_payes').value = '';
            return;
        }
        const eleve = students.find(s => s.id == eleveId);
        if (!eleve) return;
        // On récupère le montant mensuel de la section de l'élève
        const section = sections.find(sec => sec.id === eleve.section_id);
        let montantMensuel = section ? section.montant_par_defaut : 0;
        if (devise === 'USD') {
            montantMensuel = montantMensuel / 2800;
        }
        if (montantMensuel <= 0) {
            document.getElementById('mois_payes').value = '';
            return;
        }
        const moisCouverts = Math.floor(montant / montantMensuel);
        fetch(`/api/eleve/${eleveId}/mois-non-payes`)
            .then(r => r.json())
            .then(moisNonPayes => {
                const moisPayes = moisNonPayes.slice(0, moisCouverts);
                document.getElementById('mois_payes').value = moisPayes.join(', ');
                // On ajoute aussi les valeurs dans un champ caché pour l'envoyer au backend
                let hidden = document.getElementById('mois_payes_hidden');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'mois_payes';
                    hidden.id = 'mois_payes_hidden';
                    document.querySelector('form').appendChild(hidden);
                }
                hidden.value = JSON.stringify(moisPayes);
            });
    }
    // Initialisation du champ caché à l'ouverture
    (function() {
        let moisPayes = @json($payment->mois_payes ? json_decode($payment->mois_payes, true) : []);
        let hidden = document.getElementById('mois_payes_hidden');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'mois_payes';
            hidden.id = 'mois_payes_hidden';
            document.querySelector('form').appendChild(hidden);
        }
        hidden.value = JSON.stringify(moisPayes);
    })();
</script>
@endsection
