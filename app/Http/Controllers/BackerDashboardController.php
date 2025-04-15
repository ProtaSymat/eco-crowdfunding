<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Contribution;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BackerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $projectsSupported = Contribution::where('user_id', $userId)
                                ->where('status', 'completed')
                                ->distinct('project_id')
                                ->count('project_id');
        
        $favoritesCount = Favorite::where('user_id', $userId)->count();
        
        $recentContributions = Contribution::where('user_id', $userId)
                                ->with(['project' => function($query) {
                                    $query->select('id', 'name', 'slug', 'cover_image');
                                }])
                                ->where('status', 'completed')
                                ->orderBy('created_at', 'desc')
                                ->take(4)
                                ->get();

        
        return view('account.account_backer', compact(
            'projectsSupported',
            'favoritesCount',
            'recentContributions'
        ));
    }
}