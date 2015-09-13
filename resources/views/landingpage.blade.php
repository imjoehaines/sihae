@extends('layout')

@section('content')
  <ul class="post-list">
    @forelse ($posts as $post)
      <li>
        <h2><a href="#">{{ $post->title }}</a></h2>
        <p>{{ $post->summary }}</p>
        <small class="post-date">posted {{ $post->timeSinceDateCreated() }}</small>
      </li>
    @empty
      <li>
        <h2>There aren't any posts!</h2>
      </li>
    @endforelse
  </ul>
@endsection
