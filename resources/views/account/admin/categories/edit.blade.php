@extends('layouts.app') {{-- Assurez-vous que ce layout existe --}}

@section('content')
<div class="container">
    <h1>Modifier la catégorie : {{ $category->name }}</h1>
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $category->slug }}" required>
        </div>
        <div class="mb-3">
            <label for="icon" class="form-label">Icône (optionnel)</label>
            <input type="text" class="form-control" id="icon" name="icon" value="{{ $category->icon }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description (optionnel)</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $category->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection