@extends('admin')

@section('content')
    <div class="container mx-auto px-8 py-8">
        <h1 class="text-2xl font-bold mb-8 text-blue-700">Statistiques globales</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 text-center hover:bg-gray-100 transition-colors">
                <h3 class="text-blue-700 font-semibold mb-2">Nombre d'élèves</h3>
                <span class="text-2xl font-bold text-blue-700">{{ $nbEleves ?? '-' }}</span>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center hover:bg-gray-100 transition-colors">
                <h3 class="text-blue-700 font-semibold mb-2">Nombre de paiements</h3>
                <span class="text-2xl font-bold text-blue-700">{{ $nbPaiements ?? '-' }}</span>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center hover:bg-gray-100 transition-colors">
                <h3 class="text-blue-700 font-semibold mb-2">Montant total encaissé</h3>
                <span class="text-2xl font-bold text-blue-700">{{ number_format($montantTotal ?? 0, 0, ',', ' ') }} $</span>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center hover:bg-gray-100 transition-colors">
                <h3 class="text-blue-700 font-semibold mb-2">Nombre de classes</h3>
                <span class="text-2xl font-bold text-blue-700">{{ $nbClasses ?? '-' }}</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 mb-8 hover:bg-gray-100 transition-colors">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Graphique des paiements par mois</h2>
            <div class="h-64 flex items-center justify-center">
                @if(isset($graphData))
                    <canvas id="paiementsChart"></canvas>
                @else
                    <span class="text-gray-400">[Graphiques à venir]</span>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 mb-6 hover:bg-gray-100 transition-colors">
            <h2 class="text-xl font-semibold mb-4 text-red-600 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Alertes
            </h2>
            <ul class="list-disc pl-6 text-red-500 space-y-1">
                @forelse($alertes as $alerte)
                    <li>{{ $alerte }}</li>
                @empty
                    <li class="text-gray-400">Aucune alerte pour le moment.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-6 mb-8 hover:bg-gray-100 transition-colors">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Récapitulatif des paiements par élève</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="px-3 py-2">Nom</th>
                            <th class="px-3 py-2">Prénom</th>
                            <th class="px-3 py-2">Classe</th>
                            <th class="px-3 py-2">Total à payer</th>
                            <th class="px-3 py-2">Total payé</th>
                            <th class="px-3 py-2">Reste à payer</th>
                            <th class="px-3 py-2">Mois couverts</th>
                            <th class="px-3 py-2">Mois restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recapPaiementEleves as $recap)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $recap['nom'] }}</td>
                            <td class="px-3 py-2">{{ $recap['prenom'] }}</td>
                            <td class="px-3 py-2">{{ $recap['classe'] }}</td>
                            <td class="px-3 py-2">{{ number_format($recap['total_a_payer'], 0, ',', ' ') }} F</td>
                            <td class="px-3 py-2">{{ number_format($recap['total_paye'], 0, ',', ' ') }} F</td>
                            <td class="px-3 py-2">{{ number_format($recap['reste_a_payer'], 0, ',', ' ') }} F</td>
                            <td class="px-3 py-2">{{ $recap['mois_couverts'] }}</td>
                            <td class="px-3 py-2">{{ $recap['mois_restants'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 mb-8 hover:bg-gray-100 transition-colors">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Synthèse des mois payés par élève</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="px-3 py-2">Nom</th>
                            <th class="px-3 py-2">Prénom</th>
                            <th class="px-3 py-2">Classe</th>
                            <th class="px-3 py-2">Mois déjà payés</th>
                            <th class="px-3 py-2">Mois restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recapPaiementEleves as $recap)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $recap['nom'] }}</td>
                            <td class="px-3 py-2">{{ $recap['prenom'] }}</td>
                            <td class="px-3 py-2">{{ $recap['classe'] }}</td>
                            <td class="px-3 py-2">
                                @if(!empty($recap['mois_payes']))
                                    {{ implode(', ', $recap['mois_payes']) }}
                                @else
                                    <span class="text-gray-400">Aucun</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                @if(!empty($recap['mois_restants_list']))
                                    {{ implode(', ', $recap['mois_restants_list']) }}
                                @else
                                    <span class="text-gray-400">Aucun</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(isset($graphData))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paiementsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: @json($graphData),
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, labels: { color: '#2563eb' } },
                },
                scales: {
                    x: { ticks: { color: '#2563eb' }, grid: { color: '#e5e7eb' } },
                    y: { ticks: { color: '#2563eb' }, grid: { color: '#e5e7eb' } }
                }
            }
        });
    </script>
    @endif
@endsection
