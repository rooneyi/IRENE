@extends('admin')

@section('content')
<div class="container max-w-xl mx-auto mt-10">
    <h2 class="text-center text-2xl font-bold text-gray-800 mb-8 tracking-wide">Paramètres du système</h2>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white p-10 rounded-xl shadow-lg mb-8">
        <button type="button" id="toggle-fees-config" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold transition mb-4">Configuration des frais annuels</button>
        <form id="fees-config-form" method="POST" action="{{ route('settings.fees.update') }}" class="mb-7" style="display:none;">
            @csrf
            <div class="mb-4">
                <label class="block text-blue-700 font-semibold mb-2">Montant d'un mois</label>
                <input type="number" id="montant_mensuel" step="0.01" min="0" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-blue-700 font-semibold mb-2">Nombre de mois de répartition</label>
                <input type="number" id="mois_repartition" name="mois_repartition" min="1" max="12" value="{{ old('mois_repartition', $moisRepartition) }}" class="w-full border rounded px-3 py-2" required readonly>
            </div>
            <div class="mb-4">
                <label class="block text-blue-700 font-semibold mb-2">Montant total à payer par élève pour l'année</label>
                <input type="number" id="total_a_payer" name="total_a_payer" step="0.01" min="0" value="{{ old('total_a_payer', $totalAPayer) }}" class="w-full border rounded px-3 py-2" required readonly>
            </div>
            <div class="mb-4">
                <label class="block text-blue-700 font-semibold mb-2">Mois d'études concernés</label>
                <select name="mois_etudes[]" id="mois_etudes" class="w-full border rounded px-3 py-2" multiple required onchange="updateMoisRepartition()">
                    @php
                        $mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                        $moisSelectionnes = old('mois_etudes', isset($moisEtudes) ? $moisEtudes : []);
                    @endphp
                    @foreach($mois as $m)
                        <option value="{{ $m }}" @if(in_array($m, $moisSelectionnes)) selected @endif>{{ $m }}</option>
                    @endforeach
                </select>
                <small class="text-gray-500">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs mois</small>
            </div>
            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Enregistrer</button>
        </form>
        <script>
            document.getElementById('toggle-fees-config').addEventListener('click', function() {
                var form = document.getElementById('fees-config-form');
                form.style.display = (form.style.display === 'none') ? 'block' : 'none';
            });
            // Calcul automatique du montant total
            function updateTotal() {
                var montant = parseFloat(document.getElementById('montant_mensuel').value) || 0;
                var mois = parseInt(document.getElementById('mois_repartition').value) || 0;
                document.getElementById('total_a_payer').value = (montant * mois).toFixed(2);
            }
            document.getElementById('montant_mensuel').addEventListener('input', updateTotal);
            document.getElementById('mois_repartition').addEventListener('input', updateTotal);
            function updateMoisRepartition() {
                var select = document.getElementById('mois_etudes');
                var count = Array.from(select.selectedOptions).length;
                document.getElementById('mois_repartition').value = count;
                updateTotal();
            }
            document.getElementById('mois_etudes').addEventListener('change', updateMoisRepartition);
            // Initialiser au chargement si déjà sélectionné
            window.addEventListener('DOMContentLoaded', updateMoisRepartition);
        </script>
    </div>
    <div class="bg-white p-10 rounded-xl shadow-lg">
        <form method="POST" action="{{ route('settings.backup') }}" class="flex items-center justify-between mb-7">
            @csrf
            <span class="text-base font-medium text-gray-700">Sauvegarde manuelle</span>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold transition">Sauvegarder maintenant</button>
        </form>
        <form method="POST" action="{{ route('settings.printer') }}" class="flex items-center justify-between mb-7">
            @csrf
            <span class="text-base font-medium text-gray-700">Configuration imprimante thermique</span>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-semibold transition">Configurer</button>
        </form>
        <form method="POST" action="{{ route('settings.archive') }}" class="flex items-center justify-between mb-7">
            @csrf
            <span class="text-base font-medium text-gray-700">Archivage automatique</span>
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-md font-semibold transition">Archiver maintenant</button>
        </form>
    </div>
</div>
@endsection
