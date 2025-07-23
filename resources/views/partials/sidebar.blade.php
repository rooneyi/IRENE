<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-white">
        <ul class="space-y-2 font-medium">
            <li class="mb-4 group @if(request()->routeIs('admin.dashboard')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('admin.dashboard')) group-hover:text-indigo-700 @endif" href="{{ route('admin.dashboard') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Tableau de bord
                </a>
            </li>
            <li class="mb-4 group @if(request()->routeIs('payments.*')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('payments.*')) group-hover:text-indigo-700 @endif" href="{{ route('payments.index') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a5 5 0 00-10 0v2M5 20h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Paiements
                </a>
            </li>
            <li class="mb-4 group @if(request()->routeIs('students.*')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('students.*')) group-hover:text-indigo-700 @endif" href="{{ route('students.index') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Élèves
                </a>
            </li>
            <li class="mb-4 group @if(request()->routeIs('users.*')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('users.*')) group-hover:text-indigo-700 @endif" href="{{ route('users.index') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Utilisateurs
                </a>
            </li>
            <li class="mb-4 group @if(request()->routeIs('settings.*')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('settings.*')) group-hover:text-indigo-700 @endif" href="{{ route('settings.index') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/></svg>
                    Paramètres
                </a>
            </li>
            <li class="mb-4 group @if(request()->routeIs('logs.*')) bg-gray-50 @else hover:bg-gray-50 transition-colors @endif">
                <a class="flex items-center no-underline py-2 px-4 block text-blue-700 @if(!request()->routeIs('logs.*')) group-hover:text-indigo-700 @endif" href="{{ route('logs.index') }}">
                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5l2 2h5a2 2 0 012 2v10a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Journal / Logs
                </a>
            </li>
        </ul>
    </div>
</aside>
