<aside class="sidebar">
  <header>
    <h1><a href="/">{{ $config::get('title') }}</a></h1>
  </header>

  {!! nl2br(e($config::get('summary'))) !!}
</aside>
