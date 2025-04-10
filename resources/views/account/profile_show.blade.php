{{-- resources/views/account/profile_show.blade.php --}}
@extends('layouts.app') {{-- Assurez-vous que ce layout existe --}}

@section('content')
<div class="container">
    <h2>Profil de l'utilisateur</h2>
    <div class="mb-3">
        <strong>Nom :</strong> {{ $user->name }}
    </div>
    <div class="mb-3">
        <strong>Email :</strong> {{ $user->email }}
    </div>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Modifier le profil</a>
</div>
@endsection