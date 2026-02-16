@extends('admin.layouts.app')

@section('title', 'All Movies')

@section('content')

    <form action="{{ route('movies.index') }}" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search on Title or Description">
        <button type="submit">Search</button>
    </form>

    @foreach ($movies as $movie)
        <h3>{{ $movie->title }}</h3>

        @if ($movie->image)
            <img src="{{ $movie->image_url }}" alt="{{ $movie->title }}" width="200" height="300"><br>
        @endif

        <b>Director: </b><p>{{ $movie->director->name ?? 'Unknown director' }}</p>
        <b>Rating: </b><p>{{ $movie->rating }}</p>

        <a href="{{ route('movies.show', $movie->slug) }}">Read more</a>
        <hr>
    @endforeach
    <div class="mt-8 flex justify-center">
        {{ $movies->appends(request()->query())->links() }}
    </div>

@endsection
