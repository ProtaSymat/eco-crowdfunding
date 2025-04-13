
@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <div class="d-flex justify-content-center align-items-center flex-column">
    <div class="d-flex align-items-end">
            <h1 class="display-5 fw-bold mb-1">{{ $project->name }}</h1>
            @if(auth()->check() && auth()->id() === $project->user_id)
                <a href="{{ route('project.edit', $project->slug) }}" class="text-dark ms-2 mb-4">
                    <i data-feather="edit-3" class="feather-sm"></i>
                </a>
            @endif
            </div>
            <p class="lead mb-4">{{ $project->short_description }}</p>
            </div>
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                @if($project->user && $project->user->avatar)
                <img src="{{ asset($project->user->avatar) }}" class="rounded-circle img-thumbnail" width="40" height="40" alt="Avatar">
                @elseif($project->user)
                    <div class="avatar-placeholder rounded-circle me-3 d-flex align-items-center justify-content-center bg-{{ $project->user->role === 'admin' ? 'danger' : ($project->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 40px; height: 40px; font-size: 1rem;">
                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    @if($project->user)
                    <p class="mb-0 fw-bold">{{ $project->user->name }}</p>
                    <p class="text-muted small mb-0">{{ $project->user->projects->count() }} projets · Membre depuis {{ $project->user->created_at->locale('fr')->format('F Y') }}</p>
                    @endif
                </div>
            </div>
            
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="#" class="badge bg-light text-dark text-decoration-none p-2">
                    Catégorie :
                    <i class="fas fa-tag me-1"></i>{{ $project->category->name }}
                </a>
                @foreach($project->tags as $tag)
                    <a href="{{ route('project.index', ['tag' => $tag->id]) }}" class="badge bg-light text-dark text-decoration-none p-2">
                        <i class="fas fa-hashtag me-1"></i>{{ $tag->name }}
                    </a>
                @endforeach
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
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
                            <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments-tab-pane" type="button" role="tab" aria-controls="comments-tab-pane" aria-selected="false">
                                Commentaires <span class="badge bg-secondary">{{ $project->comments()->where('is_hidden', false)->where('parent_id', null)->count() }}</span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="projectTabsContent">
                        <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                            <div class="mt-4">
                            @if($project->video_url)
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="{{ Str::contains($project->video_url, 'youtube') ? str_replace('watch?v=', 'embed/', $project->video_url) : $project->video_url }}" 
                                    title="{{ $project->name }}" 
                                    allowfullscreen></iframe>
                        </div>
                    @endif
                                {!! $project->description !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="updates-tab-pane" role="tabpanel" aria-labelledby="updates-tab" tabindex="0">
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
                        <div class="tab-pane fade" id="comments-tab-pane" role="tabpanel" aria-labelledby="comments-tab" tabindex="0">
                            <div class="mt-4">
                                <h4 class="mb-4">Commentaires ({{ $project->comments->where('is_hidden', false)->where('parent_id', null)->count() }})</h4>
                                @auth
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <form action="{{ route('comments.store') }}" method="POST" id="comment-form">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                                            <input type="hidden" name="parent_id" id="parent_id" value="">
                                            
                                            <div class="mb-3">
                                                <label for="comment-content" class="form-label">Votre commentaire</label>
                                                <textarea class="form-control" id="comment-content" name="content" rows="3" required></textarea>
                                            </div>
                                            
                                            <div class="text-end">
                                                <button type="button" id="cancel-reply" class="btn btn-light me-2 d-none">Annuler la réponse</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-paper-plane me-2"></i>Publier
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> ou 
                                    <a href="{{ route('register') }}" class="alert-link">créez un compte</a> pour laisser un commentaire.
                                </div>
                                @endauth

                                <div class="comments-container">
                                @forelse($project->comments()->where('parent_id', null)->orderByDesc('created_at')->get() as $comment)
                                    @if(!$comment->is_hidden || (auth()->check() && auth()->user()->role === 'admin'))
                                        <div class="card mb-3 {{ $comment->is_hidden ? 'bg-light' : '' }}" id="comment-{{ $comment->id }}">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    @if($comment->user && $comment->user->avatar)
                                                        <img src="{{ asset($comment->user->avatar) }}" class="rounded-circle img-thumbnail me-3" width="40" height="40" alt="Avatar">
                                                    @elseif($comment->user)
                                                        <div class="avatar-placeholder rounded-circle me-3 d-flex align-items-center justify-content-center bg-{{ $comment->user->role === 'admin' ? 'danger' : ($comment->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 40px; height: 40px; font-size: 1rem;">
                                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="w-100">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div>
                                                                @if($comment->user)
                                                                <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                @if($comment->user->role === 'admin')
                                                                    <span class="badge bg-danger ms-2">Admin</span>
                                                                @elseif($comment->user->role === 'creator')
                                                                    <span class="badge bg-success ms-2">Créateur</span>
                                                                @endif
                                                                @endif
                                                            </div>
                                                            
                                                            @if(auth()->check() && $comment->user && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        @if(auth()->id() === $comment->user_id)
                                                                            <li><button class="dropdown-item edit-comment" data-comment-id="{{ $comment->id }}"><i class="fas fa-edit me-2"></i>Modifier</button></li>
                                                                        @endif
                                                                    
                                                                        
                                                                        @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                                                            <li><hr class="dropdown-divider"></li>
                                                                            <li>
                                                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Supprimer</button>
                                                                                </form>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($comment->is_hidden)
                                                            <div class="alert alert-warning mb-2 py-2">
                                                                <i class="fas fa-eye-slash me-2"></i>Ce commentaire a été masqué par un administrateur.
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="comment-content mb-3">
                                                            {{ $comment->content }}
                                                        </div>
                                                        
                                                        @auth
                                                            <div class="d-flex">
                                                                <button class="btn btn-sm btn-outline-secondary reply-button" data-comment-id="{{ $comment->id }}">
                                                                    <i class="fas fa-reply me-1"></i>Répondre
                                                                </button>
                                                            </div>
                                                        @endauth
                                                        @if($comment->replies()->count() > 0)
                                                        <div class="replies mt-3">
                                                                @foreach($comment->replies->where('is_hidden', false)->sortBy('created_at') as $reply)
                                                                    <div class="card mb-2 {{ $reply->is_hidden ? 'bg-light' : '' }}" id="comment-{{ $reply->id }}">
                                                                        <div class="card-body py-3">
                                                                            <div class="d-flex">
                                                                                @if($reply->user && $reply->user->avatar)
                                                                                    <img src="{{ asset($reply->user->avatar) }}" class="rounded-circle img-thumbnail me-2" width="30" height="30" alt="Avatar">
                                                                                @elseif($reply->user)
                                                                                    <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center bg-{{ $reply->user->role === 'admin' ? 'danger' : ($reply->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                                                    </div>
                                                                                @endif
                                                                                
                                                                                <div class="w-100">
                                                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                                                        <div>
                                                                                            @if($reply->user)
                                                                                            <h6 class="mb-0 fw-bold fs-6">{{ $reply->user->name }}</h6>
                                                                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                            @if($reply->user->role === 'admin')
                                                                                                <span class="badge bg-danger ms-2">Admin</span>
                                                                                            @elseif($reply->user->role === 'creator')
                                                                                                <span class="badge bg-success ms-2">Créateur</span>
                                                                                            @endif
                                                                                            @endif
                                                                                        </div>
                                                                                        
                                                                                        @if(auth()->check() && $reply->user && (auth()->id() === $reply->user_id || auth()->user()->role === 'admin'))
                                                                                            <div class="dropdown">
                                                                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                                                </button>
                                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                                    @if(auth()->id() === $reply->user_id)
                                                                                                        <li><button class="dropdown-item edit-comment" data-comment-id="{{ $reply->id }}"><i class="fas fa-edit me-2"></i>Modifier</button></li>
                                                                                                    @endif
                                                                                                    
                                                                                                   
                                                                                                    
                                                                                                    @if(auth()->id() === $reply->user_id || auth()->user()->role === 'admin')
                                                                                                        <li><hr class="dropdown-divider"></li>
                                                                                                        <li>
                                                                                                            <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">
                                                                                                                @csrf
                                                                                                                @method('DELETE')
                                                                                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Supprimer</button>
                                                                                                            </form>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                </ul>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    
                                                                                    @if($reply->is_hidden)
                                                                                        <div class="alert alert-warning mb-2 py-1 small">
                                                                                            <i class="fas fa-eye-slash me-1"></i>Cette réponse a été masquée par un administrateur.
                                                                                        </div>
                                                                                    @endif
                                                                                    
                                                                                    <div class="comment-content">
                                                                                        {{ $reply->content }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="alert alert-light text-center">
                                        <i class="far fa-comment-dots fa-2x mb-3"></i>
                                        <p>Aucun commentaire pour le moment. Soyez le premier à donner votre avis !</p>
                                    </div>
                                @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p class="text-muted mb-0">
                                @if(is_string($project->start_date))
                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                @else
                                    {{ $project->start_date->format('d/m/Y') }}
                                @endif
                            </p>                        
                        </div>
                    </div>
                    
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ number_format($project->progress_percentage, 0, ',', ' ') }}%" 
                             aria-valuenow="{{ number_format($project->progress_percentage, 0, ',', ' ') }}" 
                             aria-valuemin="0" aria-valuemax="100">
                             {{ number_format($project->progress_percentage, 0, ',', ' ') }}%
                        </div>
                    </div>
                    
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($project->funding_goal, 0, ',', ' ') }} €</h5>
                            <small class="text-muted">Objectif</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($project->total_collected, 0, ',', ' ') }} €</h5>
                            <small class="text-muted">Collectés</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ $project->remaining_days }}</h5>
                            <small class="text-muted">Jours restants</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                    <a href="{{ route('project.support', $project->slug) }}" class="btn btn-success btn-lg">

                            <i class="fas fa-dollar-sign me-2"></i>Soutenir ce projet
