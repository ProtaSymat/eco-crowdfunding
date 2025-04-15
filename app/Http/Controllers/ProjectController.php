<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Contribution;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Project::with(['category', 'user', 'tags']);
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort', 'newest');
        
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            case 'ending_soon':
                $query->where('end_date', '>=', now())
                      ->orderBy('end_date', 'asc');
                break;
            case 'featured':
                $query->where('featured', true)
                      ->orderBy('created_at', 'desc');
                break;
        }
        
        $projects = $query->paginate(12);
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('project.index', compact('projects', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $project = Project::with(['user', 'category', 'tags', 'images', 'updates' => function($query) {
            $query->where('backers_only', false)->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();
        
        return view('project.show', compact('project'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('project.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
{
    $this->authorize('create', Project::class);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'short_description' => 'required|string|max:255',
        'description' => 'required|string',
        'funding_goal' => 'required|numeric|min:1',
        'min_contribution' => 'required|numeric|min:1',
        'duration' => 'required|integer|min:1|max:60',
        'cover_image' => 'required|image|max:2048',
        'video_url' => 'nullable|url',
        'tags' => 'nullable|string',
    ]);
    
    $slug = Str::slug($validated['name']);
    $baseSlug = $slug;
    $counter = 1;
    
    while (Project::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    $startDate = Carbon::now();
    $endDate = Carbon::now()->addDays($validated['duration']);
    
    $coverImagePath = $request->file('cover_image')->store('projects/covers', 'public');
    
    $project = Project::create([
        'user_id' => Auth::id(),
        'category_id' => $validated['category_id'],
        'name' => $validated['name'],
        'slug' => $slug,
        'short_description' => $validated['short_description'],
        'description' => $validated['description'],
        'funding_goal' => $validated['funding_goal'],
        'min_contribution' => $validated['min_contribution'],
        'duration' => $validated['duration'],
        'start_date' => $startDate,
        'end_date' => $endDate,
        'status' => 'pending',
        'cover_image' => $coverImagePath,
        'video_url' => $validated['video_url'] ?? null,
        'featured' => false
    ]);
    
    $tagIds = [];
        
    if (!empty($request->tags)) {
        $tagNames = array_map('trim', explode(',', $request->tags));
        
        foreach ($tagNames as $name) {
            if (!empty($name)) {
                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['slug' => Str::slug($name)]
                );
                $tagIds[] = $tag->id;
            }
        }
        
        $project->tags()->sync($tagIds);
    }
    
    return redirect()->route('project.show', $project->slug)
        ->with('success', 'Votre projet a été créé et est en attente de validation.');
}
    public function edit($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $this->authorize('update', $project);
        
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('project.edit', compact('project', 'categories', 'tags'));
    }

    public function update(Request $request, $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'required|string|max:255',
            'description' => 'required|string',
            'funding_goal' => 'required|numeric|min:1',
            'min_contribution' => 'required|numeric|min:1',
            'cover_image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        if ($project->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $baseSlug = $slug;
            $counter = 1;
            
            while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $project->slug = $slug;
        }
        
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) {
                Storage::disk('public')->delete($project->cover_image);
            }
            
            $coverImagePath = $request->file('cover_image')->store('projects/covers', 'public');
            $project->cover_image = $coverImagePath;
        }
        
        $project->category_id = $validated['category_id'];
        $project->name = $validated['name'];
        $project->short_description = $validated['short_description'];
        $project->description = $validated['description'];
        $project->funding_goal = $validated['funding_goal'];
        $project->min_contribution = $validated['min_contribution'];
        $project->video_url = $validated['video_url'] ?? null;
        $project->save();
        
        if (isset($validated['tags'])) {
            $project->tags()->sync($validated['tags']);
        } else {
            $project->tags()->detach();
        }
        
        return redirect()->route('project.show', $project->slug)->with('success', 'Votre projet a été mis à jour.');
    }

    public function destroy($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $this->authorize('delete', $project);
        
        $project->delete();
        
        return redirect()->route('project.index')->with('success', 'Le projet a été supprimé.');
    }

    public function myProjects()
    {
        $projects = Project::where('user_id', Auth::id())
                        ->with(['category', 'tags'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('project.my_project', compact('projects'));
    }

    public function support($slug)
{
    $project = Project::where('slug', $slug)->firstOrFail();
    return view('project.support', compact('project'));
}

public function processSupport(Request $request, $slug)
{
    $project = Project::where('slug', $slug)->firstOrFail();

    $request->validate([
        'amount' => 'required|numeric|min:' . $project->min_contribution,
        'card_name' => 'required|string|max:255',
        'card_number' => 'required|string|max:19',
        'expiry_date' => 'required|string|max:5',
        'cvv' => 'required|string|max:4',
    ]);
    
    $transactionId = 'TR' . time() . rand(1000, 9999);
    
    $contribution = new Contribution();
    $contribution->user_id = auth()->id();
    $contribution->project_id = $project->id;
    $contribution->amount = $request->amount;
    $contribution->status = 'completed';
    $contribution->transaction_id = $transactionId;
    $contribution->comment = $request->comment;
    $contribution->anonymous = $request->has('anonymous') ? 1 : 0;
    $contribution->payment_method = 'credit_card';
    $contribution->save();
    
    $totalCollected = Contribution::where('project_id', $project->id)
                                 ->where('status', 'completed')
                                 ->sum('amount');
    
    $project->total_collected = $totalCollected;
    
    if ($totalCollected >= $project->funding_goal) {
        $project->status = 'completed';
    }
    
    $project->save();
    
    Donation::create([
        'contribution_id' => $contribution->id
    ]);
    
    return redirect()->route('project.supportSuccess', $project->slug)->with([
        'success' => true,
        'amount' => $request->amount,
        'transaction_id' => $transactionId
    ]);
}

public function supportSuccess($slug)
{
    $project = Project::where('slug', $slug)->firstOrFail();
    return view('project.support-success', compact('project'));
}
public function creatorDashboard()
{
    $user = Auth::user();
    $activeProjects = Project::where('user_id', $user->id)
                            ->where(function($query) {
                                $query->where('status', 'active')
                                      ->orWhere('status', 'pending');
                            })
                            ->with('category')
                            ->get();
    
    $projectsCount = Project::where('user_id', $user->id)->count();
    
    $totalFunds = Project::where('user_id', $user->id)
                        ->sum('total_collected') ?? 0;
    
    $contributorsCount = Contribution::whereIn('project_id', 
                                      Project::where('user_id', $user->id)
                                            ->pluck('id'))
                                    ->distinct('user_id')
                                    ->count('user_id') ?? 0;
    
    $recentActivity = Contribution::whereIn('project_id', 
                                  Project::where('user_id', $user->id)
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
    
    $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
    $now = Carbon::now()->endOfMonth();
    
    $months = [];
    $currentDate = clone $sixMonthsAgo;
    
    while ($currentDate <= $now) {
        $months[] = $currentDate->format('M');
        $currentDate->addMonth();
    }
    
    $contributionsByMonth = Contribution::whereIn('project_id', 
                                        Project::where('user_id', $user->id)->pluck('id'))
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
    
    return view('account.account_creator', compact(
        'activeProjects',
        'projectsCount',
        'totalFunds',
        'contributorsCount',
        'recentActivity',
        'months',
        'contributionData'
    ));
}
}