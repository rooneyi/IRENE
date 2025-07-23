@extends('base')
@section('title', 'Login')
@section('content')
<!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
{{--        <img src="" alt="Your Company" class="mx-auto h-10 w-auto" />--}}
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Connectez vous Sur Payement Management System</h2>
    </div>
    @if($errors->any())
        <div class="error">{{$errors->first()}}</div>
    @endif
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form action="{{ route('login') }}" method="POST" class="space-y-6 border-1 border-gray-300 bg-white px-6 py-8 shadow-md rounded-lg">
            @csrf
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Nom d'utilisateurs </label>
                <div class="mt-2">
                    <input id="email" type="email" name="email" required autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm/6 font-medium text-gray-900">Mot de passe </label>
                </div>
                <div class="mt-2">
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>

            <div class="gap-2 flex items-center justify-between">
                <button type="submit" name="role" value="user" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Connexion Utilisateur </button>
                <button type="submit" name="role" value="admin" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Connexion Admin</button>

            </div>
        </form>


    </div>
</div>
@endsection


