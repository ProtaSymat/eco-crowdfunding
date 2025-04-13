@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes projets</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('project.create') }}" class="btn btn-primary">Créer un nouveau projet</a>
        </div>
    </div>
    
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
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="card-text">{{ $project->short_description }}</p>
                        <div class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($project->status) }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <a href="{{ route('project.show', $project->slug) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            <a href="{{ route('project.edit', $project->slug) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                            <a href="#" class="btn btn-sm btn-outline-info">Statistiques</a>
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
@endsection