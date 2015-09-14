@inject('config', 'Sihae\Providers\ConfigServiceProvider')
@extends('layout')

@section('content')
  <h2>{{ $title }}</h2>

  {!! $body !!}
@endsection
