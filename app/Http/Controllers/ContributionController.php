<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Donation;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ContributionController extends Controller
{
    public function userContributions()
    {
        $user = Auth::user();
        
        $contributions = Contribution::where('user_id', $user->id)
                                    ->with(['project.category', 'donation'])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);
        
        $projectsSupported = Contribution::where('user_id', $user->id)
                                        ->where('status', 'completed')
                                        ->distinct('project_id')
                                        ->count('project_id');
        
        $totalAmount = Contribution::where('user_id', $user->id)
                                ->where('status', 'completed')
                                ->sum('amount');
        
        $contributionsCount = Contribution::where('user_id', $user->id)
                                        ->where('status', 'completed')
                                        ->count();
        
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $now = Carbon::now()->endOfMonth();
        
        $months = [];
        $currentDate = clone $sixMonthsAgo;
        
        while ($currentDate <= $now) {
            $months[] = $currentDate->format('M');
            $currentDate->addMonth();
        }
        
        $contributionsByMonth = Contribution::where('user_id', $user->id)
                                          ->where('status', 'completed')
                                          ->where('created_at', '>=', $sixMonthsAgo)
                                          ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
                                          ->groupBy('year', 'month')
                                          ->orderBy('year')
                                          ->orderBy('month')
                                          ->get();
        
        $contributionData = [];
        $currentDate = clone $sixMonthsAgo;
        
        while ($currentDate <= $now) {
            $month = (int)$currentDate->format('m');
            $year = (int)$currentDate->format('Y');
            
            $found = false;
            foreach ($contributionsByMonth as $contribution) {
                if ($contribution->month == $month && $contribution->year == $year) {
                    $contributionData[] = $contribution->total;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $contributionData[] = 0;
            }
            
            $currentDate->addMonth();
        }
        
        return view('account.contributions', compact(
            'contributions',
            'projectsSupported',
            'totalAmount',
            'contributionsCount',
            'months',
            'contributionData'
        ));
    }

    public function backerDashboard()
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
        
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $now = Carbon::now()->endOfMonth();
        
        $months = [];
        $currentDate = clone $sixMonthsAgo;
        
        while ($currentDate <= $now) {
            $months[] = $currentDate->format('M');
            $currentDate->addMonth();
        }
        
        $contributionsByMonth = Contribution::where('user_id', $userId)
                                    ->where('status', 'completed')
                                    ->where('created_at', '>=', $sixMonthsAgo)
                                    ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
                                    ->groupBy('year', 'month')
                                    ->orderBy('year')
                                    ->orderBy('month')
                                    ->get();
        
        $contributionData = [];
        $currentDate = clone $sixMonthsAgo;
        
        while ($currentDate <= $now) {
            $month = (int)$currentDate->format('m');
            $year = (int)$currentDate->format('Y');
            
            $found = false;
            foreach ($contributionsByMonth as $contribution) {
                if ($contribution->month == $month && $contribution->year == $year) {
                    $contributionData[] = $contribution->total;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $contributionData[] = 0;
            }
            
            $currentDate->addMonth();
        }
        
        $totalContributed = Contribution::where('user_id', $userId)
                                ->where('status', 'completed')
                                ->sum('amount');
        
        $recentActivity = Contribution::where('user_id', $userId)
                                ->with('project')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($contribution) {
                                    return (object)[
                                        'type' => 'contribution',
                                        'title' => 'Votre contribution',
                                        'description' => 'Vous avez contribué ' . 
                                                      number_format($contribution->amount, 2) . ' € au projet ' .
                                                      ($contribution->project->title ?? 'Supprimé'),
                                        'created_at' => $contribution->created_at
                                    ];
                                });
        
        return view('account.account_backer', compact(
            'projectsSupported',
            'favoritesCount',
            'recentContributions',
            'totalContributed',
            'recentActivity',
            'months',
            'contributionData'
        ));
    }

    //possibilité de dl (a voir)
    public function downloadReceipt($id)
    {
        $contribution = Contribution::findOrFail($id);
        
        if ($contribution->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à accéder à ce reçu.');
        }

        return redirect()->back()->with('info', 'Fonctionnalité en cours de développement.');
    }
}