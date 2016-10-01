@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <ol class="post-list">
    @forelse ($posts as $post)
      <li>
        <h1><a href="/post/{{ $post->slug }}">{{ $post->title }}</a></h1>

        <div class="post-summary">
          {!! $post->summary !!}
        </div>

        <small class="post-date">
          <span title="{{ $post->dateCreated()->format('jS \o\f F Y \a\t g:ia') }}">
            Posted {{ $post->timeSinceDateCreated() }}
          </span>

          @if (Auth::check())
            &mdash; <a href="/post/edit/{{ $post->slug }}"><i class="fa fa-pencil"></i> Edit this post</a>
          @endif
        </small>
      </li>
    @empty
      <li>
        <h1>There aren't any posts!</h1>
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
