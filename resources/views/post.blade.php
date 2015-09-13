@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <h2>{{ $title }}</h2>

  {{-- NOTE: ensure this is filtered for XSS --}}
  {!! Markdown::string($body) !!}
@endsection
