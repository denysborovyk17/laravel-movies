@extends('layouts.app')

@section('title', 'Create Movie')

@section('content')

<h1>Create Movie</h1>
    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title') }}">

        <label>Image:</label>
        <input type="file" name="image" accept="image/*">

        <label>Director:</label>
        <input type="text" name="director" value="{{ old('director') }}">

        <label>Description:</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Year:</label>
        <input type="text" name="year" value="{{ old('year') }}">

        <label>Genre:</label>
        <input type="text" name="genre" value="{{ old('genre') }}">

        <label>Rating:</label>
        <input type="text" name="rating" value="{{ old('rating') }}">

        <label>Status:</label>
        <select name="status">
            @foreach (\App\Enums\MovieStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', 'draft') === $status->value ? 'selected' : '' }}>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>

        <button type="submit">Save</button>
    </form>
    @include('errors.form-errors')

@endsection

