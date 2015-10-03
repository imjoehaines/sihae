<aside class="sidebar">
  <header>
    <h1><a href="/">{{ $config::get('title') }}</a></h1>
  </header>

  {!! Purifier::clean(Markdown::string($config::get('summary'))) !!}
</aside>
