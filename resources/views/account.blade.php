@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar/Menu -->
        <div class="col-md-3">
            <!-- Informations administrateur résumées dans la sidebar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle img-thumbnail" width="80" height="80" alt="Avatar">
                        @else
                            <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center bg-danger text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                    
                    <span class="badge bg-danger mb-3">Administrateur</span>
                    
                    <div class="d-grid">
                        <a href="#" class="btn btn-sm btn-outline-danger">
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
                            <h6 class="mb-0">{{ Auth::user()->last_login ? date('d/m/Y', strtotime(Auth::user()->last_login)) : 'N/A' }}</h6>
                            <small class="text-muted">Dernière connexion</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Administration</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="grid" class="feather-sm me-2"></i>Tableau de bord
                    </a>
                    <div class="list-group-item bg-light text-muted">
                        <small class="text-uppercase fw-bold">Gestion</small>
                    </div>
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i data-feather="users" class="feather-sm me-2"></i>Utilisateurs
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="tag" class="feather-sm me-2"></i>Catégories
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="briefcase" class="feather-sm me-2"></i>Projets
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="credit-card" class="feather-sm me-2"></i>Contributions
                    </a>
                    <div class="list-group-item bg-light text-muted">
                        <small class="text-uppercase fw-bold">Paramètres</small>
                    </div>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="settings" class="feather-sm me-2"></i>Configuration
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i data-feather="activity" class="feather-sm me-2"></i>Logs système
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action text-danger">
                        <i data-feather="log-out" class="feather-sm me-2"></i>Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content (Users Management) -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestion des utilisateurs</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i data-feather="plus" class="feather-sm me-1"></i>Nouvel utilisateur
                    </button>
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
                    
                    <!-- Filtres de recherche -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Rechercher un utilisateur..." id="searchUser">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterRole">
                                <option value="">Tous les rôles</option>
                                <option value="admin">Administrateurs</option>
                                <option value="moderator">Modérateurs</option>
                                <option value="creator">Créateurs</option>
                                <option value="backer">Utilisateurs</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tableau des utilisateurs -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Rôle</th>
                                    <th scope="col">Date d'inscription</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        
                    </div>
                </div>
            </div>
            
            <!-- Gestion des catégories (caché par défaut, s'affiche lors de la navigation) -->
            <div class="card shadow-sm d-none" id="categoriesManagement">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestion des catégories</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i data-feather="plus" class="feather-sm me-1"></i>Nouvelle catégorie
                    </button>
                </div>
                <div class="card-body">
                    <!-- Messages de succès ou d'erreur -->
                    @if(session('category_success'))
                        <div class="alert alert-success mb-4">
                            <i data-feather="check-circle" class="feather-sm me-1"></i>
                            {{ session('category_success') }}
                        </div>
                    @endif
                    
                    <!-- Filtres de recherche -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Rechercher une catégorie..." id="searchCategory">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="active">Actives</option>
                                <option value="inactive">Inactives</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tableau des catégories -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Projets</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                             
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour la gestion des utilisateurs -->
<!-- Modal Création d'utilisateur -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Créer un nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="backer">Utilisateur</option>
                                <option value="creator">Créateur</option>
                                <option value="moderator">Modérateur</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified">
                        <label class="form-check-label" for="email_verified">
                            Email vérifié
                        </label>
                        <div class="form-text">Cochez cette case pour marquer l'adresse email comme déjà vérifiée.</div>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biographie</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression d'utilisateur (à répéter pour chaque utilisateur dans une boucle) -->

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong></strong> ?</p>
                <p class="text-danger mb-0">Cette action est irréversible et supprimera toutes les données associées à cet utilisateur.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modals pour la gestion des catégories -->
<!-- Modal Création de catégorie -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Créer une nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cat_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                        <div class="form-text">L'identifiant unique utilisé dans les URLs (ex: technologie, art, sport).</div>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icône</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="Ex: briefcase, music, heart">
                        <div class="form-text">Nom de l'icône Feather (voir <a href="https://feathericons.com/" target="_blank">feathericons.com</a>).</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Catégorie active
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression de catégorie (à répéter pour chaque catégorie dans une boucle) -->

<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong></strong> ?</p>
                <p class="text-danger mb-0">Cette action peut affecter les projets liés à cette catégorie.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Feather
        if (typeof feather !== 'undefined') {
            feather.replace({ 'stroke-width': 1.5, width: 16, height: 16 });
        }
        
        // Navigation entre les sections
        const userLink = document.querySelector('a[href="#"]');
        const categoryLink = document.querySelector('a[href="#""]');
        const userManagement = document.querySelector('.card:not(#categoriesManagement)');
        const categoryManagement = document.querySelector('#categoriesManagement');
        
        if (userLink && categoryLink && userManagement && categoryManagement) {
            userLink.addEventListener('click', function(e) {
                e.preventDefault();
                userManagement.classList.remove('d-none');
                categoryManagement.classList.add('d-none');
                
                userLink.classList.add('active');
                categoryLink.classList.remove('active');
            });
            
            categoryLink.addEventListener('click', function(e) {
                e.preventDefault();
                userManagement.classList.add('d-none');
                categoryManagement.classList.remove('d-none');
                
                userLink.classList.remove('active');
                categoryLink.classList.add('active');
            });
        }
        
        // Auto-générer le slug basé sur le nom de la catégorie
        const categoryNameInput = document.getElementById('cat_name');
        const slugInput = document.getElementById('slug');
        
        if (categoryNameInput && slugInput) {
            categoryNameInput.addEventListener('input', function() {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            });
        }
        
        // Filtres de recherche pour les utilisateurs
        const searchUserInput = document.getElementById('searchUser');
        const filterRoleSelect = document.getElementById('filterRole');
        
        if (searchUserInput && filterRoleSelect) {
            const applyUserFilters = () => {
                const searchValue = searchUserInput.value.toLowerCase();
                const roleValue = filterRoleSelect.value;
                
                const userRows = document.querySelectorAll('tbody tr');
                
                userRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const role = row.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
                    
                    const matchesSearch = name.includes(searchValue) || email.includes(searchValue);
                    const matchesRole = !roleValue || role.includes(roleValue);
                    
                    row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
                });
            };
            
            searchUserInput.addEventListener('input', applyUserFilters);
            filterRoleSelect.addEventListener('change', applyUserFilters);
        }
    });
</script>
@endsection