@extends('account.account_layout')

@section('account_content')
<!-- Contenu pour les créateurs -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Tableau de bord créateur</h3>
        <a href="{{ route('creator.project.create') }}" class="btn btn-success">
            <i data-feather="plus" class="feather-sm me-1"></i>Nouveau projet
        </a>
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
        
        <div class="row">
            <!-- Statistiques des projets -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded p-3 me-3">
                                <i data-feather="briefcase" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Mes projets</h5>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $projectsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            
            <!-- Fonds récoltés -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded p-3 me-3">
                                <i data-feather="dollar-sign" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Fonds récoltés</h5>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ number_format($totalFunds ?? 0, 2) }} €</h3>
                    </div>
                </div>
            </div>
            
            <!-- Contributeurs -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded p-3 me-3">
                                <i data-feather="users" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Contributeurs</h5>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $contributorsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graphique d'évolution des contributions -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Évolution des contributions</h5>
            </div>
            <div class="card-body">
                <canvas id="contributionsChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Projets en cours -->
        <h4 class="mt-4">Vos projets actifs</h4>
        
        @if(isset($activeProjects) && count($activeProjects) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Projet</th>
                            <th>Objectif</th>
                            <th>Collecté</th>
                            <th>Progression</th>
                            <th>Jours restants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeProjects as $project)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($project->thumbnail)
                                            <img src="{{ asset($project->thumbnail) }}" class="rounded me-2" width="40" height="40" alt="{{ $project->title }}">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                                <i data-feather="image" class="feather-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $project->title }}</h6>
                                            <small class="text-muted">{{ $project->category->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($project->goal, 2) }} €</td>
                                <td>{{ number_format($project->current_amount, 2) }} €</td>
                                <td>
                                    @php
                                        $percentage = ($project->current_amount / $project->goal) * 100;
                                        $percentageClass = $percentage < 30 ? 'danger' : ($percentage < 70 ? 'warning' : 'success');
                                    @endphp
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $percentageClass }}" role="progressbar" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                </td>
                                <td>
                                    @php
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays($project->end_date, false);
                                    @endphp
                                    @if($daysLeft > 0)
                                        <span class="badge bg-info">{{ $daysLeft }} jours</span>
                                    @elseif($daysLeft == 0)
                                        <span class="badge bg-warning">Dernier jour</span>
                                    @else
                                        <span class="badge bg-secondary">Terminé</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Voir">
                                            <i data-feather="eye" class="feather-sm"></i>
                                        </a>
                                        <a href="{{ route('creator.project.edit', $project->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Modifier">
                                            <i data-feather="edit" class="feather-sm"></i>
                                        </a>
                                        <a href="{{ route('creator.project.stats', $project->id) }}" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Statistiques">
                                            <i data-feather="bar-chart-2" class="feather-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i data-feather="info" class="feather-sm me-1"></i>
                Vous n'avez pas encore de projets actifs. Créez votre premier projet !
            </div>
            <a href="{{ route('creator.project.create') }}" class="btn btn-success">Créer un projet</a>
        @endif
    </div>
</div>

<!-- Activité récente -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">Activité récente</h4>
    </div>
    <div class="card-body">
        @if(isset($recentActivity) && count($recentActivity) > 0)
            <div class="timeline">
                @foreach($recentActivity as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $activity->type == 'contribution' ? 'success' : 'info' }}">
                            <i data-feather="{{ $activity->type == 'contribution' ? 'dollar-sign' : 'message-circle' }}" class="feather-sm text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-0">{{ $activity->title }}</h6>
                            <p class="text-muted mb-2">{{ $activity->description }}</p>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i data-feather="activity" class="feather text-muted mb-3" style="width: 48px; height: 48px;"></i>
                <p class="mb-0">Aucune activité récente à afficher.</p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Affichage du graphique d'évolution des contributions (exemple)
        const ctx = document.getElementById('contributionsChart').getContext('2d');
        
        // Données d'exemple à remplacer par des données réelles
        const chartData = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Contributions (€)',
                data: [0, 0, 0, 0, 0, 0], // À remplacer par des données réelles
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        };
        
        // Initialisation du graphique
        const myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' €';
                            }
                        }
                    }
                }
            }
        });
        
        // Tooltips d'initialisation
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 25px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-marker {
    position: absolute;
    width: 24px;
    height: 24px;
    left: -30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -20px;
    top: 24px;
    height: calc(100% - 24px);
    width: 2px;
    background-color: #e9ecef;
}
</style>
@endsection