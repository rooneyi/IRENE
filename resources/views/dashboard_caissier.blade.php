@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-extrabold mb-8 text-blue-800 tracking-tight text-center">Tableau de bord du caissier</h2>
    <div class="flex justify-between items-center mb-6">
        <a href="javascript:history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Retour
        </a>
        <a href="{{ route('payments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Aller à la page Paiements</a>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
        <h3 class="text-xl font-bold mb-6 text-blue-700 text-center">Graphique des paiements par classe</h3>
        <div class="flex justify-center">
            <canvas id="paiementsChart" height="120"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
        <h3 class="text-xl font-bold mb-6 text-green-700 text-center">Graphique des paiements par section</h3>
        <div class="flex justify-center">
            <canvas id="sectionsDonut" height="120"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
        <h3 class="text-xl font-bold mb-6 text-blue-700 text-center">Statistiques détaillées</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <h4 class="text-lg font-bold mb-2 text-blue-700">Élèves ayant payé par classe</h4>
                <div class="w-full flex flex-wrap gap-4 justify-center">
                    @foreach($parClasse as $classe => $nb)
                        <div class="bg-blue-100 rounded-lg p-4 w-40 text-center shadow">
                            <div class="text-lg font-semibold text-blue-800">Classe {{ $classe }}</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $nb }}</div>
                            <div class="text-gray-600">paiement(s)</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <h4 class="text-lg font-bold mb-2 text-green-700">Élèves ayant payé par section</h4>
                <div class="w-full flex flex-wrap gap-4 justify-center">
                    @foreach($parSection as $sectionId => $nb)
                        <div class="bg-green-100 rounded-lg p-4 w-40 text-center shadow">
                            <div class="text-lg font-semibold text-green-800">Section {{ $sections[$sectionId] ?? $sectionId }}</div>
                            <div class="text-2xl font-bold text-green-900">{{ $nb }}</div>
                            <div class="text-gray-600">paiement(s)</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique barres paiements par classe
    const ctx = document.getElementById('paiementsChart').getContext('2d');
    const paiementsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($parClasse as $classe => $nb)
                    'Classe {{ $classe }}',
                @endforeach
            ],
            datasets: [{
                label: 'Paiements par classe',
                data: [
                    @foreach($parClasse as $classe => $nb)
                        {{ $nb }},
                    @endforeach
                ],
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique donut paiements par section
    const ctxDonut = document.getElementById('sectionsDonut').getContext('2d');
    const sectionsDonut = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($parSection as $sectionId => $nb)
                    '{{ $sections[$sectionId] ?? $sectionId }}',
                @endforeach
            ],
            datasets: [{
                label: 'Paiements par section',
                data: [
                    @foreach($parSection as $sectionId => $nb)
                        {{ $nb }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(251, 191, 36, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(168, 85, 247, 0.7)',
                    'rgba(236, 72, 153, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(14, 165, 233, 0.7)'
                ],
                borderColor: 'rgba(255,255,255,1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
