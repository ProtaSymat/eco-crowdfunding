<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreatorDashboardController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    
    $activeProjects = Project::where('user_id', $userId)
                            ->where(function($query) {
                                $query->where('status', 'active')
                                      ->orWhere('status', 'pending');
                            })
                            ->with('category')
                            ->get();
    
    $projectsCount = Project::where('user_id', $userId)->count();
    
    $totalFunds = Project::where('user_id', $userId)
                        ->sum('total_collected') ?? 0;
    
    $contributorsCount = Contribution::whereIn('project_id', 
                                        Project::where('user_id', $userId)
                                              ->pluck('id'))
                                    ->distinct('user_id')
                                    ->count('user_id') ?? 0;
    
    $recentActivity = Contribution::whereIn('project_id', 
                                    Project::where('user_id', $userId)
                                          ->pluck('id'))
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($contribution) {
                                    return (object)[
                                        'type' => 'contribution',
                                        'title' => 'Nouvelle contribution',
                                        'description' => 'Contribution de ' . 
                                                        ($contribution->anonymous ? 'Anonyme' : ($contribution->user->name ?? 'Utilisateur')) . 
                                                        ' de ' . number_format($contribution->amount, 2) . ' € au projet ' .
                                                        ($contribution->project->name ?? ''),
                                        'created_at' => $contribution->created_at
                                    ];
                                });
    
    return view('account.account_creator', compact(
        'activeProjects',
        'projectsCount',
        'totalFunds',
        'contributorsCount',
        'recentActivity'
    ));
}
}