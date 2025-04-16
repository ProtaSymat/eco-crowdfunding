@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item active" aria-current="page">Créer un projet</li>
        </ol>
    </nav>
    
    <div class="mb-5">
        <h1 class="display-5 fw-bold mb-5">Créer un nouveau projet</h1>

        <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du projet</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="short_description" class="form-label">Description courte</label>
                    <textarea class="form-control" id="short_description" name="short_description" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description complète</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="" selected>Choisir une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Image de couverture</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="video_url" class="form-label">Vidéo</label>
                    <input type="text" class="form-control" id="video_url" name="video_url">
                </div>
                
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                    <input type="text" class="form-control" id="tags" name="tags">
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="funding_goal" class="form-label">Objectif de financement (€)</label>
                    <input type="number" class="form-control" id="funding_goal" name="funding_goal" required>
                </div>
                
                <div class="mb-3">
                    <label for="min_contribution" class="form-label">Contribution minimale (€)</label>
                    <input type="number" class="form-control" id="min_contribution" name="min_contribution" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="col">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                
            </div>
        </div>
        
        <button type="submit" class="btn btn-success">Créer le projet</button>
    </form>
    </div>
@endsection