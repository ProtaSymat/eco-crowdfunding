@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Modifier l'utilisateur</h1>
        <div><a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Supprimer
            </button></div>
    </div>
    <div class="card-body">


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Informations de base</h5>
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">{{ $user->id }}</dd>

                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Rôle</dt>
                        <dd class="col-sm-8">
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Administrateur</span>
                            @else
                                <span class="badge bg-primary">Utilisateur</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Statut</dt>
                        <dd class="col-sm-8">
                            @if($user->active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Informations temporelles</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Création</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y à H:i') }}</dd>

                        <dt class="col-sm-4">Dernière mise à jour</dt>
                        <dd class="col-sm-8">{{ $user->updated_at->format('d/m/Y à H:i') }}</dd>

                        <dt class="col-sm-4">Dernière connexion</dt>
                        <dd class="col-sm-8">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y à H:i') : 'Jamais connecté' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?
                <p class="text-danger mt-2">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection