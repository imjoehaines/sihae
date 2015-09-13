@extends('layout')

@section('content')
  @forelse($posts as $post)
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->summary }} <small>— posted {{ $post->timeSinceDateCreated() }}</small> </p>
  @empty
    <h2>There aren't any posts!</h2>
  @endforelse
@endsection
