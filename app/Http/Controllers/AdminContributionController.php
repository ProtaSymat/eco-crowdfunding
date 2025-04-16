<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AdminContributionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $project = $request->input('project');
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');
        
        $query = Contribution::with(['user', 'project'])
                            ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($project) {
            $query->where('project_id', $project);
        }
        
        if ($dateStart) {
            $query->whereDate('created_at', '>=', $dateStart);
        }
        
        if ($dateEnd) {
            $query->whereDate('created_at', '<=', $dateEnd);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('project', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $contributions = $query->paginate(20);
        $projects = Project::select('id', 'name')->get();
        
        return view('account.admin.contributions.index', compact(
            'contributions', 
            'projects', 
            'status', 
            'project', 
            'search', 
            'dateStart',
            'dateEnd'
        ));
    }
    
    public function show($id)
    {
        $contribution = Contribution::with(['user', 'project'])->findOrFail($id);
        
        return view('admin.contributions.show', compact('contribution'));
    }
    
    public function generateReport(Request $request)
    {
        $dateStart = $request->input('date_start') ?? now()->subMonth()->toDateString();
        $dateEnd = $request->input('date_end') ?? now()->toDateString();
        
        $contributions = Contribution::with(['project', 'user'])
                                    ->where('status', 'completed')
                                    ->whereBetween('created_at', [$dateStart.' 00:00:00', $dateEnd.' 23:59:59'])
                                    ->get();
        
        $totalAmount = $contributions->sum('amount');
        $projectCount = $contributions->pluck('project_id')->unique()->count();
        $userCount = $contributions->pluck('user_id')->unique()->count();
        
        $byProject = $contributions->groupBy('project_id')
                                  ->map(function($items, $key) {
                                      $project = $items->first()->project;
                                      return [
                                          'project_name' => $project->name,
                                          'count' => $items->count(),
                                          'total' => $items->sum('amount')
                                      ];
                                  })
                                  ->sortByDesc('total')
                                  ->values();
        
        return view('admin.contributions.report', compact(
            'contributions', 
            'totalAmount', 
            'projectCount', 
            'userCount', 
            'byProject',
            'dateStart',
            'dateEnd'
        ));
    }
}