<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, Project $project)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
                         ->where('project_id', $project->id)
                         ->first();
        
        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'project_id' => $project->id
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
