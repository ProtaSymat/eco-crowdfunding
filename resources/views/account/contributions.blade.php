@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Mes contributions</h3>
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
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i data-feather="heart" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Projets soutenus</h5>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $projectsSupported ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded p-3 me-3">
                                <i data-feather="dollar-sign" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Montant total</h5>
                                <p class="text-muted mb-0">Contributions</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ number_format($totalAmount ?? 0, 2) }} €</h3>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded p-3 me-3">
                                <i data-feather="check-circle" class="feather"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Contributions</h5>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $contributionsCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Évolution des contributions</h5>
            </div>
            <div class="card-body">
                <canvas id="contributionsChart" height="250"></canvas>
            </div>
        </div>
        
        <h4 class="mt-4">Historique des contributions</h4>
        
        @if(isset($contributions) && count($contributions) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Projet</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Reçu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contributions as $contribution)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($contribution->project->cover_image)
                                            <img src="{{ asset('storage/' . $contribution->project->cover_image) }}" class="rounded me-2" width="40" height="40" alt="{{ $contribution->project->name }}">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                                                <i data-feather="image" class="feather-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $contribution->project->name }}</h6>
                                            <small class="text-muted">{{ $contribution->project->category->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($contribution->amount, 2) }} €</td>
                                <td>{{ $contribution->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($contribution->status == 'completed')
                                        <span class="badge bg-success">Complété</span>
                                    @elseif($contribution->status == 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($contribution->status == 'failed')
                                        <span class="badge bg-danger">Échoué</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $contribution->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contribution->donation)
                                        <span class="badge bg-info">Oui</span>
                                    @else
                                        <span class="badge bg-light text-dark">Non</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('project.show', $contribution->project->slug) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Voir le projet">
                                            <i data-feather="eye" class="feather-sm"></i>
                                        </a>
                                        @if($contribution->status == 'completed')
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $contribution->id }}" title="Voir le reçu">
                                                <i data-feather="file-text" class="feather-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    @if($contribution->status == 'completed')
                                    <div class="modal fade" id="receiptModal{{ $contribution->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $contribution->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="receiptModalLabel{{ $contribution->id }}">Reçu de contribution</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-4">
                                                        <div class="mb-3">
                                                            <i data-feather="check-circle" class="text-success" style="width: 48px; height: 48px;"></i>
                                                        </div>
                                                        <h4 class="mb-0">Merci pour votre soutien !</h4>
                                                        <p class="text-muted">Transaction #{{ $contribution->transaction_id }}</p>
                                                    </div>
                                                    
                                                    <div class="border-top pt-3 mb-3">
                                                        <p><strong>Projet :</strong> {{ $contribution->project->name }}</p>
                                                        <p><strong>Montant :</strong> {{ number_format($contribution->amount, 2) }} €</p>
                                                        <p><strong>Date :</strong> {{ $contribution->created_at->format('d/m/Y à H:i') }}</p>
                                                        <p><strong>Méthode de paiement :</strong> 
                                                            @if($contribution->payment_method == 'credit_card')
                                                                Carte de crédit
                                                            @elseif($contribution->payment_method == 'paypal')
                                                                PayPal
                                                            @else
                                                                {{ $contribution->payment_method }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    @if($contribution->comment)
                                                    <div class="alert alert-light">
                                                        <strong>Votre commentaire :</strong><br>
                                                        {{ $contribution->comment }}
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    <button type="button" class="btn btn-primary" onclick="window.print()">
                                                        <i data-feather="printer" class="feather-sm me-1"></i>Imprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $contributions->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i data-feather="info" class="feather-sm me-1"></i>
                Vous n'avez pas encore effectué de contributions. Découvrez des projets qui pourraient vous intéresser !
            </div>
            <a href="{{ route('project.index') }}" class="btn btn-primary">Découvrir des projets</a>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('contributionsChart').getContext('2d');
        
        const chartData = {
            labels: {!! json_encode($months ?? []) !!},
            datasets: [{
                label: 'Contributions (€)',
                data: {!! json_encode($contributionData ?? []) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.2)',
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };
        
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
                    },
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });
    });
</script>
@endsection