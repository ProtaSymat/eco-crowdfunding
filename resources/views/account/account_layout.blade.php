@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle img-thumbnail" width="80" height="80" alt="Avatar">
                        @else
                            <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                    
                    @if(Auth::user()->role === 'admin')
                        <span class="badge bg-danger mb-3">Administrateur</span>
                    @elseif(Auth::user()->role === 'creator')
                        <span class="badge bg-success mb-3">Créateur</span>
                    @elseif(Auth::user()->role === 'moderator')
                        <span class="badge bg-warning mb-3">Modérateur</span>
                    @else
                        <span class="badge bg-primary mb-3">Utilisateur</span>
                    @endif
                    
                    <div class="d-grid">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'creator' ? 'success' : 'primary') }}">
                            <i data-feather="edit" class="feather-sm me-1"></i>Modifier le profil
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h6 class="mb-0">{{ Auth::user()->created_at->format('d/m/Y') }}</h6>
                            <small class="text-muted">Membre depuis</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ date('d/m/Y H:i', strtotime(now())) }}</h6>
                            <small class="text-muted">Dernière connexion</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'creator' ? 'success' : 'primary') }} text-white">
                    <h5 class="mb-0">
                        @if(Auth::user()->role === 'admin')
                            Administration
                        @elseif(Auth::user()->role === 'creator')
                            Espace Créateur
                        @else
                            Mon Compte
                        @endif
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-feather="home" class="feather-sm me-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <i data-feather="user" class="feather-sm me-2"></i>Mon profil
                    </a>
                    <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('user.contributions') ? 'active' : '' }}">
                        <i data-feather="dollar-sign" class="feather-sm me-2"></i>Mes contributions
                    </a>
                    
                    @if(Auth::user()->role === 'backer')
                    <button onclick="document.getElementById('become-creator-form').submit();">Devenir Créateur</button>

                    <form id="become-creator-form" action="{{ route('profile.become-creator') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    @endif
                    
                    @if(Auth::user()->role === 'creator' || Auth::user()->role === 'admin')
                        <div class="list-group-item bg-light text-muted">
                            <small class="text-uppercase fw-bold">Gestion de projets</small>
                        </div>
                        <a href="{{ route('creator.projects') }}" class="list-group-item list-group-item-action {{ request()->routeIs('creator.projects') ? 'active' : '' }}">
                            <i data-feather="briefcase" class="feather-sm me-2"></i>Mes projets
                        </a>
                        <a href="{{ route('project.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('creator.project.create') ? 'active' : '' }}">
                            <i data-feather="plus-circle" class="feather-sm me-2"></i>Créer un projet
                        </a>
                    @endif
                    
                    @if(Auth::user()->role === 'admin')
                        <div class="list-group-item bg-light text-muted">
                            <small class="text-uppercase fw-bold">Gestion</small>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i data-feather="users" class="feather-sm me-2"></i>Utilisateurs
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                            <i data-feather="tag" class="feather-sm me-2"></i>Catégories
                        </a>
                        <a href="{{ route('admin.projects') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
                            <i data-feather="briefcase" class="feather-sm me-2"></i>Projets
                        </a>
                        <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('admin.contributions') ? 'active' : '' }}">
                            <i data-feather="credit-card" class="feather-sm me-2"></i>Contributions
                        </a>
                        <div class="list-group-item bg-light text-muted">
                            <small class="text-uppercase fw-bold">Paramètres</small>
                        </div>
                        <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i data-feather="settings" class="feather-sm me-2"></i>Configuration
                        </a>
                        <a href="#" class="list-group-item list-group-item-action {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                            <i data-feather="activity" class="feather-sm me-2"></i>Logs système
                        </a>
                    @endif
                    
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action text-danger">
                        <i data-feather="log-out" class="feather-sm me-2"></i>Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @yield('account_content')
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5, width: 16, height: 16 });
        }
    });
</script>
@endsection