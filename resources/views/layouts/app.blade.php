<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Diretório Digital') }}</title>
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('plugins/materialdesign/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/perfectScrollbar/perfect-scrollbar.css') }}"/>
  @stack('link')  
  @stack('style')
  <style type="text/css" id="apresentacao"></style>
  <!-- Scripts -->
  <script>
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
  </script>
</head>
<body>
    @yield('content')
    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/perfectScrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- <script src="{{ asset('js/admin.js') }}"></script> -->
    @stack('scripts')
  </body>
</html>