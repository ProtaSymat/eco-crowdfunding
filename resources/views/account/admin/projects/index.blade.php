@extends('account.account_layout')

@section('account_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des projets</h1>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.projects.index') }}" method="GET" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Statut</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="search">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Nom du projet, description...">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des projets</h6>
            <span class="badge bg-primary">{{ $projects->total() }} projets</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="projectsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="120">Miniature</th>
                            <th>Nom</th>
                            <th>Créateur</th>
                            <th>Catégorie</th>
                            <th>Objectif</th>
                            <th>Collecté</th>
                            <th>Progression</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/'. $project->cover_image) }}" class="img-thumbnail" 
                                         alt="{{ $project->name }}" width="100">
                                </td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->user->name }}</td>
                                <td>{{ $project->category->name }}</td>
                                <td>{{ number_format($project->funding_goal, 2) }} €</td>
                                <td>{{ number_format($project->total_collected, 2) }} €</td>
                                <td>
                                    <div class="progress">
                                        @php
                                            $percentage = $project->funding_goal > 0 ? ($project->total_collected / $project->funding_goal) * 100 : 0;
                                            $percentage = min(100, $percentage);
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ round($percentage) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if($project->status == 'pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @elseif($project->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($project->status == 'completed')
                                        <span class="badge bg-info">Terminé</span>
                                    @elseif($project->status == 'rejected')
                                        <span class="badge bg-danger">Rejeté</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.projects.show', $project->id) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i data-feather="eye" class="text-white"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                           class="btn btn-sm btn-primary" title="Modifier">
                                            <i data-feather="edit" class="text-white"></i>

                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-project" 
                                                data-id="{{ $project->id }}" title="Supprimer">
                                            <i data-feather="trash" class="text-white"></i>

                                        </button>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary change-status" 
                                                data-id="{{ $project->id }}" data-status="{{ $project->status }}">
                                            Changer le statut
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Aucun projet trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.</p>
                <p class="text-danger">Attention : un projet avec des contributions ne peut pas être supprimé.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteProjectForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut du projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changeStatusForm" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="project_status" class="form-label">Nouveau statut</label>
                        <select class="form-control" id="project_status" name="status" required>
                            <option value="pending">En attente</option>
                            <option value="active">Actif</option>
                            <option value="completed">Terminé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    
                    <div id="rejection_reason_div" class="mb-3 d-none">
                        <label for="rejection_reason" class="form-label">Motif de rejet</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"></textarea>
                        <small class="text-muted">Veuillez expliquer pourquoi ce projet est rejeté.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Suppression de projet
        const deleteButtons = document.querySelectorAll('.delete-project');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const projectId = this.getAttribute('data-id');
                const form = document.getElementById('deleteProjectForm');
                form.action = `/admin/projects/${projectId}`;
                
                const modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
                modal.show();
            });
        });
        
        // Changement de statut
        const statusButtons = document.querySelectorAll('.change-status');
        statusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const projectId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status');
                const form = document.getElementById('changeStatusForm');
                form.action = `/admin/projects/${projectId}/status`;
                
                const statusSelect = document.getElementById('project_status');
                statusSelect.value = currentStatus;
                
                // Afficher/masquer le champ de motif de rejet
                toggleRejectionReason(currentStatus);
                
                const modal = new bootstrap.Modal(document.getElementById('changeStatusModal'));
                modal.show();
            });
        });
        
        // Afficher/masquer le champ de motif de rejet en fonction du statut
        const statusSelect = document.getElementById('project_status');
        statusSelect.addEventListener('change', function() {
            toggleRejectionReason(this.value);
        });
        
        function toggleRejectionReason(status) {
            const rejectionDiv = document.getElementById('rejection_reason_div');
            const rejectionInput = document.getElementById('rejection_reason');
            
            if (status === 'rejected') {
                rejectionDiv.classList.remove('d-none');
                rejectionInput.setAttribute('required', 'required');
            } else {
                rejectionDiv.classList.add('d-none');
                rejectionInput.removeAttribute('required');
            }
        }
    });
</script>
@endsection