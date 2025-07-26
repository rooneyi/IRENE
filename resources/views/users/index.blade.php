@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Liste des utilisateurs</h2>
    <div class="flex justify-end mb-6">
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-2.5 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition font-semibold text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nouvel utilisateur
        </a>
    </div>
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Avatar</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Nom</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Email</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Rôle</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold text-lg">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition-colors font-semibold text-sm">Modifier</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 transition-colors font-semibold text-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-gray-700">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
