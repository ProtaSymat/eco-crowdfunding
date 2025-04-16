@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h3 class="mb-0">Mon tableau de bord</h3>
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
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i data-feather="dollar-sign" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Mes contributions</h5>
                                <p class="text-muted mb-0">Projets que vous soutenez</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $projectsSupported ?? 0 }}</h3>
                        <div class="mt-3">
                            <a href="{{ route('user.contributions') }}" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded p-3 me-3">
                                <i data-feather="star" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Projets favoris</h5>
                                <p class="text-muted mb-0">Projets que vous suivez</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $favoritesCount ?? 0 }}</h3>
                        <div class="mt-3">
                            <a href="#" class="btn btn-sm btn-warning text-white">Voir tout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-gradient-primary text-white mt-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary">Devenir créateur</h4>
                        <p class="text-primary">Vous avez un projet à financer ? Partagez votre projet avec notre communauté et obtenez le financement dont vous avez besoin.</p>
                    </div>
                    <form action="{{ route('profile.become-creator') }}" method="POST" class="col-md-4 text-md-end">
                    @csrf
                    <button type="submit" class="btn btn-light">Commencer maintenant</button>
                </form>
                </div>
            </div>
        </div>
        
        <h4 class="mt-4">Vos dernières contributions</h4>
        
        @if(isset($recentContributions) && count($recentContributions) > 0)
            <div class="row">
                @foreach($recentContributions as $contribution)
                <div class="col-md-6 col-lg-3">
                    <div class="contribution-card">
                        <div class="contribution-thumbnail">
                            <img src="{{ asset('storage/' . $contribution->project->cover_image) }}" alt="{{ $contribution->project->name }}">
                            <div class="contribution-amount">{{ number_format($contribution->amount, 2) }} €</div>
                        </div>
                        <div class="contribution-body">
                            <div class="contribution-date">
                                <i class="far fa-calendar-alt me-2"></i> {{ $contribution->created_at->format('d/m/Y') }}
                            </div>
                            <h5 class="contribution-title">{{ $contribution->project->name }}</h5>
                            
                            @if(isset($contribution->project->total_collected) && isset($contribution->project->goal))
                            <div class="progress-bar-container">
                                <div class="progress-bar" data-progress="{{ min(100, ($contribution->project->total_collected / $contribution->project->goal) * 100) }}"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <small class="text-muted">{{ min(100, round(($contribution->project->total_collected / $contribution->project->goal) * 100)) }}% financé</small>
                                
                                @if(isset($contribution->project->end_date))
                                <small class="text-muted">{{ Carbon\Carbon::parse($contribution->project->end_date)->diffInDays(now()) }} jours restants</small>
                                @endif
                            </div>
                            @endif
                            
                            <a href="{{ route('project.show', $contribution->project->slug) }}" class="btn btn-view-project w-100">
                                Voir le projet
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
            <i data-feather="info" class="feather-sm me-1"></i>
                Vous n'avez pas encore contribué à des projets. Découvrez des projets intéressants et soutenez-les !
            </div>
            <a href="{{ route('project.index') }}" class="btn btn-primary">Découvrir des projets</a>
        @endif
    </div>
</div>

<style>
    .contribution-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
        background-color: white;
        height: 100%;
    }
    
    .contribution-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .contribution-thumbnail {
        position: relative;
        overflow: hidden;
        height: 150px;
    }
    
    .contribution-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .contribution-card:hover .contribution-thumbnail img {
        transform: scale(1.05);
    }
    
    .contribution-body {
        padding: 20px;
    }
    
    .contribution-amount {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(13, 110, 253, 0.9);
        color: white;
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .contribution-date {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .contribution-title {
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 48px;
    }
    
    .btn-view-project {
        background-color: transparent;
        color: #0d6efd;
        border: 1px solid #0d6efd;
        border-radius: 20px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    
    .btn-view-project:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .progress-bar-container {
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        margin: 15px 0;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        background: linear-gradient(to right, #0d6efd, #0099ff);
        border-radius: 3px;
        transition: width 1.5s ease;
        width: 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const progress = bar.getAttribute('data-progress');
                bar.style.width = progress + '%';
            });
        }, 300);
    });
</script>
@endsection

