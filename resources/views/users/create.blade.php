@extends('admin')

@section('content')
<div class="container" style="max-width:600px; margin:auto;">
    <h2>Ajouter un utilisateur</h2>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div style="margin-bottom:15px;">
            <label>Nom</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Mot de passe</label><br>
            <input type="password" name="password" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Rôle</label><br>
            <select name="role" required>
                <option value="">-- Sélectionner --</option>
                <option value="admin" @if(old('role')=='admin') selected @endif>Administrateur</option>
                <option value="user" @if(old('role')=='user') selected @endif>Utilisateur</option>
                <option value="saisie" @if(old('role')=='saisie') selected @endif>Saisie</option>
                <option value="lecture" @if(old('role')=='lecture') selected @endif>Lecture seule</option>
            </select>
        </div>
        <button type="submit" style="background:#007bff; color:#fff; padding:10px 20px; border:none; border-radius:4px;">Enregistrer</button>
        <a href="{{ route('users.index') }}" style="margin-left:20px;">Annuler</a>
    </form>
</div>
@endsection

