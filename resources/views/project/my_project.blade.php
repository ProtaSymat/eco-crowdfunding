@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Mes projets</h3>
        <div class="text-right">
            <a href="{{ route('project.create') }}" class="btn btn-primary">Créer un nouveau projet</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            @forelse($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ Storage::url($project->cover_image) }}" class="card-img-top" alt="{{ $project->name }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-top mb-2">
                                <h5 class="card-title">{{ $project->name }}</h5>
                                <div class="dropdown">
                                    <button class="bg-transparent border-0" id="dropdownMenu{{ $project->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $project->id }}">
                                        <li><a class="dropdown-item" href="{{ route('project.show', $project->slug) }}"><i data-feather="eye" class="feather-sm me-2"></i> Voir</a></li>
                                        <li><a class="dropdown-item" href="{{ route('project.edit', $project->slug) }}"><i data-feather="edit-2" class="feather-sm me-2"></i> Modifier</a></li>
                                        <li><a class="dropdown-item" href="#"><i data-feather="bar-chart-2" class="feather-sm me-2"></i> Statistiques</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="card-text fw-light">{{ $project->short_description }}</p>
                            <div class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($project->status) }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Vous n'avez pas encore créé de projet. <a href="{{ route('project.create') }}">Créer votre premier projet</a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection