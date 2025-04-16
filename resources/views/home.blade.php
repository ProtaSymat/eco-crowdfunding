@extends('layouts.app')

@section('content')
<div class="container">
    <div class="slider-container my-5">
        <div id="eco-slider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="slider-title">Financez l'avenir durable</h2>
                            <p class="slider-subtitle">Soutenez des projets écologiques innovants et participez à la transition énergétique</p>
                            <a href="{{ route('project.index') }}" class="btn btn-success mt-3">Découvrir les projets</a>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('images/slide-1.png') }}" class="img-fluid rounded" alt="Projets écologiques">
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="slider-title">Investissez responsable</h2>
                            <p class="slider-subtitle">Des rendements financiers qui respectent l'environnement et les communautés</p>
                            <a href="#" class="btn btn-success mt-3">Comment ça marche</a>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('images/slide-2.png') }}" class="img-fluid rounded" alt="Investissement responsable">
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="slider-title">Créez votre projet</h2>
                            <p class="slider-subtitle">Lancez votre initiative écologique et trouvez des contributeurs engagés</p>
                            <a href="#" class="btn btn-success mt-3">Proposer un projet</a>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('images/slide-3.png') }}" class="img-fluid rounded" alt="Créer un projet">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>
    
<hr class="mb-4">
    <div>
      <div class="container">
        <div class="d-flex">
          <div class="col-md-6 col-12">
            <div class="me-5"><img alt="Phone app illustration" src="{{ asset('images/Tinder.png') }}" loading="lazy" class="w-100"></div>
            <h3 class="section-2-heading">Investir sur CleanIT</h3>
            <h3 class="heading-4">comment ça marche ?</h3>
            <p class="main-paragraph main-paragraph-small">Si vous avez décidé de prendre le contrôle de votre épargne pour investir de manière responsable et efficace avec CleanIT, découvrez les prochaines étapes simples et rapides. <br>
            </p>
          </div>
          <div class="col-md-6 col-12">
            <div class="w-layout-grid grid-3">
              <div class="card how-it-works-card p-4">
                <div class="card-icon-item">
                  <i data-feather="user"></i>
                </div>
                <div class="div-block-11">
                  <div class="card-title">Créez votre compte</div>
                  <p class="paragraph-17">en moins de 10 minutes</p>
                </div>
              </div>
              <div class="card how-it-works-card p-4">
                <div class="card-icon-item">
                <i data-feather="sun"></i>
                </div>
                <div class="div-block-11">
                  <div class="card-title">Sélectionnez un projet</div>
                  <p class="paragraph-17">en accord avec vos valeurs</p>
                </div>
              </div>
              <div class="card how-it-works-card p-4">
                <div class="card-icon-item blue">
                <i data-feather="lock"></i>
                </div>
                <div class="div-block-11">
                  <div class="card-title">Souscrivez en ligne</div>
                  <p class="paragraph-17">sans aucun frais</p>
                </div>
              </div>
              <div class="card how-it-works-card p-4">
                <div class="card-icon-item blue">
                <i data-feather="dollar-sign"></i>
                </div>
                <div class="div-block-11">
                  <div class="card-title">Percevez vos revenus</div>
                  <p class="paragraph-17">intérêts, dividendes et capital</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<div class="featured-projects py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3 class="text-center mb-4">Projets à la une</h3>
        </div>
    </div>
    
    <div class="row">
        @forelse($latestProjects ?? [] as $project)
        <div class="col-md-4 mb-4">
            <div class="card project-card h-100">
                <img src="{{ $project->cover_image ? asset('storage/'.$project->cover_image) : asset('images/default-project.jpg') }}" class="card-img-top" alt="{{ $project->name }}">
                <div class="card-body">
                    <div class="project-category mb-2">
                        <span class="badge bg-success">{{ $project->category->name }}</span>
                    </div>
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ Str::limit($project->short_description, 100) }}</p>
                    <div class="project-progress mb-3">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($project->funded_amount / $project->funding_goal) * 100 }}%" 
                                aria-valuenow="{{ ($project->funded_amount / $project->funding_goal) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small>{{ number_format(($project->funded_amount / $project->funding_goal) * 100, 0) }}% financé</small>
                            <small>{{ number_format($project->funding_goal, 0, ',', ' ') }} € objectif</small>
                        </div>
                    </div>
                    <div class="project-info d-flex justify-content-between">
                        <div>
                            <i data-feather="users" class="feather-sm"></i>
                            <small>{{ $project->backers_count }} investisseurs</small>
                        </div>
                        <div>
                            <i data-feather="calendar" class="feather-sm"></i>
                            <small>{{ $project->days_left }} jours restants</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('project.show', $project->slug) }}" class="btn btn-outline-success w-100">Découvrir</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p>Aucun projet disponible pour le moment</p>
        </div>
        @endforelse
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('project.index') }}" class="btn btn-success">Voir tous les projets</a>
    </div>
