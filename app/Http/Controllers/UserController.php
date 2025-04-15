<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller 
{
    public function index() 
    {
        $users = User::paginate(10);
        return view('account.admin.users.index', compact('users'));
    }

    public function create() 
    {
        return view('account.admin.users.create');
    }

    public function show(User $user)
    {
        return view('account.admin.users.show', compact('user'));
    }

    public function edit(User $user) 
    {
        return view('account.admin.users.edit', compact('user'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',
            'active' => 'sometimes|boolean',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->active = $request->has('active') ? 1 : 0;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:user,admin',
            'active' => 'sometimes|boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->active = $request->has('active') ? 1 : 0;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function showProfile() 
    {
        $user = Auth::user();
        return view('account.account_show', compact('user'));
    }

    public function editProfile() 
    {
        $user = Auth::user();
        return view('account.account_edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->update($request->only('name', 'email'));
        
        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }

    public function becomeCreator(Request $request)
    {
        $user = Auth::user();
        $user->role = 'creator';
        $user->save();
        
        return redirect()->back()->with('success', 'Vous êtes maintenant un créateur!');
    }
}