@inject('config', 'Sihae\Providers\ConfigServiceProvider')

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

  <link rel="stylesheet" href="/lib/simplemde.min.css">
  <script src="/lib/simplemde.min.js"></script>

  <link rel="stylesheet" href="/lib/tomorrow-night-eighties.css">
  <script src="/lib/highlight.pack.js"></script>

  <link rel="stylesheet" href="/lib/alert-alert.min.css">
  <script src="/lib/alert-alert.min.js"></script>

  <link rel="stylesheet" href="/css/sihae.css">

  <title>{{ $config::get('title') }}</title>
</head>
<body>
  @include('partials/sidebar')

  <main>
    @yield('content')
  </main>

  @include('partials/flashmessages')
  <script>hljs.initHighlightingOnLoad()</script>
</body>
</html>
