@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Tableau de bord administrateur</h3>
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
        
    </div>
</div>
@endsection