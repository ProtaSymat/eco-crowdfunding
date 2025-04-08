<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);
        
        // Déterminer l'ordre de l'image
        $maxOrder = $project->images()->max('order') ?? 0;
        
        // Traiter et enregistrer l'image
        $imagePath = $request->file('image')->store('projects/gallery', 'public');
        
        // Créer l'entrée dans la base de données
        $projectImage = ProjectImage::create([
            'project_id' => $project->id,
            'image_path' => $imagePath,
            'caption' => $validated['caption'] ?? null,
            'order' => $maxOrder + 1,
        ]);
        
        return redirect()->back()->with('success', 'L\'image a été ajoutée au projet.');
    }
    
    public function destroy($id)
    {
        $image = ProjectImage::findOrFail($id);
        
        $this->authorize('update', $image->project);
        
        // Supprimer le fichier
        Storage::disk('public')->delete($image->image_path);
        
        // Supprimer l'entrée
        $image->delete();
        
        return redirect()->back()->with('success', 'L\'image a été supprimée.');
    }
    
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:project_images,id',
            'images.*.order' => 'required|integer|min:1',
        ]);
        
        foreach ($validated['images'] as $imageData) {
            $image = ProjectImage::find($imageData['id']);
            
            if ($image) {
                $this->authorize('update', $image->project);
                $image->order = $imageData['order'];
                $image->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
}