@inject('config', 'Sihae\Providers\ConfigServiceProvider')

<!DOCTYPE html>
<html>
<head>
  <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,400italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/sihae.css">

  <title>{{ $config::get('title') }}</title>
</head>
<body>
  @include('sidebar')

  <main>
    @yield('content')
  </main>
</body>
</html>
