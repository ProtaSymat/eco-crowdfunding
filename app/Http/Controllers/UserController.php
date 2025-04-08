<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function becomeCreator(Request $request)
{
    $user = Auth::user();
    $user->role = 'creator';
    $user->save();
    
    return redirect()->back()->with('success', 'Vous êtes maintenant un créateur!');
}
}
