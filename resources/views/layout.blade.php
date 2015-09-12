<!DOCTYPE html>
<html>
<head>
  <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic|Lato:300,400' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdn.rawgit.com/mblode/marx/master/css/marx.min.css">
  <link rel="stylesheet" href="sihae.css">

  <title>{{ $title or 'Sihae' }}</title>
</head>
<body>
  <main>
    <header>
      <h1>{{ $title or 'Sihae' }}</h1>
    </header>

    <article>@yield('content')</article>
    <aside>@include('sidebar')</aside>
  </main>
</body>
</html>
