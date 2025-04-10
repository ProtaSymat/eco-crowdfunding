@extends('account.account_layout')

@section('account_content')
<!-- Contenu pour les utilisateurs simples (backers) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h3 class="mb-0">Mon tableau de bord</h3>
    </div>
    <div class="card-body">
        <!-- Messages de succès ou d'erreur -->
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i data-feather="check-circle" class="feather-sm me-1"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i data-feather="alert-circle" class="feather-sm me-1"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row">
            <!-- Résumé des contributions -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i data-feather="heart" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Mes contributions</h5>
                                <p class="text-muted mb-0">Projets que vous soutenez</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $contributionsCount ?? 0 }}</h3>
                        <div class="mt-3">
                            <a href="{{ route('user.contributions') }}" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Projets favoris -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded p-3 me-3">
                                <i data-feather="star" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Projets favoris</h5>
                                <p class="text-muted mb-0">Projets que vous suivez</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $favoritesCount ?? 0 }}</h3>
                        <div class="mt-3">
                            <a href="{{ route('user.favorites') }}" class="btn btn-sm btn-warning">Voir tout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Appel à devenir créateur -->
        <div class="card bg-gradient-primary text-white mt-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary">Devenir créateur</h4>
                        <p class="text-primary">Vous avez un projet à financer ? Partagez votre projet avec notre communauté et obtenez le financement dont vous avez besoin.</p>
                    </div>
                    <!-- <form action="{{ route('become.creator') }}" method="POST" class="col-md-4 text-md-end">
                    @csrf
                    <button type="submit" class="btn btn-light">Commencer maintenant</button>
                </form> -->
                </div>
            </div>
        </div>
        
        <!-- Derniers projets auxquels l'utilisateur a contribué -->
        <h4 class="mt-4">Vos dernières contributions</h4>
        
        @if(isset($recentContributions) && count($recentContributions) > 0)
            <div class="row">
                @foreach($recentContributions as $contribution)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="{{ asset($contribution->project->thumbnail) }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $contribution->project->title }}">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $contribution->project->title }}</h5>
                                        <p class="card-text"><small class="text-muted">{{ $contribution->created_at->format('d/m/Y') }}</small></p>
                                        <p class="card-text fw-bold">{{ number_format($contribution->amount, 2) }} €</p>
                                        <a href="{{ route('projects.show', $contribution->project->slug) }}" class="btn btn-sm btn-outline-primary">Voir le projet</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                <i data-feather="info" class="feather-sm me-1"></i>
                Vous n'avez pas encore contribué à des projets. Découvrez des projets intéressants et soutenez-les !
            </div>
            <a href="{{ route('projects.index') }}" class="btn btn-primary">Découvrir des projets</a>
        @endif
    </div>
</div>
@endsection