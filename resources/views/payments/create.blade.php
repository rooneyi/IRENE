    @extends('admin')

    @section('content')
    <div class="container mx-auto max-w-lg py-8">
        <div class="bg-blue-50 rounded-xl shadow-lg p-10">
            <h2 class="text-3xl font-extrabold mb-8 text-blue-700 tracking-tight text-center">Enregistrer un nouveau paiement</h2>
            @if ($errors->any())
                <div class="mb-6">
                    <ul class="text-blue-600 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Élève</label>
                    <select id="eleve-select" name="eleve_id" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
        <option value="">-- Sélectionner un élève --</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}" data-section="{{ $student->section_id }}">{{ $student->nom }} {{ $student->prenom }} ({{ $student->classe }})</option>
        @endforeach
    </select>


    <script>
    document.getElementById('eleve-select').addEventListener('change', function() {
        var selectedSection = this.options[this.selectedIndex].getAttribute('data-section');
        var feeTypeSelect = document.getElementById('fee-type-select');
        Array.from(feeTypeSelect.options).forEach(function(opt) {
            if (opt.value === "") {
                opt.style.display = '';
                return;
            }
            opt.style.display = (opt.getAttribute('data-section') === selectedSection) ? '' : 'none';
        });
        feeTypeSelect.value = "";
    });
    </script>
                </div>
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Montant payé</label>
                    <input type="number" name="montant" min="0" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
                </div>
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Date de paiement</label>
                    <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900" />
                </div>
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Statut</label>
                    <select name="statut" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                        <option value="Payé">Payé</option>
                        <option value="En attente">En attente</option>
                        <option value="Incomplet">Incomplet</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Mois concernés</label>
                    <input type="text" id="mois_payes" name="mois_payes" readonly class="w-full border border-blue-200 rounded-lg px-4 py-2 bg-gray-100 text-blue-900" placeholder="Sélectionnez un élève et saisissez le montant pour voir les mois" />
                </div>
                <div>
                    <label class="block mb-2 text-blue-700 font-semibold">Devise</label>
                    <select name="devise" required class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white text-blue-900">
                        <option value="FC">Franc congolais (FC)</option>
                        <option value="USD">Dollar ($)</option>
                    </select>
                </div>
                <div class="flex items-center gap-4 mt-8 justify-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Enregistrer</button>
                    <a href="{{ route('payments.index') }}" class="bg-white border border-blue-600 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    <script>
            // Récupération dynamique des mois à payer
            const students = @json($students);
            document.querySelector('select[name="eleve_id"]').addEventListener('change', updateMoisPayes);
            document.querySelector('input[name="montant"]').addEventListener('input', updateMoisPayes);
            document.getElementById('mois_payes').value = '';
            function updateMoisPayes() {
                const eleveId = document.querySelector('select[name="eleve_id"]').value;
                const montant = parseFloat(document.querySelector('input[name="montant"]').value) || 0;
                if (!eleveId || montant <= 0) {
                    document.getElementById('mois_payes').value = '';
                    return;
                }
                const eleve = students.find(s => s.id == eleveId);
                if (!eleve) return;
                const montantMensuel = eleve.mois_repartition > 0 ? eleve.total_a_payer / eleve.mois_repartition : 0;
                if (montantMensuel <= 0) {
                    document.getElementById('mois_payes').value = '';
                    return;
                }
                const moisCouverts = Math.floor(montant / montantMensuel);
                // Générer la liste des mois non encore payés (exemple: ['Janvier', 'Février', ...])
                fetch(`/api/eleve/${eleveId}/mois-non-payes`)
                    .then(r => r.json())
                    .then(moisNonPayes => {
                        const moisARegler = moisNonPayes.slice(0, moisCouverts);
                        document.getElementById('mois_payes').value = moisARegler.join(', ');
                    });
            }
            </script>
    @endsection
