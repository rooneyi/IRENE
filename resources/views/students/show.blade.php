@extends('admin')

@section('content')
<div class="container mx-auto max-w-3xl py-8">
    <div class="bg-white rounded-xl shadow-lg p-10 mb-8">
        <div class="flex items-center gap-6 mb-6">
            <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-700 font-extrabold text-3xl">
                {{ strtoupper(substr($student->nom,0,1)) }}
            </span>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $student->prenom }} {{ $student->nom }}</h2>
                <div class="text-gray-600 text-sm">Matricule : <span class="font-semibold">{{ $student->matricule ?? '-' }}</span></div>
                <div class="text-gray-600 text-sm">Classe : <span class="font-semibold">{{ $student->classe }}</span></div>
                <div class="text-gray-600 text-sm">Sexe : <span class="font-semibold">{{ $student->sexe }}</span></div>
                <div class="text-gray-600 text-sm">Date de naissance : <span class="font-semibold">{{ $student->date_naissance }}</span></div>
                <div class="text-gray-600 text-sm">Téléphone : <span class="font-semibold">{{ $student->telephone }}</span></div>
                <div class="text-gray-600 text-sm">Adresse : <span class="font-semibold">{{ $student->adresse }}</span></div>
                <div class="text-gray-600 text-sm">Statut : <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">{{ $student->statut ?? 'Actif' }}</span></div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Paiements de l'élève</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Date</th>
                        <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Type de frais</th>
                        <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Montant</th>
                        <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">{{ $paiement->date_paiement }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $paiement->feeType->nom ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">{{ $paiement->montant }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">{{ $paiement->statut }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-700">Aucun paiement trouvé pour cet élève.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-8 flex justify-center">
        <a href="{{ route('students.index') }}" class="bg-gray-200 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Retour à la liste</a>
    </div>
</div>
@endsection

