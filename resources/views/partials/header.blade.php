<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border border-bottom py-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" height="30">
                <span class="fw-bold">{{ config('app.name', 'CleanIT') }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route(name: 'project.index') }}"></i>Projets</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('about') }}">À propos</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                
                <form class="d-flex ms-auto my-2 my-lg-0 position-relative shadow-sm" style="max-width: 550px;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i data-feather="search" class="feather-sm text-secondary"></i>
                        </span>
                        <input class="form-control form-control-sm bg-light border-start-0" type="search" placeholder="Rechercher des projets..." aria-label="Search">
                    </div>
                </form>
                
                <ul class="navbar-nav">
                    <li class="nav-item ms-3 me-5 d-none d-lg-block">
                        <a class="nav-link position-relative" href="#" title="Notifications">
                            <i data-feather="bell" class="feather-sm"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                                <span class="visually-hidden">notifications non lues</span>
                            </span>
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i data-feather="log-in" class="feather-sm me-1"></i>{{ __('Se connecter') }}
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-outline-primary ms-2" href="{{ route('register') }}">
                                    <i data-feather="user-plus" class="feather-sm me-1"></i>{{ __('S\'inscrire') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if(Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle img-thumbnail" width="28" height="28" alt="Avatar">
                        @else
                            <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'creator' ? 'success' : 'primary') }} text-white" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="navbarDropdown">
                                @php
                                $user = Auth::user();
                                $dashboardRoute = match($user->role) {
                                    'admin' => 'admin.dashboard',
                                    'creator' => 'creator.dashboard',
                                    default => 'backer.dashboard',
                                };
                                @endphp

                                <a class="dropdown-item" href="{{ route($dashboardRoute) }}">
                                    <i data-feather="user" class="feather-sm me-2"></i>Mon Compte
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i data-feather="settings" class="feather-sm me-2"></i>Paramètres
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i data-feather="log-out" class="feather-sm me-2"></i>{{ __('Déconnexion') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5, width: 18, height: 18 });
        }
    });
</script>