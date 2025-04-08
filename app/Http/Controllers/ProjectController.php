<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
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
        
        // Filtrage
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
        
        // Tri
        $sortBy = $request->get('sort', 'newest');
        
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                // À implémenter plus tard avec le nombre de contributions
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
        
        return view('projects.index', compact('projects', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $project = Project::with(['user', 'category', 'tags', 'images', 'updates' => function($query) {
            $query->where('backers_only', false)->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();
        
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('projects.create', compact('categories', 'tags'));
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
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        // Générer le slug à partir du nom
        $slug = Str::slug($validated['name']);
        $baseSlug = $slug;
        $counter = 1;
        
        // S'assurer que le slug est unique
        while (Project::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Calcul des dates de début et de fin
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($validated['duration']);
        
        // Traitement de l'image de couverture
        $coverImagePath = $request->file('cover_image')->store('projects/covers', 'public');
        
        // Création du projet
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
            'status' => 'pending', // Par défaut en attente de validation
            'cover_image' => $coverImagePath,
            'video_url' => $validated['video_url'] ?? null,
            'featured' => false, // Par défaut non mis en avant
        ]);
        
        // Associer les tags
        if (isset($validated['tags'])) {
            $project->tags()->sync($validated['tags']);
        }
        
        return redirect()->route('projects.show', $project->slug)->with('success', 'Votre projet a été créé et est en attente de validation.');
    }

    public function edit($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $this->authorize('update', $project);
        
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('projects.edit', compact('project', 'categories', 'tags'));
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
        
        // Ne mettre à jour le slug que si le nom a changé
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
        
        // Traitement de la nouvelle image de couverture si fournie
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image
            if ($project->cover_image) {
                Storage::disk('public')->delete($project->cover_image);
            }
            
            $coverImagePath = $request->file('cover_image')->store('projects/covers', 'public');
            $project->cover_image = $coverImagePath;
        }
        
        // Mise à jour des champs
        $project->category_id = $validated['category_id'];
        $project->name = $validated['name'];
        $project->short_description = $validated['short_description'];
        $project->description = $validated['description'];
        $project->funding_goal = $validated['funding_goal'];
        $project->min_contribution = $validated['min_contribution'];
        $project->video_url = $validated['video_url'] ?? null;
        $project->save();
        
        // Mise à jour des tags
        if (isset($validated['tags'])) {
            $project->tags()->sync($validated['tags']);
        } else {
            $project->tags()->detach();
        }
        
        return redirect()->route('projects.show', $project->slug)->with('success', 'Votre projet a été mis à jour.');
    }

    public function destroy($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        
        $this->authorize('delete', $project);
        
        // Soft delete du projet
        $project->delete();
        
        return redirect()->route('projects.index')->with('success', 'Le projet a été supprimé.');
    }
}