@extends('layouts.app')

@section('title', $movie->title)

@section('content')

    <a href="{{ route('movies.index') }}">Back to All Movies</a>

    <h1>{{ $movie->title }}</h1>

    @if ($movie->image)
        <img src="{{ $movie->image_url }}" alt="{{ $movie->title }}" width="200" height="250"><br>
    @endif

    <b>Director: </b><p>{{ $movie->director->name ?? 'Unknown director'}}</p>
    <b>Description: </b><p>{{ $movie->description}}</p>
    <b>Year: </b><p>{{ $movie->year}}</p>
    <b>Genre: </b><p>{{ $movie->genre }}</p>
    <b>Rating: </b><p>{{ $movie->rating }}</p>
@endsection
