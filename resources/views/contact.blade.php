@extends('layouts.app')

@section('content')
    <div class="row mb-5">
        <div class="col-12 text-center text-success p-3 rounded-3">
            <h1 class="display-4 fw-bold">Contactez CleanIT</h1>
            <p class="lead">Ensemble, rendons notre planète plus propre et aidons ceux qui en ont besoin</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Nos coordonnées</h3>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-success">
                            <i data-feather="map-pin" class="feather-sm"></i>
                        </div>
                        <div>
                            <h5>Adresse</h5>
                            <p class="text-muted">25 Rue du Renard<br>76000 Rouen, France</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-success">
                        <i data-feather="phone" class="feather-sm"></i>

                        </div>
                        <div>
                            <h5>Téléphone</h5>
                            <p class="text-muted">+33 1 42 75 89 63</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="me-3 text-success">
                        <i data-feather="mail" class="feather-sm"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="text-muted">contact@cleanit.org</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="me-3 text-success">
                        <i data-feather="clock" class="feather-sm"></i>

                        </div>
                        <div>
                            <h5>Heures d'ouverture</h5>
                            <p class="text-muted">Lun - Ven: 9h00 - 18h00<br>Sam: 10h00 - 15h00</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="mb-3">Suivez-nous</h5>
                        <div class="d-flex">
                            <a href="#" class="btn btn-outline-success me-2"><i data-feather="facebook" class="feather-sm"></i></a>
                            <a href="#" class="btn btn-outline-success me-2"><i data-feather="twitter" class="feather-sm"></i></a>
                            <a href="#" class="btn btn-outline-success me-2"><i data-feather="instagram" class="feather-sm"></i></a>
                            <a href="#" class="btn btn-outline-success"><i data-feather="linkedin" class="feather-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Envoyez-nous un message</h3>
                    
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="votre@email.com" required>
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Sujet</label>
                                <input type="text" class="form-control" id="subject" placeholder="Sujet de votre message" required>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Ca sert à rien d'envoyer j'ai pas codé la suite" required></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" checked>
                                    <label class="form-check-label" for="newsletter">
                                        Je souhaite recevoir la newsletter de CleanIT
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg px-4">Envoyer le message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-success mb-4">Nous trouver</h3>
                <div id="map" style="height: 400px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2644.0365170801423!2d1.056160376552738!3d49.429092871451885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e0de4c8ab08737%3A0x12c6b54cd7a17898!2s25%20Route%20de%20Renard%2C%2076100%20Rouen!5e0!3m2!1sfr!2sfr!4v1713003471887!5m2!1sfr!2sfr"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-success mb-4">Nos associations partenaires</h3>
                    <div class="row row-cols-2 row-cols-md-4 g-4">
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <i data-feather="heart" class="feather-sm"></i>
                                    <h5>Les Restos du Cœur</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                <i data-feather="cloud-rain" class="feather-sm"></i>
                                    <h5>Greenpeace France</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <i data-feather="trash" class="feather-sm"></i>
                                    <h5>Clean Up France</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                <i data-feather="home" class="feather-sm"></i>                                    <h5>Fondation Abbé Pierre</h5>
                                </div>
                            </div>
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
        var map = L.map('map').setView([48.8588897, 2.3470599], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        L.marker([48.8588897, 2.3470599]).addTo(map)
            .bindPopup('<strong>CleanIT</strong><br>25 Rue de l\'Innovation<br>75011 Paris')
            .openPopup();
        
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