@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Slider Section -->
    <div class="slider-container my-5">
        <div id="eco-slider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="slider-title">Financez l'avenir durable</h2>
                            <p class="slider-subtitle">Soutenez des projets écologiques innovants et participez à la transition énergétique</p>
                            <a href="{{ route('projects.index') }}" class="btn btn-success mt-3">Découvrir les projets</a>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('images/slide-1.png') }}" class="img-fluid rounded" alt="Projets écologiques">
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
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
                
                <!-- Slide 3 -->
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
            
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#eco-slider" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>
    

    <div>
      <div class="container">
        <div class="d-flex">
          <div class="col-md-6 col-12">
            <img sizes="(max-width: 2000px) 100vw, 2000px" alt="Phone app illustration" src="" loading="lazy" class="how-it-works-img">
            <h3 class="section-2-heading">Investir sur CleanIT</h3>
            <h3 class="heading-4">comment ça marche ?</h3>
            <p class="main-paragraph main-paragraph-small">Si vous avez décidé de prendre le contrôle de votre épargne pour investir de manière responsable et efficace avec CleanIT, découvrez les prochaines étapes simples et rapides. <br>
            </p>
          </div>
          <div class="col-md-6 col-12">
            <div class="w-layout-grid grid-3">
              <div class="card how-it-works-card">
                <div class="card-icon-item">
                  <img src="" loading="lazy" alt="user icon" class="image-11">
                </div>
                <img src="" loading="lazy" alt="number 1" class="card-img">
                <div class="div-block-11">
                  <div class="card-title">Créez votre compte</div>
                  <p class="paragraph-17">en moins de 10 minutes</p>
                </div>
              </div>
              <div class="card how-it-works-card">
                <div class="card-icon-item">
                  <img src="" loading="lazy" alt="sun icon" class="image-11 mid">
                </div>
                <img src="" loading="lazy" alt="number 2" class="card-img">
                <div class="div-block-11">
                  <div class="card-title">Sélectionnez un projet</div>
                  <p class="paragraph-17">en accord avec vos valeurs</p>
                </div>
              </div>
              <div class="card how-it-works-card">
                <div class="card-icon-item blue">
                  <img src="" loading="lazy" alt="security badge icon" class="image-11 other">
                </div>
                <img src="" loading="lazy" alt="number 3" class="card-img">
                <div class="div-block-11">
                  <div class="card-title">Souscrivez en ligne</div>
                  <p class="paragraph-17">sans aucun frais</p>
                </div>
              </div>
              <div class="card how-it-works-card">
                <div class="card-icon-item blue">
                  <img src="" loading="lazy" alt="wallet money icon" class="image-11 other">
                </div>
                <img src="" loading="lazy" alt="number 4" class="card-img">
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

    <!-- Featured Projects Section (Vous pouvez ajouter plus de sections ici) -->
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <h3 class="text-center mb-4">Projets à la une</h3>
            <!-- Ici vous pourriez ajouter vos projets en vedette -->
        </div>
    </div>
</div>

<!-- Script pour le slider -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le carousel Bootstrap
        var slider = new bootstrap.Carousel(document.getElementById('eco-slider'), {
            interval: 5000,
            wrap: true
        });
    });
</script>
@endpush

<!-- Style personnalisé pour le slider -->
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
</style>
@endpush
@endsection