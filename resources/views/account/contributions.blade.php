@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="mb-4">Mes contributions</h1>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if(auth()->user()->contributions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Projet</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Montant</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->contributions()->orderByDesc('created_at')->get() as $contribution)
                                        <tr>
                                            <td>
                                                <a href="{{ route('project.show', $contribution->project->slug) }}" class="text-decoration-none text-dark">
                                                    {{ $contribution->project->name }}
                                                </a>
                                            </td>
                                            <td>{{ $contribution->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($contribution->amount, 0, ',', ' ') }} €</td>
                                            <td>
                                                <span class="badge bg-{{ $contribution->status === 'completed' ? 'success' : ($contribution->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $contribution->status === 'completed' ? 'Complété' : ($contribution->status === 'pending' ? 'En attente' : 'Échoué') }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contributionModal-{{ $contribution->id }}">
                                                    <i class="fas fa-eye me-1"></i>Détails
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-hand-holding-usd fa-3x text-muted"></i>
                            </div>
                            <h5>Vous n'avez pas encore fait de contribution</h5>
                            <p class="text-muted">Découvrez des projets intéressants et soutenez-les !</p>
                            <a href="{{ route('project.index') }}" class="btn btn-primary mt-2">Découvrir des projets</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach(auth()->user()->contributions as $contribution)
    <div class="modal fade" id="contributionModal-{{ $contribution->id }}" tabindex="-1" aria-labelledby="contributionModalLabel-{{ $contribution->id }}" aria-hidden="true">
</div>