</a>
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#shareProjectModal">
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
                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Soutiens</h5>
        <p>Nombre de soutiens : {{ $project->contributions()->where('status', 'completed')->count() }}</p>
        <p>Total collecté : {{ number_format($project->total_collected, 0, ',', ' ') }} €</p>
        
        @if($project->contributions()->where('status', 'completed')->count() > 0)
            <hr>
            <h6>Derniers donateurs</h6>
            <ul class="list-group list-group-flush">
                @foreach($project->contributions()->where('status', 'completed')->latest()->take(5)->get() as $contribution)
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if(!$contribution->anonymous && $contribution->user)
                                    @if($contribution->user->avatar)
                                        <img src="{{ asset($contribution->user->avatar) }}" class="rounded-circle img-thumbnail me-2" width="30" height="30" alt="Avatar">
                                    @else
                                        <div class="avatar-placeholder rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-{{ $contribution->user->role === 'admin' ? 'danger' : ($contribution->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($contribution->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $contribution->user->name }}</span>
                                @else
                                    <div class="avatar-placeholder rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span>Donateur anonyme</span>
                                @endif
                            </div>
                            <span class="fw-bold">{{ number_format($contribution->amount, 0, ',', ' ') }} €</span>
                        </div>
                        @if($contribution->comment)
                            <p class="text-muted small mt-1 ms-4">{{ $contribution->comment }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
            
            @if($project->contributions()->where('status', 'completed')->count() > 5)
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#allContributorsModal">
                        Voir tous les donateurs
                    </a>
                </div>
            @endif
        @else
            <div class="alert alert-light text-center mt-3">
                <p class="mb-0">Aucun don pour le moment. Soyez le premier à soutenir ce projet !</p>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>

<div class="modal fade" id="allContributorsModal" tabindex="-1" aria-labelledby="allContributorsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allContributorsModalLabel">Tous les donateurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    @foreach($project->contributions()->where('status', 'completed')->latest()->get() as $contribution)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if(!$contribution->anonymous && $contribution->user)
                                        @if($contribution->user->avatar)
                                            <img src="{{ asset($contribution->user->avatar) }}" class="rounded-circle img-thumbnail me-2" width="30" height="30" alt="Avatar">
                                        @else
                                            <div class="avatar-placeholder rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-{{ $contribution->user->role === 'admin' ? 'danger' : ($contribution->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($contribution->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span>{{ $contribution->user->name }}</span>
                                    @else
                                        <div class="avatar-placeholder rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>Donateur anonyme</span>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">{{ number_format($contribution->amount, 0, ',', ' ') }} €</span>
                                    <div class="text-muted small">{{ $contribution->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @if($contribution->comment)
                                <p class="text-muted small mt-1 ms-4">{{ $contribution->comment }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Modifier le commentaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="edit-comment-form">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-comment-content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="edit-comment-content" name="content" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="shareProjectModal" tabindex="-1" aria-labelledby="shareProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareProjectModalLabel">Partager ce projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4 text-center">
                    <p class="mb-3">Partagez ce projet sur les réseaux sociaux :</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" target="_blank" class="text-decoration-none">
                            <div class="share-icon bg-primary text-white rounded-circle p-2">
                            <i data-feather="facebook" class="feather-sm"></i>
                            </div>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <div class="share-icon bg-info text-white rounded-circle p-2">
                            <i data-feather="twitter" class="feather-sm"></i>
                            </div>
                        </a>
                        <a href="#" target="_blank" class="text-decoration-none">
                            <div class="share-icon bg-primary text-white rounded-circle p-2" style="background-color: #0077b5;">
                            <i data-feather="linkedin" class="feather-sm"></i>
                            </div>
                        </a>
                        <a href="#" target="_blank" class="text-decoration-none">
                            <div class="share-icon bg-success text-white rounded-circle p-2">
                            <i data-feather="message-square" class="feather-sm"></i>
                            </div>
                        </a>
                        <a href="mailto:?subject={{ urlencode('Découvrez ce projet: ' . $project->name) }}&body={{ urlencode('Bonjour, je pense que ce projet pourrait vous intéresser: ' . route('project.show', $project->slug)) }}" class="text-decoration-none">
                            <div class="share-icon bg-danger text-white rounded-circle p-2">
                            <i data-feather="mail" class="feather-sm"></i>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="mb-2">Ou partagez ce lien directement :</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="shareLink" value="{{ route('project.show', $project->slug) }}" readonly>
                        <button class="btn btn-outline-success" type="button" id="copyLinkBtn" onclick="copyShareLink()">
                            <i class="fas fa-copy me-1"></i> Copier
                        </button>
                    </div>
                    <div id="linkCopiedAlert" class="alert alert-success d-none">
                        Lien copié !
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection