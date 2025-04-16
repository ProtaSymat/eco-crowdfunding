@extends('account.account_layout')

@section('account_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des contributions</h1>
        <a href="{{ route('admin.contributions.report') }}" class="btn btn-outline-primary">
            <i data-feather="chart"></i> Générer un rapport
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.contributions.index') }}" method="GET" class="row">
                <div class="col-md-2 mb-3">
                    <label for="status">Statut</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="project">Projet</label>
                    <select name="project" id="project" class="form-control">
                        <option value="">Tous les projets</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ request('project') == $proj->id ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_start">Date début</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" value="{{ request('date_start') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_end">Date fin</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" value="{{ request('date_end') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="search">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Nom, email...">
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des contributions</h6>
            <span class="badge bg-primary">{{ $contributions->total() }} contributions</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="contributionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Projet</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contributions as $contribution)
                            <tr>
                                <td>{{ $contribution->id }}</td>
                                <td>
                                    @if($contribution->anonymous)
                                        <span class="text-muted"><i data-feather="fas fa-user-x"></i> Anonyme</span>
                                    @else
                                        {{ $contribution->user->name ?? 'Utilisateur supprimé' }}
                                        <br>
                                        <small class="text-muted">{{ $contribution->user->email ?? '' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($contribution->project)
                                        <a href="{{ route('admin.projects.show', $contribution->project_id) }}" 
                                           class="text-decoration-none">
                                            {{ $contribution->project->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Projet supprimé</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($contribution->amount, 2) }} €</td>
                                <td>{{ $contribution->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($contribution->status == 'pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @elseif($contribution->status == 'completed')
                                        <span class="badge bg-success">Complété</span>
                                    @elseif($contribution->status == 'refunded')
                                        <span class="badge bg-danger">Remboursé</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.contributions.show', $contribution->id) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i data-feather="eye" class="text-white"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune contribution trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $contributions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection