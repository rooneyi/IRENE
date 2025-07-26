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
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Enregistrer</button>
        @if(session('success'))
            <div class="mt-4 text-green-600">{{ session('success') }}</div>
        @endif
    </form>
</div>
@endsection
