@extends('layout')

@section('content')
  <h1>Edit a post</h1>

  @if ($errors->count())
    <h2>Oops &mdash; you seem to be missing something!</h2>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form action="/post/edit/{{ $slug }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="slug" value="{{ $slug }}">

    <label for="title">Title</label>
    <input name="title" id="title" maxlength="140" required value="{{ $title }}">

    <label for="body">Post</label>
    <textarea name="body" id="body" required>{{ $body }}</textarea>

    <button name="submit" type="submit">Edit post</button>
  </form>

  <script>
    var simplemde = new SimpleMDE()
    simplemde.render()
  </script>
@endsection
