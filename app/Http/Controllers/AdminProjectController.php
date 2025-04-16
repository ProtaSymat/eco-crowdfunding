<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $category = $request->input('category');
        $search = $request->input('search');
        
        $query = Project::with(['user', 'category'])
                       ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($category) {
            $query->where('category_id', $category);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $projects = $query->paginate(15);
        $categories = Category::all();
        
        return view('account.admin.projects.index', compact('projects', 'categories', 'status', 'category', 'search'));
    }
    
    public function show($id)
    {
        $project = Project::with(['user', 'category', 'contributions', 'updates', 'images'])
                        ->findOrFail($id);
        
        return view('account.admin.projects.show', compact('project'));
    }
    
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $categories = Category::all();
        
        return view('account.admin.projects.edit', compact('project', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'goal' => 'required|numeric|min:0',
            'end_date' => 'required|date|after:today',
            'description' => 'required|string',
            'status' => 'required|in:pending,active,completed,rejected',
            'cover_image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image && Storage::exists($project->cover_image)) {
                Storage::delete($project->cover_image);
            }
            
            $path = $request->file('cover_image')->store('projects/cover_image', 'public');
            $validated['cover_image'] = 'storage/' . $path;
        }
        
        $project->update($validated);
        
        return redirect()->route('admin.projects.index')
                         ->with('success', 'Le projet a été mis à jour avec succès.');
    }
    
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        
        if ($project->contributions()->count() > 0) {
            return redirect()->route('admin.projects.index')
                           ->with('error', 'Ce projet ne peut pas être supprimé car il contient des contributions.');
        }
        
        foreach ($project->images as $image) {
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
            $image->delete();
        }
        
        if ($project->cover_image && Storage::exists($project->cover_image)) {
            Storage::delete($project->cover_image);
        }
        
        $project->delete();
        
        return redirect()->route('admin.projects.index')
                         ->with('success', 'Le projet a été supprimé avec succès.');
    }
    
    public function changeStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,active,completed,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);
        
        $project->status = $validated['status'];
        
        if ($validated['status'] === 'rejected' && isset($validated['rejection_reason'])) {
            $project->rejection_reason = $validated['rejection_reason'];
        }
        
        $project->save();
        
        return redirect()->route('admin.projects.index')
                         ->with('success', 'Le statut du projet a été mis à jour.');
    }
}