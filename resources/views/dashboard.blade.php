@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-8">Dashboard d’administration</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-blue-600 font-semibold mb-2">Total paiements</h3>
            <span class="text-2xl font-bold">{{ $totalPaiements }}</span>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-blue-600 font-semibold mb-2">Temps moyen de traitement</h3>
            <span class="text-2xl font-bold">{{ $tempsMoyenTraitement }} min</span>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-blue-600 font-semibold mb-2">Taux d’erreurs</h3>
            <span class="text-2xl font-bold">{{ $tauxErreurs }}%</span>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-blue-600 font-semibold mb-2">Taux de recouvrement</h3>
            <span class="text-2xl font-bold">{{ $tauxRecouvrement }}%</span>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <h3 class="text-blue-600 font-semibold mb-2">Satisfaction parentale</h3>
            <span class="text-2xl font-bold">{{ $satisfactionParentale }}/5</span>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Graphiques mensuels / par classe</h2>
        <div class="h-48 flex items-center justify-center text-gray-400">
            @if(isset($graphData))
                <canvas id="paiementsChart"></canvas>
            @else
                [Graphiques à venir]
            @endif
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-red-600">Alertes</h2>
        <ul class="list-disc pl-6 text-red-500">
            @forelse($alertes as $alerte)
                <li>{{ $alerte }}</li>
            @empty
                <li>Aucune alerte pour le moment.</li>
            @endforelse
        </ul>
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
            plugins: { legend: { display: false } }
        }
    });
</script>
@endif
@endsection
