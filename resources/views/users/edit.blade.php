@extends('admin')

@section('content')
<div class="container" style="max-width:600px; margin:auto;">
    <h2>Modifier un utilisateur</h2>
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div style="margin-bottom:15px;">
            <label>Nom</label><br>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required />
        </div>
        <div style="margin-bottom:15px;">
            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label><br>
            <input type="password" name="password" />
        </div>
        <div style="margin-bottom:15px;">
            <label>Rôle</label><br>
            <select name="role" required>
                <option value="admin" @if(old('role', $user->role)=='admin') selected @endif>Administrateur</option>
                <option value="user" @if(old('role', $user->role)=='user') selected @endif>Utilisateur</option>
                <option value="saisie" @if(old('role', $user->role)=='saisie') selected @endif>Saisie</option>
                <option value="lecture" @if(old('role', $user->role)=='lecture') selected @endif>Lecture seule</option>
            </select>
        </div>
        <button type="submit" style="background:#007bff; color:#fff; padding:10px 20px; border:none; border-radius:4px;">Enregistrer</button>
        <a href="{{ route('users.index') }}" style="margin-left:20px;">Annuler</a>
    </form>
</div>
@endsection

