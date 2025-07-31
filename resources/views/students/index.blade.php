@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Liste des élèves</h2>
    <div class="flex flex-col md:flex-row justify-end mb-6 gap-4">
        <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white px-5 py-2.5 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-800 transition font-semibold text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Nouvel élève
        </a>
        <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg cursor-pointer font-semibold hover:bg-blue-200 transition">
                Importer
                <input type="file" name="import_file" accept=".csv,.xlsx" class="hidden" onchange="this.form.submit()">
            </label>
        </form>
        <a href="{{ route('students.export') }}" class="bg-blue-100 text-blue-700 px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-200 transition shadow">Exporter</a>
    </div>
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Avatar</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Nom</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Post-Nom</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Prénom</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Classe</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Sexe</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Date de naissance</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Téléphone Tuteur</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold text-lg">
                                {{ strtoupper(substr($student->nom,0,1)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $student->nom }}</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $student->post_nom }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $student->prenom }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $student->classe }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200">{{ $student->sexe == 'M' ? 'Masculin' : ($student->sexe == 'F' ? 'Féminin' : '-') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">{{ $student->date_naissance }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $student->telephone_tuteur }}</td>

                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('students.edit', $student) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition-colors font-semibold text-sm">Modifier</a>
                            <a href="{{ route('students.show', $student) }}" class="bg-green-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-500 transition-colors font-semibold text-sm">Voir</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Supprimer cet élève ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 transition-colors font-semibold text-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-6 text-blue-400">Aucun élève trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
