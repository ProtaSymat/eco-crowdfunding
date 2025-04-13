@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Tableau de bord administrateur</h3>
        <a href="#" class="btn btn-primary">
            <i data-feather="settings" class="feather-sm me-1"></i>Paramètres
        </a>
    </div>
    <div class="card-body">
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
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0">Gestion des utilisateurs</h4>
            </div>
            <div class="card-body">
                <a href="#" class="btn btn-success mb-3">
                    <i data-feather="user-plus" class="feather-sm me-1"></i>Ajouter un utilisateur
                </a>
            </div>
        </div>
    </div>
</div>
@endsection