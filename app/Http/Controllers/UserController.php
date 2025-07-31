<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut accéder à la gestion des utilisateurs.');
        }
        $users = User::orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut ajouter un utilisateur.');
        }
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut ajouter un utilisateur.');
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();
        if (!$authUser || $authUser->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut modifier un utilisateur.');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();
        if (!$authUser || $authUser->role !== 'admin') {
            abort(403, 'Accès refusé. Seul l\'administrateur peut modifier un utilisateur.');
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required',
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        return redirect()->route('users.index')->with('success', 'Utilisateur modifié avec succès.');
    }
}
