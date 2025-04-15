@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.show', $project->slug) }}" class="text-decoration-none">{{ $project->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifier le projet</li>
        </ol>
    </nav>
    
    <div class="mb-5">
        <h1 class="display-5 fw-bold mb-5">Modifier le projet</h1>
    
    <form action="{{ route('project.update', $project->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du projet</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $project->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="short_description" class="form-label">Description courte</label>
                    <textarea class="form-control" id="short_description" name="short_description" rows="2" required>{{ $project->short_description }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description complète</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ $project->description }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Choisir une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Image de couverture</label>
                    @if($project->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="Image de couverture actuelle" class="img-thumbnail" style="max-height: 200px;">
                            <p class="small text-muted mt-1">Image actuelle</p>
                        </div>
                    @endif
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                    <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                </div>
                
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                    <input type="text" class="form-control" id="tags" name="tags" value="{{ $project->tags }}">
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="funding_goal" class="form-label">Objectif de financement (€)</label>
                    <input type="number" class="form-control" id="funding_goal" name="funding_goal" value="{{ $project->funding_goal }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="min_contribution" class="form-label">Contribution minimale (€)</label>
                    <input type="number" class="form-control" id="min_contribution" name="min_contribution" value="{{ $project->min_contribution }}" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}" required>
                    </div>
                    <div class="col">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="draft" {{ $project->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="pending" {{ $project->status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Mettre à jour le projet</button>
            <a href="{{ route('project.show', $project->slug) }}" class="btn btn-secondary ms-2">Annuler</a>
        </div>
    </form>
    </div>
@endsection