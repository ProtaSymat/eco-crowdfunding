<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-4">
        <div class="container">
            <!-- Logo & Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" height="30">
                <span class="fw-bold">{{ config('app.name', 'CleanIT') }}</span>
            </a>
            
            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route(name: 'projects.index') }}"></i>Projets</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('about') }}">À propos</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex ms-auto my-2 my-lg-0 position-relative shadow-sm" style="max-width: 550px;">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i data-feather="search" class="feather-sm text-secondary"></i>
                        </span>
                        <input class="form-control form-control-sm bg-light border-start-0" type="search" placeholder="Rechercher des projets..." aria-label="Search">
                    </div>
                </form>
                
                <!-- Right-side Navigation -->
                <ul class="navbar-nav">
                    <!-- Notifications -->
                    <li class="nav-item mx-1 d-none d-lg-block">
                        <a class="nav-link position-relative" href="#" title="Notifications">
                            <i data-feather="bell" class="feather-sm"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                                <span class="visually-hidden">notifications non lues</span>
                            </span>
                        </a>
                    </li>
                    
                    <!-- Messages -->
                    <!-- <li class="nav-item mx-1 d-none d-lg-block">
                        <a class="nav-link" href="#" title="Messages">
                            <i data-feather="message-square" class="feather-sm"></i>
                        </a>
                    </li> -->
                    
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
                            <div class="avatar me-2 d-flex justify-content-center align-items-center">
    <div class="rounded-circle border" style="width: 28px; height: 28px;"></div>
</div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('account') }}">
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

<!-- Script pour initialiser les icônes Feather -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5, width: 18, height: 18 });
        }
    });
</script>