@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Liste des élèves</h2>
    <a href="{{ route('students.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 float-right mb-4">+ Nouvel élève</a>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border-b">Nom</th>
                    <th class="px-4 py-2 border-b">Prénom</th>
                    <th class="px-4 py-2 border-b">Matricule</th>
                    <th class="px-4 py-2 border-b">Classe</th>
                    <th class="px-4 py-2 border-b">Date naissance</th>
                    <th class="px-4 py-2 border-b">Sexe</th>
                    <th class="px-4 py-2 border-b">Tuteur</th>
                    <th class="px-4 py-2 border-b">Téléphone</th>
                    <th class="px-4 py-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $student->nom }}</td>
                        <td class="px-4 py-2">{{ $student->prenom }}</td>
                        <td class="px-4 py-2">{{ $student->matricule }}</td>
                        <td class="px-4 py-2">{{ $student->classe }}</td>
                        <td class="px-4 py-2">{{ $student->date_naissance }}</td>
                        <td class="px-4 py-2">{{ $student->sexe }}</td>
                        <td class="px-4 py-2">{{ $student->tuteur }}</td>
                        <td class="px-4 py-2">{{ $student->telephone_tuteur }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:underline">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4">Aucun élève trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
