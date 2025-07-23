@extends('admin')

@section('content')
<div class="container" style="max-width:600px; margin:auto;">
    <h2>Enregistrer un nouveau paiement</h2>
    <form method="POST" action="{{ route('payments.store') }}">
        @csrf
        <div style="margin-bottom:15px;">
            <label>Élève</label><br>
            <select name="eleve_id" required>
                <option value="">-- Sélectionner un élève --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->nom }} {{ $student->prenom }} ({{ $student->classe }})</option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom:15px;">
            <label>Type de frais</label><br>
            <select name="fee_type_id" required>
                <option value="">-- Sélectionner --</option>
                @foreach($feeTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->nom }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom:15px;">
            <label>Montant payé</label><br>
            <input type="number" name="montant" min="0" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Date de paiement</label><br>
            <input type="date" name="date_paiement" value="{{ date('Y-m-d') }}" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Statut</label><br>
            <select name="statut" required>
                <option value="Payé">Payé</option>
                <option value="En attente">En attente</option>
                <option value="Incomplet">Incomplet</option>
            </select>
        </div>
        <div style="margin-bottom:15px;">
            <label>Remarque</label><br>
            <input type="text" name="remarque" />
        </div>
        <button type="submit" style="background:#007bff; color:#fff; padding:10px 20px; border:none; border-radius:4px;">Enregistrer</button>
        <a href="{{ route('payments.index') }}" style="margin-left:20px;">Annuler</a>
    </form>
</div>
@endsection

