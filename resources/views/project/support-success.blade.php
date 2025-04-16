@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i data-feather="check" class="feather-sm"></i>
                            </div>
                        </div>
                        
                        <h2 class="mb-3">Merci pour votre soutien !</h2>
                        <p class="lead mb-4">Votre don de <strong>{{ session('amount') }} €</strong> pour le projet <strong>{{ $project->name }}</strong> a été traité avec succès.</p>
                        
                        <div class="alert alert-light mb-4">
                            <p class="mb-1"><strong>Référence de transaction :</strong> {{ session('transaction_id') }}</p>
                            <p class="mb-0 small text-muted">Veuillez conserver cette référence pour toute question ultérieure.</p>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('project.show', $project->slug) }}" class="btn btn-primary">
                                <i data-feather="corner-down-left" class="me-2"></i>Retour au projet
                            </a>
                            <a href="{{ route('user.contributions') }}" class="btn btn-outline-secondary">
                                Voir mes contributions
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 mt-4">
                    <div class="card-body text-center py-4">
                        <h5 class="mb-3">Partagez ce projet pour aider à atteindre l'objectif</h5>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('project.show', $project->slug)) }}" target="_blank" class="text-decoration-none">
                                <div class="share-icon bg-primary text-white rounded-circle p-3">
                                <i data-feather="facebook" class="feather-sm"></i>
                                </div>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($project->name) }}&url={{ urlencode(route('project.show', $project->slug)) }}" target="_blank" class="text-decoration-none">
                                <div class="share-icon bg-info text-white rounded-circle p-3">
                                <i data-feather="twitter" class="feather-sm"></i>                                </div>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('project.show', $project->slug)) }}&title={{ urlencode($project->name) }}" target="_blank" class="text-decoration-none">
                                <div class="share-icon bg-primary text-white rounded-circle p-3" style="background-color: #0077b5;">
                                <i data-feather="linkedin" class="feather-sm"></i>                                </div>
                            </a>
                            <a href="mailto:?subject={{ urlencode('Découvrez ce projet: ' . $project->name) }}&body={{ urlencode('Bonjour, je viens de soutenir ce projet et je pense qu\'il pourrait vous intéresser: ' . route('project.show', $project->slug)) }}" class="text-decoration-none">
                                <div class="share-icon bg-danger text-white rounded-circle p-3">
                                <i data-feather="mail" class="feather-sm"></i>                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection