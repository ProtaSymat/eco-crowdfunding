@extends('layouts.app')

@section('content')
    <div class="row mb-5">
        <div class="col-12 text-center text-success p-3 rounded-3">
            <h1 class="display-4 fw-bold">À propos de CleanIT</h1>
            <p class="lead">Découvrez notre histoire, notre mission et les personnes qui font de CleanIT ce qu'elle est aujourd'hui</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Notre Histoire</h3>
                    <p>Fondée en 2018 à Rouen, CleanIT est née de la vision commune de trois amis passionnés par l'environnement et l'action sociale. Face aux défis croissants de la pollution et des inégalités sociales, ils ont décidé d'unir leurs compétences pour créer une organisation qui aborderait ces problèmes de front.</p>
                    <p>Au départ, CleanIT n'était qu'une petite initiative locale organisant des nettoyages de quartier le week-end. Grâce à l'enthousiasme des participants et au soutien de la communauté, l'organisation s'est rapidement développée pour devenir ce qu'elle est aujourd'hui : un acteur majeur de l'écologie sociale en Normandie.</p>
                    <p>Aujourd'hui, CleanIT compte plus de 50 employés et des centaines de bénévoles dévoués qui travaillent ensemble pour rendre notre planète plus propre et aider ceux qui en ont besoin.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Notre Mission</h3>
                    <p>Chez CleanIT, notre mission est double :</p>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-success">
                            <i data-feather="trash" class="feather-sm"></i>
                        </div>
                        <div>
                            <h5>Environnement</h5>
                            <p class="text-muted">Agir concrètement pour réduire la pollution et promouvoir des pratiques écologiques durables au sein des communautés locales.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-success">
                            <i data-feather="heart" class="feather-sm"></i>
                        </div>
                        <div>
                            <h5>Solidarité</h5>
                            <p class="text-muted">Créer des liens entre les initiatives environnementales et sociales pour que la transition écologique profite à tous, particulièrement aux plus vulnérables.</p>
                        </div>
                    </div>
                    
                    <p>Nous croyons fermement que la protection de l'environnement et la justice sociale sont intrinsèquement liées. Notre approche vise à développer des solutions qui répondent simultanément à ces deux défis majeurs de notre époque.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Nos Valeurs</h3>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-success mb-3">
                                        <i data-feather="shield" style="width: 48px; height: 48px;"></i>
                                    </div>
                                    <h4 class="card-title">Intégrité</h4>
                                    <p class="card-text">Nous agissons avec honnêteté et transparence dans toutes nos activités. Nos partenaires et bénéficiaires peuvent compter sur notre engagement sincère et notre gestion rigoureuse des ressources.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-success mb-3">
                                        <i data-feather="users" style="width: 48px; height: 48px;"></i>
                                    </div>
                                    <h4 class="card-title">Inclusion</h4>
                                    <p class="card-text">Nous croyons que chacun a un rôle à jouer dans la construction d'un avenir durable. Nous nous efforçons de créer des espaces accueillants où toutes les voix sont entendues et valorisées.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="text-success mb-3">
                                        <i data-feather="refresh-cw" style="width: 48px; height: 48px;"></i>
                                    </div>
                                    <h4 class="card-title">Innovation</h4>
                                    <p class="card-text">Face aux défis complexes d'aujourd'hui, nous recherchons constamment de nouvelles approches et solutions créatives. Nous encourageons l'expérimentation et l'apprentissage continu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Notre Impact</h3>
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="display-4 fw-bold text-success">120+</div>
                            <p class="text-muted">Opérations de nettoyage organisées</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="display-4 fw-bold text-success">15k</div>
                            <p class="text-muted">Kilos de déchets collectés</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="display-4 fw-bold text-success">200+</div>
                            <p class="text-muted">Bénévoles actifs</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="display-4 fw-bold text-success">25</div>
                            <p class="text-muted">Associations partenaires</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Notre Équipe</h3>
                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="rounded-circle bg-light mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i data-feather="user"></i>
                                    </div>
                                    <h5>Sophie Martin</h5>
                                    <p class="text-success">Directrice Générale</p>
                                    <p class="small text-muted">Co-fondatrice passionnée d'écologie urbaine et d'économie circulaire.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="rounded-circle bg-light mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i data-feather="user"></i>
                                    </div>
                                    <h5>Thomas Dubois</h5>
                                    <p class="text-success">Directeur des Opérations</p>
                                    <p class="small text-muted">Expert en logistique avec 15 ans d'expérience dans l'humanitaire.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="rounded-circle bg-light mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i data-feather="user"></i>
                                    </div>
                                    <h5>Lucie Bernard</h5>
                                    <p class="text-success">Responsable Partenariats</p>
                                    <p class="small text-muted">Spécialiste des relations institutionnelles et du développement de projets collaboratifs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="rounded-circle bg-light mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i data-feather="user"></i>
                                    </div>
                                    <h5>Marc Leroy</h5>
                                    <p class="text-success">Responsable Technique</p>
                                    <p class="small text-muted">Ingénieur en environnement spécialisé dans les technologies propres.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-success mb-4">Rejoignez notre mouvement</h3>
                    <p class="lead mb-4">Vous souhaitez contribuer à notre mission ? Plusieurs façons de vous impliquer s'offrent à vous !</p>
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-success btn-lg w-100">
                                <i data-feather="users" class="feather-sm me-2"></i>Devenir bénévole
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-success btn-lg w-100">
                                <i data-feather="heart" class="feather-sm me-2"></i>Faire un don
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        });

        const hiddenElements = document.querySelectorAll('.card');
        hiddenElements.forEach((el) => observer.observe(el));
    });
</script>

<style>
    .card {
        transition: all 0.5s ease;
        border-radius: 10px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .btn-success {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }
    
    .btn-success:hover {
        background-color: #27ae60;
        border-color: #27ae60;
    }
    
    .text-success {
        color: #2ecc71 !important;
    }
    
    .bg-success {
        background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
    }
    
    .card {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    
    .card.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection