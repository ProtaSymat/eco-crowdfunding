@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Mes favoris</h3>
        <a href="{{ route('project.index') }}" class="btn btn-primary">
            <i data-feather="search" class="feather-sm me-1"></i>Découvrir des projets
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i data-feather="check-circle" class="feather-sm me-1"></i>
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            @forelse($favorites as $favorite)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">

                            <img src="{{ asset('storage/' . $favorite->project->cover_image) }}" class="card-img-top" alt="{{ $favorite->project->title }}">
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $favorite->project->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($favorite->project->short_description, 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('project.show', $favorite->project->slug) }}" class="btn btn-sm btn-outline-primary">Voir le projet</a>
                                <button 
                                    class="btn btn-sm btn-outline-warning remove-favorite"
                                    data-project-id="{{ $favorite->project->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Retirer des favoris">
                                    <i data-feather="star" class="feather-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i data-feather="info" class="feather-sm me-1"></i>
                        Vous n'avez pas encore de projets favoris. Découvrez des projets pour en ajouter à vos favoris !
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        document.querySelectorAll('.remove-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const projectId = this.getAttribute('data-project-id');
                const card = this.closest('.col-md-6');
                
                fetch(`/projects/${projectId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'removed') {
                        card.style.transition = 'opacity 0.3s';
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.remove();
                            
                            if (document.querySelectorAll('.remove-favorite').length === 0) {
                                document.querySelector('.row').innerHTML = `
                                    <div class="col-md-6 col-lg-4">
                                        <div class="alert alert-info">
                                            <i data-feather="info" class="feather-sm me-1"></i>
                                            Vous n'avez pas encore de projets favoris. Découvrez des projets pour en ajouter à vos favoris !
                                        </div>
                                    </div>
                                `;
                                feather.replace();
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du retrait des favoris:', error);
                });
            });
        });
    });
</script>
@endpush