@extends('layout')

@section('content')
  <h1>{{ $title }}
    @if (Auth::check())
      <small class="post-edit">
        <a href="/post/edit/{{ $slug }}"><i class="fa fa-pencil"></i> Edit this post</a>
      </small>
    @endif
  </h1>

  <small class="post-date" title="{{ $created->format('jS \o\f F Y \a\t g:ia') }}">
    Posted {{ $created->diffForHumans() }}
  </small>

  {!! $body !!}
@endsection
