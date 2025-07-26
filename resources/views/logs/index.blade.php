@extends('admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 tracking-tight text-center">Journal d’activité</h2>
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Avatar</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Date</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Utilisateur</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Action</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">Description</th>
                    <th class="px-6 py-3 border-b text-gray-900 font-semibold text-left">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($log->user)
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold text-lg">
                                    {{ strtoupper(substr($log->user->name,0,1)) }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-400 font-bold text-lg">?</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $log->created_at }}</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $log->user ? $log->user->name : 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">{{ $log->action }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $log->description }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-gray-700">Aucune activité enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-8 flex justify-center">
        {{ $logs->links('pagination::tailwind') }}
    </div>
</div>
@endsection
