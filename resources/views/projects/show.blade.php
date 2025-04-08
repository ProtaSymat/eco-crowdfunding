<!-- resources/views/projects/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>
    
    <!-- En-tête du Projet -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">{{ $project->name }}</h1>
            <p class="lead mb-4">{{ $project->short_description }}</p>
            
            <div class="d-flex align-items-center mb-4">
                <img src="{{ $project->user->profile_photo_url ?? 'https://via.placeholder.com/40' }}" 
                     class="rounded-circle me-3" width="40" height="40" 
                     alt="{{ $project->user->name }}">
                <div>
                    <p class="mb-0 fw-bold">{{ $project->user->name }}</p>
                    <p class="text-muted small mb-0">{{ $project->user->projects->count() }} projets · Membre depuis {{ $project->user->created_at->locale('fr')->format('F Y') }}</p>
                </div>
            </div>
            
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="#" class="badge bg-light text-dark text-decoration-none p-2">
                    <i class="fas fa-tag me-1"></i>{{ $project->category->name }}
                </a>
                @foreach($project->tags as $tag)
                    <a href="{{ route('projects.index', ['tag' => $tag->id]) }}" class="badge bg-light text-dark text-decoration-none p-2">
                        <i class="fas fa-hashtag me-1"></i>{{ $tag->name }}
                    </a>
                @endforeach
            </div>
            
            @if(auth()->check() && auth()->id() === $project->user_id)
                <div class="mb-4">
                    <a href="{{ route('projects.edit', $project->slug) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit me-2"></i>Modifier le projet
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProjectModal">
                        <i class="fas fa-trash-alt me-2"></i>Supprimer
                    </button>
                </div>
                
                <!-- Modal de suppression -->
                <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteProjectModalLabel">Confirmer la suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('projects.destroy', $project->slug) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0">État</h5>
                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'pending' ? 'warning' : ($project->status === 'completed' ? 'primary' : 'danger')) }}">
                                {{ $project->status === 'active' ? 'En cours' : ($project->status === 'pending' ? 'En attente' : ($project->status === 'completed' ? 'Financé' : 'Non financé')) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">Début</h5>
                            <p class="text-muted mb-0">{{ $project->start_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $project->progress_percentage }}%" 
                             aria-valuenow="{{ $project->progress_percentage }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ $project->progress_percentage }}%
                        </div>
                    </div>
                    
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($project->funding_goal, 0, ',', ' ') }} €</h5>
                            <small class="text-muted">Objectif</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">0 €</h5> <!-- À remplacer par la somme réelle collectée -->
                            <small class="text-muted">Collectés</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ $project->remaining_days }}</h5>
                            <small class="text-muted">Jours restants</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg">
                            <i class="fas fa-heart me-2"></i>Soutenir ce projet
                        </button>
                        <button class="btn btn-outline-success">
                            <i class="fas fa-share-alt me-2"></i>Partager
                        </button>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Contribution minimale</h6>
                        <p class="fw-bold mb-0">{{ number_format($project->min_contribution, 0, ',', ' ') }} €</p>
                    </div>
                    
                    <div>
                        <h6>Date de fin</h6>
                        <p class="fw-bold mb-0">{{ $project->end_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu Principal -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <!-- Galerie d'images / Vidéo -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    @if($project->video_url)
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="{{ Str::contains($project->video_url, 'youtube') ? str_replace('watch?v=', 'embed/', $project->video_url) : $project->video_url }}" 
                                    title="{{ $project->name }}" 
                                    allowfullscreen></iframe>
                        </div>
                    @endif
                    
                    <div id="projectGallery" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $project->cover_image) }}" 
                                     class="d-block w-100 rounded" 
                                     alt="{{ $project->name }}"
                                     style="max-height: 500px; object-fit: cover;">
                            </div>
                            @foreach($project->images as $image)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="d-block w-100 rounded" 
                                         alt="{{ $image->caption ?? $project->name }}"
                                         style="max-height: 500px; object-fit: cover;">
                                    @if($image->caption)
                                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                                            <p class="mb-0">{{ $image->caption }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        @if(count($project->images) > 0)
                            <button class="carousel-control-prev" type="button" data-bs-target="#projectGallery" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#projectGallery" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                            
                            <div class="d-flex justify-content-center mt-3">
                                <ol class="carousel-indicators position-static m-0">
                                    <li data-bs-target="#projectGallery" data-bs-slide-to="0" class="active bg-success"></li>
                                    @foreach($project->images as $index => $image)
                                        <li data-bs-target="#projectGallery" data-bs-slide-to="{{ $index + 1 }}" class="bg-success"></li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    </div>
                    
                    @if(auth()->check() && auth()->id() === $project->user_id)
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addImageModal">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter une image
                            </button>
                        </div>
                        
                        <!-- Modal d'ajout d'image -->
                        <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addImageModalLabel">Ajouter une image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('project.images.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="caption" class="form-label">Légende (optionnel)</label>
                                                <input type="text" class="form-control" id="caption" name="caption">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success">Ajouter l'image</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Onglets: Description, Mises à jour, FAQ -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="projectTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-tab-pane" type="button" role="tab" aria-controls="description-tab-pane" aria-selected="true">
                                Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="updates-tab" data-bs-toggle="tab" data-bs-target="#updates-tab-pane" type="button" role="tab" aria-controls="updates-tab-pane" aria-selected="false">
                                Mises à jour
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-tab-pane" type="button" role="tab" aria-controls="faq-tab-pane" aria-selected="false">
                                FAQ
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="projectTabsContent">
                        <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                            <!-- Contenu de la description -->
                            <div class="mt-4">
                                {!! $project->description !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="updates-tab-pane" role="tabpanel" aria-labelledby="updates-tab" tabindex="0">
                            <!-- Contenu des mises à jour -->
                            <div class="mt-4">
                                @forelse($project->updates as $update)
                                    <div class="update mb-4">
                                        <h5 class="update-title fw-bold">{{ $update->title }}</h5>
                                        <p class="text-muted small">Publié le {{ $update->created_at->format('d/m/Y') }}</p>
                                        <div class="update-content">
                                            {!! $update->content !!}
                                        </div>
                                    </div>
                                @empty
                                    <p>Aucune mise à jour pour le moment.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="faq-tab-pane" role="tabpanel" aria-labelledby="faq-tab" tabindex="0">
                            <!-- Contenu de la FAQ -->
                            <div class="mt-4">
                                @forelse($project->faqs as $faq)
                                    <div class="faq-item mb-3">
                                        <h5 class="faq-question fw-bold">{{ $faq->question }}</h5>
                                        <p class="faq-answer">{{ $faq->answer }}</p>
                                    </div>
                                @empty
                                    <p>Aucune FAQ disponible pour le moment.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Section latérale pour des informations supplémentaires ou des actions spécifiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Soutiens</h5>
                    <p>Nombre de soutiens : {{ $project->supporters_count }}</p>
                    <p>Total collecté : {{ number_format($project->total_collected, 0, ',', ' ') }} €</p>
                </div>
            </div>
            <!-- Autres sections ou widgets -->
        </div>
    </div>
</div>
@endsection