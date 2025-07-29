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
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition">
                        <td class="px-6 py-4">
                            @if($payment->student->avatar)
                                <img src="{{ asset('storage/avatars/' . $payment->student->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover border-2 border-blue-400">
                            @else
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold text-lg">
                                    {{ strtoupper(substr($payment->student->nom,0,1)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $payment->student->nom }} {{ $payment->student->prenom }}</td>
                        <td class="px-6 py-4">
                            @php
                                $montant = $payment->montant;
                                $devise = $payment->devise ?? '$';
                            @endphp
                            <span class="font-semibold text-blue-700">{{ number_format($montant, 0, ',', ' ') }} {{ $devise }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $payment->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($payment->statut === 'payé')
                                <span class="inline-block px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">Payé</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-full">En attente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $payment->mois_payes ?? '-' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('payments.show', $payment->id) }}" class="text-blue-600 hover:text-blue-900" title="Voir"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                            <a href="{{ route('payments.edit', $payment->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Modifier"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" /></svg></a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('payments.showReceipt', $payment->id) }}" class="text-green-600 hover:text-green-900" title="Voir le reçu">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17v1a3 3 0 003 3h2a3 3 0 003-3v-1m-6 0V5a2 2 0 012-2h2a2 2 0 012 2v12m-6 0h6" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-500">Aucun paiement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
