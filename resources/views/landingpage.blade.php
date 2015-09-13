@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <ol class="post-list">
    @forelse ($posts as $post)
      <li>
        <h2><a href="/post/{{ $post->slug }}">{{ $post->title }}</a></h2>
        <p>{{ $post->summary }}</p>
        <small class="post-date">posted {{ $post->timeSinceDateCreated() }}</small>
      </li>
    @empty
      <li>
        <h2>There aren't any posts!</h2>
      </li>
    @endforelse
  </ol>

  @if ($posts->total() > $config::get('postsPerPage'))
    <ol class="post-pagination">
      <li class="post-pagination-newer">
        <a href="{{ $posts->previousPageUrl() }}" @if ($posts->currentPage() == 1) class="disabled" @endif>Newer Posts</a>
      </li>

      <li class="post-pagination-pages">
        <small>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</small>
      </li>

      <li class="post-pagination-older">
        <a href="{{ $posts->nextPageUrl() }}" @unless ($posts->hasMorePages()) class="disabled" @endunless>Older Posts</a>
      </li>
    </ol>
  @endif
@endsection
