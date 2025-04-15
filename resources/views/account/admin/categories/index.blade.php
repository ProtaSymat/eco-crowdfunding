@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex mb-4 align-items-center">
        <h1 class="me-4">Catégories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-0">Ajouter une catégorie</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td class="d-flex flex-row">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info text-white me-3">
                        <i data-feather="edit-3" class="feather-sm"></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i data-feather="trash" class="feather-sm"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection