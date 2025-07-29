@extends('admin')

@push('styles')
<style>
@media print {
    body * { display: none !important; }
    .printable-receipt, .printable-receipt * { display: block !important; visibility: visible !important; }
    .printable-receipt { position: absolute; left: 0; top: 0; width: 100vw; background: #fff; z-index: 9999; box-shadow: none !important; }
}
</style>
@endpush

@section('content')
<div class="container mx-auto max-w-lg py-8 printable-receipt">
    <div class="bg-white rounded-xl shadow-lg p-10">
        <div class="flex flex-col items-center mb-8">
            <img src="/build/assets/logo.png" alt="Logo" class="h-16 mb-2">
            <h1 class="text-xl font-bold text-blue-800">College  Saint Vincent De Paul</h1>
            <span class="text-gray-500 text-sm">Reçu officiel de paiement</span>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-blue-700 text-center">Reçu de paiement</h2>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">N° Reçu :</span>
            <span class="text-blue-700">{{ $payment->numero_recu }}</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Élève :</span>
            <span>{{ $payment->student->nom }} {{ $payment->student->prenom }}</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Classe :</span>
            <span>{{ $payment->student->classe }}</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Montant payé (USD/FC) :</span>
            @php
                $montant = $payment->montant;
                $devise = $payment->devise ?? 'FC';
                $montant_fc = $devise === 'USD' ? $montant * 2800 : $montant;
                $montant_usd = $devise === 'USD' ? $montant : round($montant / 2800, 2);
            @endphp
            <span class="text-green-700 font-bold">{{ number_format($montant_usd, 2, ',', ' ') }} $ / {{ number_format($montant_fc, 0, ',', ' ') }} FC</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Date de paiement :</span>
            <span>{{ $payment->date_paiement }}</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Mois payés :</span>
            <span>
                @if($payment->mois_payes)
                    {{ implode(', ', json_decode($payment->mois_payes, true)) }}
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Statut :</span>
            <span>{{ $payment->statut }}</span>
        </div>
        <div class="mb-4 flex justify-between">
            <span class="font-semibold text-gray-700">Catégorie de frais :</span>
            <span>{{ $payment->feeType->categorie ?? '-' }}</span>
        </div>
        <div class="mt-8 flex justify-center gap-4">
            <button onclick="window.print()" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 font-semibold">Imprimer le reçu</button>
            <a href="{{ route('payments.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 font-semibold">Retour</a>
        </div>
        <div class="mt-12 flex flex-col items-center print:mt-20">
            <img src="/build/assets/logo.png" alt="Cachet de l'institut" class="h-20 opacity-70 mb-2 print:mb-0">
            <span class="text-gray-500 text-xs">Cachet officiel de l'institut</span>
        </div>
    </div>
</div>
@endsection
