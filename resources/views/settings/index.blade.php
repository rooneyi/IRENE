@extends('admin')

@section('content')
<div class="container" style="max-width:700px; margin:auto;">
    <h2>Paramètres du système</h2>
    <div style="background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 8px #ccc;">
        <p><strong>Sauvegarde manuelle :</strong> <button style="background:#007bff; color:#fff; border:none; padding:8px 16px; border-radius:4px;">Sauvegarder maintenant</button></p>
        <p><strong>Configuration imprimante thermique :</strong> <button style="background:#007bff; color:#fff; border:none; padding:8px 16px; border-radius:4px;">Configurer</button></p>
        <p><strong>Synchronisation cloud :</strong> <span style="color:#888;">(à venir)</span></p>
        <p><strong>Archivage automatique :</strong> <span style="color:#888;">(à venir)</span></p>
    </div>
</div>
@endsection

