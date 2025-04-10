<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{

    public function edit()
    {
        $user = Auth::user();
        return view('account.profile_edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }

    public function show()
    {
        $user = Auth::user();
        return view('account.profile_show', compact('user'));
    }

    public function becomeCreator(Request $request)
    {
        $user = Auth::user();
        $user->role = 'creator';
        $user->save();
    
        return redirect()->back()->with('success', 'Vous êtes maintenant un créateur!');
    }
}
