@extends('layouts.main')

@section('title')
@lang('episode.title')
@endsection

@section('content')
    <div>
        <form method="get" action=""><input type="text" name="query" value="{{ $query }}"></form>
    </div>

    @if ($query)
    <div>@lang('episode.search.query', ['query' => $query])</div>

        @if ($episodes->isEmpty())
        <div>@lang('episode.search.not_found')</div>
        @endif
    @endif

    @if (!$episodes->isEmpty())
    <div>
    @foreach ($episodes as $episode)
        <div>
            <a href="{{ $episode->url }}">
                <strong>{{ $episode->meta['series']['name_ru'] }}</strong>
                <span>{{ $episode->name_ru }}</span>
                <small>{{ $episode->released_at->format('d.m.Y') }}</small>
                <small>{{ $episode->number }}</small>
            </a>
    @endforeach
    </div>
    @endif

    <div>{{ $episodes->links() }}</div>
@endsection