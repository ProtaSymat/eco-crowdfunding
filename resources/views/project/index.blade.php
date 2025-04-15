@extends('layouts.app')

@section('content')
    <div class="row mb-5">
        <div class="col-12 text-center bg-success text-white p-5 rounded-3">
            <h1 class="display-4 fw-bold">Découvrez les projets</h1>
            <p class="lead">Soutenez des initiatives éco-responsables et aidez ceux qui en ont besoin</p>
            @auth
                <a href="{{ route('project.create') }}" class="btn btn-light btn-lg mt-3">
                    <i class="fas fa-plus-circle me-2"></i>Proposer un projet
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg mt-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour proposer un projet
                </a>
            @endauth
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('project.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" name="category" id="category">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="tag" class="form-label">Tag</label>
                            <select class="form-select" name="tag" id="tag">
                                <option value="">Tous les tags</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" name="status" id="status">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>En cours</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Trier par</label>
                            <select class="form-select" name="sort" id="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Plus populaires</option>
                                <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Se termine bientôt</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Mis en avant</option>
                            </select>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-filter me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('project.index') }}" class="btn btn-outline-secondary px-4 ms-2">
                                <i class="fas fa-sync-alt me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($projects->where('featured', true)->count() > 0 && request()->query->count() === 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Projets en vedette</h2>
            <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($projects->where('featured', true)->take(5) as $index => $featuredProject)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="card border-0 shadow">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <img src="{{ asset('storage/' . $featuredProject->cover_image) }}" 
                                             class="img-fluid rounded-start" alt="{{ $featuredProject->name }}"
                                             style="height: 400px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body h-100 d-flex flex-column">
                                            <h3 class="card-title">{{ $featuredProject->name }}</h3>
                                            <p class="card-text">{{ $featuredProject->short_description }}</p>
                                            
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $featuredProject->progress_percentage }}%" 
                                                     aria-valuenow="{{ $featuredProject->progress_percentage }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ $featuredProject->progress_percentage }}%
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mb-3">
                                                <div>
                                                    <small class="text-muted">Objectif</small>
                                                    <p class="fw-bold">{{ number_format($featuredProject->funding_goal, 0, ',', ' ') }} €</p>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Jours restants</small>
                                                    <p class="fw-bold">{{ $featuredProject->remaining_days }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-auto">
                                                <a href="{{ route('project.show', $featuredProject->slug) }}" 
                                                   class="btn btn-success btn-lg w-100">Découvrir ce projet</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">
                {{ request()->query->count() > 0 ? 'Résultats de la recherche' : 'Tous les projets' }}
                <small class="text-muted">({{ $projects->total() }} projet(s))</small>
            </h2>
            
            @if($projects->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Aucun projet ne correspond à vos critères de recherche.
                </div>
            @endif
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($projects as $project)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm project-card">
                        <div class="position-relative">
    <img src="{{ asset('storage/' . $project->cover_image) }}" 
         class="card-img-top" alt="{{ $project->name }}"
         style="height: 200px; object-fit: cover;">
         
    @if($project->featured)
        <span class="position-absolute top-0 start-0 badge bg-warning m-2">
            <i class="fas fa-star me-1"></i>En vedette
        </span>
    @endif
    
    <span class="position-absolute top-0 end-0 badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'pending' ? 'warning' : ($project->status === 'completed' ? 'primary' : 'danger')) }} m-2">
        {{ $project->status === 'active' ? 'En cours' : ($project->status === 'pending' ? 'En attente' : ($project->status === 'completed' ? 'Financé' : 'Non financé')) }}
    </span>
    
    @auth
    <div class="position-absolute top-0 start-0 ms-3 mt-3 favorite-btn" 
         data-project-id="{{ $project->id }}"
         role="button">
        <i data-feather="star" 
           class="favorite-icon {{ Auth::user()->favorites->contains('project_id', $project->id) ? 'text-warning fill-warning' : 'text-white' }}"
           stroke-width="2"
           style="cursor: pointer;"></i>
    </div>
    @endauth
</div>
                            
                            <div class="card-body d-flex flex-column">
                            <div class="progress mb-3 mt-auto">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ number_format($project->progress_percentage, 0, ',', ' ') }}%" 
                                         aria-valuenow="{{ number_format($project->progress_percentage, 0, ',', ' ') }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                         {{ number_format($project->progress_percentage, 0, ',', ' ') }}%
                                    </div>
                                </div>
                                <h5 class="card-title">{{ Str::limit($project->name, 40) }}</h5>
                                <p class="card-text">{{ Str::limit($project->short_description, 100) }}</p>
                                
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="text-muted d-flex flex-row align-items-end">
                                        <i data-feather="clock" class="feather-sm me-1"></i>
                                        <small class="me-2">Plus que {{ $project->remaining_days }} jours</small>
                                        <small>Objectif : {{ number_format($project->funding_goal, 0, ',', ' ') }} €</small>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-muted small border-rounded border">
                                        <i class="fas fa-tag me-1"></i>{{ $project->category->name }}
                                    </p>
                                </div>
                                
                                <div class="d-flex align-items-center mt-auto">
                                @if($project->user->avatar)
                                <img src="{{ asset($project->user->avatar) }}" class="rounded-circle img-thumbnail" width="80" height="80" alt="Avatar">
                                @else
                                    <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center bg-{{ $project->user->role === 'admin' ? 'danger' : ($project->user->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 40px; height: 40px; font-size: 1rem;">
                                        {{ strtoupper(substr($project->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                    <span class="text-muted small">Par {{ $project->user->name }}</span>
                                </div>
                                
                                <a href="{{ route('project.show', $project->slug) }}" class="btn btn-outline-success mt-3 w-100">
                                    Voir le projet
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-5">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-light p-4 text-center">
                <div class="card-body">
                    <h3 class="mb-3">Vous avez un projet éco-responsable ?</h3>
                    <p class="mb-4">Lancez votre campagne de financement sur CleanIT et rejoignez notre communauté engagée pour un monde plus propre et plus juste.</p>
                    @auth
                        <a href="{{ route('project.create') }}" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-lightbulb me-2"></i>Lancer mon projet
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour proposer un projet
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectCards = document.querySelectorAll('.project-card');
        
        projectCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        if (document.getElementById('featuredCarousel')) {
            const carousel = new bootstrap.Carousel(document.getElementById('featuredCarousel'), {
                interval: 5000,
                wrap: true
            });
        }

        const favoriteButtons = document.querySelectorAll('.favorite-btn');
        favoriteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                console.log('test');
                e.preventDefault();
                e.stopPropagation();
                const projectId = this.dataset.projectId;
                const iconElement = this.querySelector('.favorite-icon');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/projects/${projectId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    if (data.status === 'added') {
                        iconElement.classList.add('text-warning', 'fill-warning');
                        iconElement.classList.remove('text-white');
                    } else {
                        iconElement.classList.remove('text-warning', 'fill-warning');
                        iconElement.classList.add('text-white');
                    }
                    
                    feather.replace();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
@endpush