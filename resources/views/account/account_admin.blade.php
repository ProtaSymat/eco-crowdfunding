@extends('account.account_layout')

@section('account_content')
<!-- Contenu spécifique aux administrateurs -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Tableau de bord administrateur</h3>
        <!-- Bouton ou autre élément spécifique à l'administration -->
        <a href="{{ route('admin.settings') }}" class="btn btn-primary">
            <i data-feather="settings" class="feather-sm me-1"></i>Paramètres
        </a>
    </div>
    <div class="card-body">
        <!-- Messages de succès ou d'erreur, comme dans la page créateur -->
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
        
        <!-- Contenu spécifique à l'administration, par exemple, gestion des utilisateurs -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0">Gestion des utilisateurs</h4>
            </div>
            <div class="card-body">
                <a href="#" class="btn btn-success mb-3">
                    <i data-feather="user-plus" class="feather-sm me-1"></i>Ajouter un utilisateur
                </a>
                <!-- Tableau des utilisateurs ou autre contenu spécifique à l'administration -->
            </div>
        </div>
        
        <!-- Vous pouvez ajouter ici d'autres sections spécifiques aux administrateurs -->
        
    </div>
</div>

<!-- Scripts et styles spécifiques à la page admin, si nécessaire -->
<script>
    // Script JavaScript spécifique à l'administration
</script>

<style>
    /* Styles CSS spécifiques à l'administration */
</style>
@endsection