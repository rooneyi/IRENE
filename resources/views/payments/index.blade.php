@extends('admin')

@section('content')
<div class="container">
    <h2>Liste des paiements</h2>
    <form method="GET" action="{{ route('payments.index') }}" class="mb-4" style="display: flex; gap: 15px; flex-wrap: wrap;">
        <input type="text" name="eleve" placeholder="Nom de l'élève" value="{{ request('eleve') }}" />
        <select name="classe">
            <option value="">Classe</option>
            @foreach($classes as $classe)
                <option value="{{ $classe }}" @if(request('classe') == $classe) selected @endif>{{ $classe }}</option>
            @endforeach
        </select>
        <select name="type_frais">
            <option value="">Type de frais</option>
            @foreach($feeTypes as $type)
                <option value="{{ $type->id }}" @if(request('type_frais') == $type->id) selected @endif>{{ $type->nom }}</option>
            @endforeach
        </select>
        <input type="month" name="mois" value="{{ request('mois') }}" />
        <button type="submit">Rechercher</button>
        <a href="{{ route('payments.create') }}" style="margin-left:auto; background:#007bff; color:#fff; padding:8px 16px; border-radius:4px; text-decoration:none;">+ Nouveau paiement</a>
    </form>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <thead>
            <tr>
                <th>Nom élève</th>
                <th>Classe</th>
                <th>Type de frais</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->student->nom }} {{ $payment->student->prenom }}</td>
                    <td>{{ $payment->student->classe }}</td>
                    <td>{{ $payment->feeType->nom }}</td>
                    <td>{{ number_format($payment->montant, 0, ',', ' ') }} FC</td>
                    <td>{{ $payment->date_paiement }}</td>
                    <td>{{ $payment->statut }}</td>
                    <td>
                        <a href="{{ route('payments.show', $payment) }}">Voir</a> |
                        <a href="{{ route('payments.edit', $payment) }}">Modifier</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Aucun paiement trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px;">
        {{ $payments->withQueryString()->links() }}
    </div>
</div>
@endsection

