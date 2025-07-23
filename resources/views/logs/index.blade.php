@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Journal d’activité</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border-b">Date</th>
                    <th class="px-4 py-2 border-b">Utilisateur</th>
                    <th class="px-4 py-2 border-b">Action</th>
                    <th class="px-4 py-2 border-b">Description</th>
                    <th class="px-4 py-2 border-b">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $log->created_at }}</td>
                        <td class="px-4 py-2">{{ $log->user ? $log->user->name : 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $log->action }}</td>
                        <td class="px-4 py-2">{{ $log->description }}</td>
                        <td class="px-4 py-2">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Aucune activité enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
