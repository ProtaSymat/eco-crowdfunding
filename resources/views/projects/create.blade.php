<!-- resources/views/projects/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item active" aria-current="page">Créer un projet</li>
        </ol>
    </nav>
    
    <!-- Titre de la Page -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold">Créer un nouveau projet</h1>
    </div>
    
    <!-- Formulaire de création -->
    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Nom du projet -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du projet</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <!-- Description courte -->
                <div class="mb-3">
                    <label for="short_description" class="form-label">Description courte</label>
                    <textarea class="form-control" id="short_description" name="short_description" rows="2" required></textarea>
                </div>
                
                <!-- Description complète -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description complète</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                
                <!-- Catégorie -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option selected>Choisir une catégorie</option>
                        <!-- Les options de catégorie viennent ici -->
                    </select>
                </div>
                
                <!-- Image de couverture -->
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Image de couverture</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
                </div>
                
                <!-- Tags -->
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                    <input type="text" class="form-control" id="tags" name="tags">
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Objectif de financement -->
                <div class="mb-3">
                    <label for="funding_goal" class="form-label">Objectif de financement (€)</label>
                    <input type="number" class="form-control" id="funding_goal" name="funding_goal" required>
                </div>
                
                <!-- Contribution minimale -->
                <div class="mb-3">
                    <label for="min_contribution" class="form-label">Contribution minimale (€)</label>
                    <input type="number" class="form-control" id="min_contribution" name="min_contribution" required>
                </div>
                
                <!-- Dates de début et de fin -->
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