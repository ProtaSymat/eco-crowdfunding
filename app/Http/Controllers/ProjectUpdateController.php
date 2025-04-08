<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Http\Request;

class ProjectUpdateController extends Controller
{
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'backers_only' => 'nullable|boolean',
        ]);
        
        $update = ProjectUpdate::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'backers_only' => $validated['backers_only'] ?? false,
        ]);
        
        return redirect()->back()->with('success', 'La mise à jour du projet a été publiée.');
    }
    
    public function edit($id)
    {
        $update = ProjectUpdate::findOrFail($id);
        
        $this->authorize('update', $update->project);
        
        return view('project_updates.edit', compact('update'));
    }
    
    public function update(Request $request, $id)
    {
        $update = ProjectUpdate::findOrFail($id);
        
        $this->authorize('update', $update->project);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'backers_only' => 'nullable|boolean',
        ]);
        
        $update->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'backers_only' => $validated['backers_only'] ?? $update->backers_only,
        ]);
        
        return redirect()->route('projects.show', $update->project->slug)->with('success', 'La mise à jour du projet a été modifiée.');
    }
    
    public function destroy($id)
    {
        $update = ProjectUpdate::findOrFail($id);
        
        $this->authorize('update', $update->project);
        
        $update->delete();
        
        return redirect()->back()->with('success', 'La mise à jour du projet a été supprimée.');
    }
}