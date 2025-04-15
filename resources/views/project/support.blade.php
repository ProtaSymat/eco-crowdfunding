@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}" class="text-decoration-none">Projets</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.show', $project->slug) }}" class="text-decoration-none">{{ $project->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Soutenir</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Soutenir le projet : {{ $project->name }}</h2>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Objectif : {{ number_format($project->funding_goal, 0, ',', ' ') }} €</span>
                            <span>{{ $project->progress_percentage }}% atteint</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $project->progress_percentage }}%" 
                                aria-valuenow="{{ $project->progress_percentage }}" 
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('project.processSupport', $project->slug) }}" id="payment-form">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold">Montant de votre don (€)</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="amount" name="amount" min="{{ $project->min_contribution }}" value="{{ $project->min_contribution }}" required>
                                <span class="input-group-text">€</span>
                            </div>
                            <div class="form-text">Le montant minimum est de {{ $project->min_contribution }} €</div>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous" value="1">
                            <label class="form-check-label" for="anonymous">
                                Faire un don anonyme
                            </label>
                            <div class="form-text">Votre nom n'apparaîtra pas publiquement dans la liste des donateurs</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Commentaire (optionnel)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
                            <div class="form-text">Votre message sera visible sur la page du projet</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h4 class="mb-3">Informations de paiement</h4>
                        
                        <div class="mb-3">
                            <label for="card_name" class="form-label">Nom sur la carte</label>
                            <input type="text" class="form-control" id="card_name" name="card_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Numéro de carte</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" 
                                   placeholder="1234 5678 9012 3456" required maxlength="19">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Date d'expiration (MM/AA)</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                       placeholder="MM/AA" required maxlength="5">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">Code de sécurité (CVV)</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" required maxlength="4">
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="save_card" name="save_card" value="1">
                            <label class="form-check-label" for="save_card">
                                Sauvegarder ma carte pour mes prochains dons
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-lock me-2"></i>Payer {{ $project->min_contribution }} €
                            </button>
                            <a href="{{ route('project.show', $project->slug) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Résumé du projet</h5>
                    <p class="card-text">{{ $project->short_description }}</p>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Début:</span>
                        <span>{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Fin:</span>
                        <span>{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jours restants:</span>
                        <span>{{ $project->remaining_days }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span>État:</span>
                        <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'pending' ? 'warning' : ($project->status === 'completed' ? 'primary' : 'danger')) }}">
                            {{ $project->status === 'active' ? 'En cours' : ($project->status === 'pending' ? 'En attente' : ($project->status === 'completed' ? 'Financé' : 'Non financé')) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informations de sécurité</h5>
                    <p class="card-text small">
                        <i class="fas fa-lock me-2 text-success"></i>
                        Vos informations de paiement sont sécurisées et cryptées. Nous ne stockons pas les détails de votre carte.
                    </p>
                    <p class="card-text small">
                        <i class="fas fa-shield-alt me-2 text-success"></i>
                        Notre plateforme utilise les technologies de sécurité les plus récentes pour protéger vos données.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            this.value = formattedValue;
        });
        
        const expiryDateInput = document.getElementById('expiry_date');
        expiryDateInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            let formattedValue = '';
            
            if (value.length > 0) {
                formattedValue = value.substring(0, 2);
                if (value.length > 2) {
                    formattedValue += '/' + value.substring(2, 4);
                }
            }
            
            this.value = formattedValue;
        });
        
        const amountInput = document.getElementById('amount');
        const submitButton = document.querySelector('button[type="submit"]');
        
        amountInput.addEventListener('input', function() {
            submitButton.innerHTML = `<i class="fas fa-lock me-2"></i>Payer ${this.value} €`;
        });
    });
</script>
@endpush