</div>

    <div class="testimonials py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h3 class="mb-4">Ce que nos utilisateurs disent</h3>
                    <p class="lead">Découvrez les témoignages de ceux qui ont déjà fait confiance à CleanIT pour leurs investissements responsables</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="testimonial-icon mb-3">
                                <i data-feather="message-circle" class="text-success"></i>
                            </div>
                            <p class="card-text">"Grâce à CleanIT, j'ai pu investir dans des projets qui correspondent vraiment à mes valeurs. La plateforme est intuitive et la transparence sur chaque initiative est exemplaire."</p>
                            <div class="testimonial-rating mb-3">
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center">
                                <div class="testimonial-avatar me-3">
                                <i data-feather="user"></i>                                </div>
                                <div>
                                    <h6 class="mb-0">Sophie Martin</h6>
                                    <small class="text-muted">Investisseuse depuis 2022</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="testimonial-icon mb-3">
                                <i data-feather="message-circle" class="text-success"></i>
                            </div>
                            <p class="card-text">"En tant que porteur de projet, j'ai été impressionné par l'accompagnement de CleanIT. L'équipe nous a aidés à structurer notre offre et à atteindre notre objectif de financement en seulement 3 semaines !"</p>
                            <div class="testimonial-rating mb-3">
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center">
                                <div class="testimonial-avatar me-3">
                                    <i data-feather="user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Thomas Dubois</h6>
                                    <small class="text-muted">Fondateur de SolarWave</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="testimonial-icon mb-3">
                                <i data-feather="message-circle" class="text-success"></i>
                            </div>
                            <p class="card-text">"J'hésitais à me lancer dans l'investissement durable, mais CleanIT a rendu le processus simple et accessible. Je suis fière de contribuer à des projets qui ont un réel impact environnemental."</p>
                            <div class="testimonial-rating mb-3">
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star" class="text-warning"></i>
                                <i data-feather="star-half" class="text-warning"></i>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center">
                                <div class="testimonial-avatar me-3">
                                <i data-feather="user"></i>                                </div>
                                <div>
                                    <h6 class="mb-0">Léa Bernard</h6>
                                    <small class="text-muted">Investisseuse depuis 2023</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var slider = new bootstrap.Carousel(document.getElementById('eco-slider'), {
            interval: 5000,
            wrap: true
        });
    });
</script>
@endpush

@push('styles')
<style>
    .slider-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    
    .slider-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .slider-subtitle {
        font-size: 1.2rem;
        color: #7f8c8d;
        margin-bottom: 1.5rem;
    }
    
    .carousel-item {
        padding: 2rem 0;
    }
    
    .carousel-indicators {
        bottom: -10px;
    }
    
    .carousel-indicators button {
        background-color: #27ae60;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    /* Styles pour les cartes de projets */
    .project-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .project-category .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
    
    .feather-sm {
        width: 16px;
        height: 16px;
        stroke-width: 2.5;
        vertical-align: middle;
        margin-right: 5px;
    }
    
    /* Styles pour les témoignages */
    .testimonials {
        background-color: #f8f9fa;
    }
    
    .testimonial-card {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
    }
    
    .testimonial-icon {
        text-align: center;
    }
    
    .testimonial-icon svg {
        width: 40px;
        height: 40px;
        stroke-width: 1;
    }
    
    .testimonial-rating svg {
        width: 18px;
        height: 18px;
        stroke-width: 2;
        margin-right: 2px;
    }
</style>
@endpush
@endsection