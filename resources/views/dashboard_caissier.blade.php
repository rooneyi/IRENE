@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-extrabold mb-8 text-blue-800 tracking-tight text-center">Tableau de bord du caissier</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-bold mb-4 text-blue-700">Élèves ayant payé par classe</h3>
            <ul class="list-disc pl-6">
                @forelse($parClasse as $classe => $nb)
                    <li class="mb-2"><span class="font-semibold">Classe {{ $classe }} :</span> {{ $nb }} paiement(s)</li>
                @empty
                    <li>Aucun paiement enregistré.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-xl font-bold mb-4 text-blue-700">Élèves ayant payé par section</h3>
            <ul class="list-disc pl-6">
                @forelse($parSection as $sectionId => $nb)
                    <li class="mb-2"><span class="font-semibold">Section {{ $sections[$sectionId] ?? $sectionId }} :</span> {{ $nb }} paiement(s)</li>
                @empty
                    <li>Aucun paiement enregistré.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

