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
                        <label class="block text-blue-700 font-semibold mb-2">Nombre de mois de répartition</label>
                        <input type="number" id="mois_repartition" name="mois_repartition" min="1" max="12" value="{{ old('mois_repartition', $moisRepartition) }}" class="w-full border rounded px-3 py-2" required readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-blue-700 font-semibold mb-2">Mois d'études concernés</label>
                        <select name="mois_etudes[]" id="mois_etudes" class="w-full border rounded px-3 py-2" multiple required onchange="updateMoisRepartition()">
                            @php
                                $mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                                $moisSelectionnes = old('mois_etudes', isset($moisEtudes) ? (is_array($moisEtudes) ? $moisEtudes : json_decode($moisEtudes, true)) : []);
                            @endphp
                            @foreach($mois as $m)
                                <option value="{{ $m }}" @if(in_array($m, $moisSelectionnes)) selected @endif>{{ $m }}</option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs mois</small>
                    </div>
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4 text-blue-700">Frais par section</h2>
                        @php $hasSection = isset($sections) && count($sections) > 0; @endphp
                        @if($hasSection)
                            @foreach($sections as $i => $section)
                                <div class="mb-4 border p-4 rounded bg-gray-50 section-block" id="section-block-{{ $i }}">
                                    <label class="block text-blue-700 font-semibold mb-2">Nom de la section</label>
                                    <input type="hidden" name="sections[{{ $i }}][id]" value="{{ $section->id }}">
                                    <select name="sections[{{ $i }}][name]" class="w-full border rounded px-3 py-2 mb-2 section-nom" data-index="{{ $i }}" required>
            <option value="maternelle" @if(old('sections.'.$i.'.name', $section->nom) == 'maternelle') selected @endif>Maternelle</option>
            <option value="primaire" @if(old('sections.'.$i.'.name', $section->nom) == 'primaire') selected @endif>Primaire</option>
            <option value="secondaire_generale" @if(old('sections.'.$i.'.name', $section->nom) == 'secondaire_generale') selected @endif>Secondaire général</option>
            <option value="technique" @if(old('sections.'.$i.'.name', $section->nom) == 'technique') selected @endif>Technique</option>
        </select>
                                    <label class="block text-blue-700 font-semibold mb-2">Montant mensuel (USD)</label>
                                    <input type="number" name="sections[{{ $i }}][montant]" step="0.01" min="0" value="{{ old('sections.'.$i.'.montant', $section->montant_par_defaut) }}" class="w-full border rounded px-3 py-2 section-montant" required oninput="updateTotalSection({{ $i }})">

                                    <label class="block text-blue-700 font-semibold mb-2 mt-2">Montant total à payer pour cette section (USD)</label>
                                    <input type="number" id="total_section_{{ $i }}" class="w-full border rounded px-3 py-2 bg-gray-100" value="" readonly>

                                    <button type="button" class="mt-2 bg-red-600 text-white px-3 py-1 rounded remove-section" onclick="removeSection('section-block-{{ $i }}')">Supprimer</button>
                                </div>
                            @endforeach
                        @else
                            <div class="mb-4 border p-4 rounded bg-gray-50 section-block">
                                <label class="block text-blue-700 font-semibold mb-2">Nom de la section</label>
                                <select name="sections[0][name]" class="w-full border rounded px-3 py-2 mb-2 section-nom" data-index="0" required>
            <option value="maternelle">Maternelle</option>
            <option value="primaire">Primaire</option>
            <option value="secondaire_generale">Secondaire général</option>
            <option value="technique">Technique</option>
        </select>

                                <label class="block text-blue-700 font-semibold mb-2">Montant mensuel (USD)</label>
                                <input type="number" name="sections[0][montant]" step="0.01" min="0" class="w-full border rounded px-3 py-2 section-montant" required oninput="updateTotalSection(0)">

                                <label class="block text-blue-700 font-semibold mb-2 mt-2">Montant total à payer pour cette section (USD)</label>
                                <input type="number" id="total_section_0" class="w-full border rounded px-3 py-2 bg-gray-100" value="" readonly>
                            </div>
                        @endif
                        <div id="new-sections"></div>
                        <button type="button" id="add-section" class="mt-2 bg-green-600 text-white px-3 py-1 rounded">Ajouter une section</button>
                    </div>
                    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Enregistrer</button>
                </form>
                <script>
                    document.getElementById('toggle-fees-config').addEventListener('click', function() {
                        var form = document.getElementById('fees-config-form');
                        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
                    });
                    // Calcul automatique du montant total à partir des montants de section
                    function updateTotalSection(index) {
                        var mois = parseInt(document.getElementById('mois_repartition').value) || 0;
                        var montant = 0;
                        var montantInput = document.getElementsByName('sections['+index+'][montant]')[0];
                        if(montantInput) {
                            montant = parseFloat(montantInput.value) || 0;
                        }
                        var total = (montant * mois).toFixed(2);
                        var totalInput = document.getElementById('total_section_' + index);
                        if(totalInput) {
                            totalInput.value = total;
                        }
                        updateTotalGeneral();
                    }
                    function updateTotalGeneral() {
                        var totalGeneral = 0;
                        var mois = parseInt(document.getElementById('mois_repartition').value) || 0;
                        document.querySelectorAll('.section-montant').forEach(function(input, idx) {
                            var montant = parseFloat(input.value) || 0;
                            totalGeneral += montant * mois;
                        });
                        document.getElementById('total_a_payer').value = totalGeneral.toFixed(2);
                    }
                    document.querySelectorAll('.section-montant').forEach(function(input, idx) {
                        input.addEventListener('input', function() { updateTotalSection(idx); });
                    });
                    document.getElementById('mois_repartition').addEventListener('input', function() {
                        document.querySelectorAll('.section-montant').forEach(function(input, idx) {
                            updateTotalSection(idx);
                        });
                    });
                    window.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.section-montant').forEach(function(input, idx) {
                            updateTotalSection(idx);
                        });
                    });
                    function updateMoisRepartition() {
                        var select = document.getElementById('mois_etudes');
                        var count = Array.from(select.selectedOptions).length;
                        document.getElementById('mois_repartition').value = count;
                        updateTotalSection();
                    }
                    document.getElementById('mois_etudes').addEventListener('change', updateMoisRepartition);
                    window.addEventListener('DOMContentLoaded', function() {
                        updateMoisRepartition();
                        updateTotalSection();
                    });
                    document.getElementById('add-section').addEventListener('click', function() {
                        // Correction : compter toutes les sections existantes et nouvelles pour l'index
                        var index = document.querySelectorAll('.section-block').length + document.querySelectorAll('#new-sections .section-block').length;
                        var newSection = document.createElement('div');
                        newSection.classList.add('mb-4', 'border', 'p-4', 'rounded', 'bg-gray-50', 'section-block');
                        newSection.innerHTML = `
                            <label class=\"block text-blue-700 font-semibold mb-2\">Nom de la section</label>
                            <select name=\"sections[${index}][name]\" class=\"w-full border rounded px-3 py-2 mb-2 section-nom\" data-index=\"${index}\" required>\n    <option value=\"maternelle\">Maternelle</option>\n    <option value=\"primaire\">Primaire</option>\n    <option value=\"secondaire_generale\">Secondaire général</option>\n    <option value=\"technique\">Technique</option>\n</select>
                            <label class=\"block text-blue-700 font-semibold mb-2\">Montant mensuel (USD)</label>
                            <input type=\"number\" name=\"sections[${index}][montant]\" step=\"0.01\" min=\"0\" class=\"w-full border rounded px-3 py-2 section-montant\" required oninput=\"updateTotalSection(${index})\">
                            <label class=\"block text-blue-700 font-semibold mb-2 mt-2\">Montant total à payer pour cette section (USD)</label>
                            <input type=\"number\" id=\"total_section_${index}\" class=\"w-full border rounded px-3 py-2 bg-gray-100\" value=\"\" readonly>
                            <button type=\"button\" class=\"mt-2 bg-red-600 text-white px-3 py-1 rounded remove-section\" onclick=\"removeSection('new-section-block-${index}')\">Supprimer</button>
                        `;
                        newSection.setAttribute('id', 'new-section-block-' + index);
                        document.getElementById('new-sections').appendChild(newSection);
                        // Correction : ajouter l'écouteur sur le nouveau champ
                        newSection.querySelector('.section-montant').addEventListener('input', function() { updateTotalSection(index); });
                        // Ajout : auto-remplir montant selon section choisie
                        newSection.querySelector('.section-nom').addEventListener('change', function(e) {
                            var val = e.target.value;
                            var montant = 0;
                            if(val === 'maternelle') montant = 40;
                            else if(val === 'primaire') montant = 45;
                            else if(val === 'secondaire_generale') montant = 65;
                            else if(val === 'technique') montant = 95;
                            newSection.querySelector('.section-montant').value = montant;
                            updateTotalSection(index);
                        });
                    });

                    // Ajout : auto-remplir montant selon section choisie pour toutes les sections existantes et par défaut
                    window.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.section-nom').forEach(function(select) {
                            select.addEventListener('change', function(e) {
                                var index = select.getAttribute('data-index');
                                var val = select.value;
                                var montant = 0;
                                if(val === 'maternelle') montant = 40;
                                else if(val === 'primaire') montant = 45;
                                else if(val === 'secondaire_generale') montant = 65;
                                else if(val === 'technique') montant = 95;
                                var montantInput = document.getElementsByName('sections['+index+'][montant]')[0];
                                if(montantInput) {
                                    montantInput.value = montant;
                                    updateTotalSection(index);
                                }
                            });
                        });
                    });
                    function removeSection(id) {
                        var el = document.getElementById(id);
                        if(el) el.remove();
                        updateTotalGeneral();
                    }
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
