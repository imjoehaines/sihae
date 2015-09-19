@inject('config', 'Sihae\Providers\ConfigServiceProvider')

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" href="/lib/simplemde.min.css">
  <script src="/lib/simplemde.min.js"></script>

  <link rel="stylesheet" href="/css/sihae.css">

  <title>{{ $config::get('title') }}</title>
</head>
<body>
  @include('partials/sidebar')

  <main>
    @if (Auth::user())
      @include('partials/admin')
    @endif

    @yield('content')
  </main>
</body>
</html>
