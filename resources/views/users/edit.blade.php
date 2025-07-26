@extends('admin')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <div class="bg-white rounded-xl shadow-lg p-10">
        <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Modifier un utilisateur</h2>
        @if ($errors->any())
            <div class="mb-6">
                <ul class="text-red-600 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Nom</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900" />
            </div>
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900" />
            </div>
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900" />
            </div>
            <div>
                <label class="block mb-2 text-gray-700 font-semibold">Rôle</label>
                <select name="role" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 bg-gray-50 text-gray-900">
                    <option value="admin" @if(old('role', $user->role)=='admin') selected @endif>Administrateur</option>
                    <option value="user" @if(old('role', $user->role)=='user') selected @endif>Utilisateur</option>
                    <option value="saisie" @if(old('role', $user->role)=='saisie') selected @endif>Saisie</option>
                    <option value="lecture" @if(old('role', $user->role)=='lecture') selected @endif>Lecture seule</option>
                </select>
            </div>
            <div class="flex items-center gap-4 mt-8 justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Enregistrer</button>
                <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-blue-600 hover:text-white text-blue-700 px-8 py-2.5 rounded-lg shadow-lg transition font-semibold">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
