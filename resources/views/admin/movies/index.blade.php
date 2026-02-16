@extends('admin.layouts.app')

@section('title', 'Movies')

@section('content')

<h2>Admin Page Movies</h2>

    <a href="{{ route('admin.movies.create') }}">Create New Movie</a>

    <form action="{{ route('admin.movies.index') }}" method="GET">
        <select name="status" onchange="this.form.submit()">
            <option value="">All status</option>

            @foreach (\App\Enums\MovieStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    {{ request('status') == $status->value ? 'selected' : '' }}>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>
    </form>

    @foreach ($movies as $movie)
        <div>
            <h3>{{ $movie->title }}</h3>
            @if($movie->image)
                <img src="{{ $movie->image_url }}" width="200" height="300"><br>
            @endif
            <b>Director: </b><p>{{ $movie->director->name ?? 'Unknown director'}}</p>
            <b>Description: </b><p>{{ $movie->description}}</p>
            <b>Year: </b><p>{{ $movie->year}}</p>
            <b>Genre: </b><p>{{ $movie->genre }}</p>
            <b>Rating: </b><p>{{ $movie->rating }}</p>
            <b>Status: </b><p>{{ ucfirst($movie->status->value) }}</p>
            <a href="{{ route('admin.movies.edit', $movie->id) }}">Edit</a>
            <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" style="display: inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <hr>
        </div>
    @endforeach
    <div class="mt-8 flex justify-center">
        {{ $movies->appends(request()->query())->links() }}
    </div>

@endsection
