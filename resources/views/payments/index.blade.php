@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Liste des paiements</h2>
    <div class="flex justify-end mb-6">
        <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-2.5 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition font-semibold text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nouveau paiement
        </a>
    </div>
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Avatar</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Élève</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Type de frais</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Montant ($/FC)</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Date</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Statut</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Mois payés</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Actions</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Reçu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold text-lg">
                                {{ strtoupper(substr($payment->student->nom,0,1)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $payment->student->nom }} {{ $payment->student->prenom }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $payment->feeType->nom }}</td>
                        <td class="px-6 py-4">
                            @php
                                $montant = $payment->montant;
                                $devise = $payment->devise ?? 'FC';
                                $montant_fc = $devise === 'USD' ? $montant * 2800 : $montant;
                                $montant_usd = $devise === 'USD' ? $montant : round($montant / 2800, 2);
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                {{ number_format($montant_usd, 2, ',', ' ') }} $ / {{ number_format($montant_fc, 0, ',', ' ') }} FC
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">{{ $payment->date_paiement }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">{{ $payment->statut }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(!empty($payment->mois_payes))
                                <span class="inline-block bg-blue-100 text-blue-600 rounded px-2 py-1 text-xs font-bold">{{ is_array($payment->mois_payes) ? implode(', ', $payment->mois_payes) : $payment->mois_payes }}</span>
                            @else
                                <span class="text-gray-300">Aucun</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('payments.show', $payment) }}" class="bg-green-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-500 transition-colors font-semibold text-sm">Voir</a>
                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Supprimer ce paiement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 transition-colors font-semibold text-sm">Supprimer</button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            @if(Route::has('payments.receipt'))
                                <a href="{{ route('payments.receipt', $payment) }}" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold hover:bg-blue-200 transition">Reçu</a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-6 text-blue-400">Aucun paiement trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
