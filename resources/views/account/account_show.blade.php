@extends('account.account_layout')

@section('account_content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Profil de l'utilisateur</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Nom :</strong> {{ $user->name }}
        </div>
        <div class="mb-3">
            <strong>Email :</strong> {{ $user->email }}
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Modifier le profil</a>
    </div>
</div>
@endsection