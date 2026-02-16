@extends('layouts.app')

@section('title', 'Edit Movie')

@section('content')

<h2>Edit Movie</h2>
    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title', $movie->title) }}">

        <label>Image:</label>
        <input type="file" name="image" accept="image/*">

        @if (isset($movie) && $movie?->image)
            <p>Current Image:</p>
            <img src="{{ $movie?->image_url }}" width="200" height="250">
            <input type="checkbox" name="remove_image" value="1">
            [Delete Image]
        @endif

        <label>Director:</label>
        <input type="text" name="director" value="{{ old('director', $movie->director->name ?? 'Unknown director') }}">

        <label>Description:</label>
        <textarea name="description">{{ old('description', $movie->description) }}</textarea>

        <label>Year:</label>
        <input type="text" name="year" value="{{ old('year', $movie->year) }}">

        <label>Genre:</label>
        <input type="text" name="genre" value="{{ old('genre', $movie->genre) }}">

        <label>Rating:</label>
        <input type="text" name="rating" value="{{ old('rating', $movie->rating) }}">

        <label>Status:</label>
        <select name="status">
            @foreach (\App\Enums\MovieStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', $movie->status->value) === $status->value ? 'selected' : '' }}>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>

        <button type="submit">Update</button>
    </form>
    @include('errors.form-errors')

@endsection
